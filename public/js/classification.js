/**
 * classification.js — Flood-Vision v1.0
 *
 * Stable Architecture:
 *  - Video frame is drawn to canvas via ctx.drawImage() FIRST (no black screen).
 *  - OpenCV reads pixels from canvas via getImageData() — NO cv.VideoCapture.
 *    This eliminates "Bad size of input mat" errors permanently.
 *  - All Mats created inside computeWaterLevel() are local and .delete()'d immediately.
 *  - SMA(10) smoothing + 10-second non-overlapping DB sync.
 */
(function () {
    'use strict';

    // ── DOM References ─────────────────────────────────────────────────────────
    const video  = document.getElementById('videoInput');
    const canvas = document.getElementById('canvasOutput');
    if (!video || !canvas) return;
    const ctx = canvas.getContext('2d', { willReadFrequently: true });

    // ── OpenCV Ready Flag ──────────────────────────────────────────────────────
    let openCvReady = false;
    const _prevReady = window.onOpenCvReady;
    window.onOpenCvReady = function () {
        if (_prevReady) _prevReady();
        openCvReady = true;
        console.log('[CV] OpenCV.js ready ✓');
    };

    // ── Animation State ────────────────────────────────────────────────────────
    let isRunning = false;
    let rafId     = null;

    // ── ROI (fraction of full frame) ───────────────────────────────────────────
    const ROI_X1 = 0.30;  // 30% from left
    const ROI_X2 = 0.70;  // 70% from left  → 40% wide strip
    const ROI_Y1 = 0.20;  // 20% from top
    const ROI_Y2 = 1.00;  // down to bottom
    const EDGE_DENSITY = 0.08; // ≥8% of ROI row must be edge pixels

    // ── SMA Smoothing ──────────────────────────────────────────────────────────
    const SMA_N      = 10;
    const smaBuffer  = [];
    let   displayValue = 0;

    function pushSMA(raw) {
        smaBuffer.push(raw);
        if (smaBuffer.length > SMA_N) smaBuffer.shift();
        return smaBuffer.reduce((a, b) => a + b, 0) / smaBuffer.length;
    }

    // ── Database Sync ──────────────────────────────────────────────────────────
    let isUploading    = false;   // guard: block new request while one is in-flight
    let lastUploadTime = 0;       // ms timestamp of last SUCCESSFUL send
    let prevStatus     = 'NORMAL';
    let showSavedBadge  = false;  // shows "✓ Data Tersimpan" badge on HUD
    let savedBadgeTimer = null;

    async function trySyncDatabase(status, level) {
        const currentTime = Date.now();

        // Strict 10-second throttle; AWAS critical bypass allowed
        const isCritical = prevStatus !== 'AWAS' && status === 'AWAS';
        if (isUploading || (currentTime - lastUploadTime < 10000 && !isCritical)) return;

        isUploading = true;
        prevStatus  = status;
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) return;

            const riverSelect = document.getElementById('camera_river_select');
            const sungai = riverSelect ? riverSelect.value : 'Sungai Gumbasa';

            const res = await fetch('/api/logs', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: JSON.stringify({ 
                    status, 
                    nilai_level: Math.round(level),
                    sungai: sungai 
                }),
            });

            if (res.ok) {
                lastUploadTime = currentTime;  // update ONLY on confirmed success
                const t = new Date(currentTime).toLocaleTimeString('id-ID');
                console.log(`[SYNC] ✓ ${status} @ ${Math.round(level)}% — ${t}`);

                // Show "Data Tersimpan" HUD badge for 2 seconds
                showSavedBadge = true;
                if (savedBadgeTimer) clearTimeout(savedBadgeTimer);
                savedBadgeTimer = setTimeout(() => { showSavedBadge = false; }, 2000);
            }
        } catch (err) {
            console.warn('[SYNC] error:', err.message);
        } finally {
            isUploading = false;  // always release guard, even on error
        }
    }


    // ── Canvas ↔ Video Sync ────────────────────────────────────────────────────
    function syncCanvas() {
        const vw = video.videoWidth;
        const vh = video.videoHeight;
        if (!vw || !vh) return false;
        if (canvas.width !== vw || canvas.height !== vh) {
            canvas.width  = vw;
            canvas.height = vh;
            console.log(`[CV] Canvas → ${vw}×${vh}`);
        }
        return true;
    }

    // ── Core Detection ─────────────────────────────────────────────────────────
    /**
     * Reads current pixels from the canvas (already contains the drawn video frame),
     * runs Canny Edge detection on the ROI only, and returns a water level % (0-100)
     * or -1 if no qualifying edge was found.
     *
     * All cv.Mat objects are created and deleted LOCALLY — no size mismatch possible.
     */
    function computeWaterLevel() {
        if (!openCvReady || typeof cv === 'undefined' || !cv.Mat) return -1;

        const W = canvas.width;
        const H = canvas.height;
        if (!W || !H) return -1;

        // Compute ROI pixel bounds (clamped to canvas size for safety)
        const rx1 = Math.max(0, Math.min(W - 1, Math.floor(W * ROI_X1)));
        const rx2 = Math.max(0, Math.min(W,     Math.floor(W * ROI_X2)));
        const ry1 = Math.max(0, Math.min(H - 1, Math.floor(H * ROI_Y1)));
        const ry2 = Math.max(0, Math.min(H,     Math.floor(H * ROI_Y2)));
        const roiW = rx2 - rx1;
        const roiH = ry2 - ry1;
        if (roiW <= 0 || roiH <= 0) return -1;

        // ── Read ONLY the ROI pixels from canvas ─────────────────────────────
        let imageData;
        try {
            imageData = ctx.getImageData(rx1, ry1, roiW, roiH);
        } catch (e) {
            console.warn('[CV] getImageData failed:', e.message);
            return -1;
        }

        // Local Mats — all deleted before function returns
        let matRgba = null, matGray = null, matEq = null, matBlur = null, matEdge = null;
        try {
            // Create RGBA mat directly from ImageData (exact size, no mismatch)
            matRgba = cv.matFromImageData(imageData);          // CV_8UC4, roiW×roiH
            matGray = new cv.Mat(roiH, roiW, cv.CV_8UC1);
            matEq   = new cv.Mat(roiH, roiW, cv.CV_8UC1);
            matBlur = new cv.Mat(roiH, roiW, cv.CV_8UC1);
            matEdge = new cv.Mat(roiH, roiW, cv.CV_8UC1);

            // Step 1 — Grayscale
            cv.cvtColor(matRgba, matGray, cv.COLOR_RGBA2GRAY);

            // Step 2 — Histogram Equalisation (auto-contrast, great for dim rooms)
            cv.equalizeHist(matGray, matEq);

            // Step 3 — Gaussian Blur 5×5 (kills noise without blurring strong edges)
            cv.GaussianBlur(matEq, matBlur, new cv.Size(5, 5), 1.2, 1.2, cv.BORDER_DEFAULT);

            // Step 4 — Canny (25/80) — sensitive enough for dark-blue notebooks
            cv.Canny(matBlur, matEdge, 25, 80, 3, false);

            // ── Debug Preview (bottom-right corner) ──────────────────────────
            // Render Canny output into a small corner so you can see what CV sees
            const prevW = Math.floor(W * 0.22);
            const prevH = Math.floor(H * 0.22);
            const prevX = W - prevW - 10;
            const prevY = H - prevH - 10;
            const tmpCanvas       = document.createElement('canvas');
            tmpCanvas.width  = roiW;
            tmpCanvas.height = roiH;
            cv.imshow(tmpCanvas, matEdge);
            ctx.save();
            ctx.globalAlpha = 0.82;
            ctx.strokeStyle = 'rgba(255,230,0,0.75)';
            ctx.lineWidth   = 1.5;
            ctx.strokeRect(prevX - 1, prevY - 1, prevW + 2, prevH + 2);
            ctx.drawImage(tmpCanvas, prevX, prevY, prevW, prevH);
            ctx.globalAlpha    = 1;
            ctx.fillStyle      = 'rgba(255,230,0,0.85)';
            ctx.font           = 'bold 10px monospace';
            ctx.textAlign      = 'right';
            ctx.textBaseline   = 'bottom';
            ctx.fillText('CANNY DEBUG', W - 11, H - prevH - 14);
            ctx.restore();
            // ─────────────────────────────────────────────────────────────────

            // Step 5 — Bottom-up scan inside ROI
            const minPx = Math.max(3, Math.floor(roiW * EDGE_DENSITY));
            let topEdgeY = -1;

            for (let y = roiH - 1; y >= 0; y--) {
                let edgeCnt = 0;
                for (let x = 0; x < roiW; x++) {
                    if (matEdge.ucharPtr(y, x)[0] > 0) edgeCnt++;
                }
                if (edgeCnt >= minPx) {
                    topEdgeY = y;
                    // Climb upward through contiguous edge band
                    let gap = 0;
                    for (let yy = y - 1; yy >= 0; yy--) {
                        let cnt2 = 0;
                        for (let x = 0; x < roiW; x++) {
                            if (matEdge.ucharPtr(yy, x)[0] > 0) cnt2++;
                        }
                        if (cnt2 >= minPx) { topEdgeY = yy; gap = 0; }
                        else if (++gap > 4) break;
                    }
                    break;
                }
            }

            if (topEdgeY < 0) return -1;

            // Step 6 — Map Y within ROI → level %
            // topEdgeY=0      → 100% (edge at top of ROI  = max water)
            // topEdgeY=roiH-1 → 0%  (edge at bottom of ROI = no water)
            return Math.max(0, Math.min(100, 100 - (topEdgeY / roiH) * 100));

        } catch (err) {
            console.warn('[CV] computeWaterLevel error:', err.message);
            return -1;
        } finally {
            // ── Always free local Mats (no memory leaks) ───────────────────
            [matRgba, matGray, matEq, matBlur, matEdge].forEach(m => { if (m) m.delete(); });
        }
    }

    // ── HUD Rendering ──────────────────────────────────────────────────────────
    function drawHUD(dv) {
        const W = canvas.width;
        const H = canvas.height;

        let status = 'NORMAL';
        let color  = '#10b981';
        if      (dv >= 80) { status = 'BAHAYA'; color = '#ef4444'; }
        else if (dv >= 50) { status = 'SIAGA';  color = '#f59e0b'; }

        // ① Dim overlay
        ctx.fillStyle = 'rgba(0,0,0,0.18)';
        ctx.fillRect(0, 0, W, H);

        // ② ROI box (yellow dashed)
        const rx1 = Math.floor(W * ROI_X1), rx2 = Math.floor(W * ROI_X2);
        const ry1 = Math.floor(H * ROI_Y1), ry2 = Math.floor(H * ROI_Y2);
        ctx.strokeStyle = 'rgba(255,230,0,0.55)';
        ctx.lineWidth   = 2;
        ctx.setLineDash([8, 6]);
        ctx.strokeRect(rx1, ry1, rx2 - rx1, ry2 - ry1);
        ctx.setLineDash([]);

        // ③ Water fill tint below tracking line
        const roiH  = ry2 - ry1;
        const lineY = ry1 + roiH - (dv / 100) * roiH;
        ctx.fillStyle = color + '22';
        ctx.fillRect(rx1, lineY, rx2 - rx1, ry2 - lineY);

        // ④ Tracking line (full width, dashed)
        ctx.beginPath();
        ctx.moveTo(0, lineY);
        ctx.lineTo(W, lineY);
        ctx.strokeStyle = color;
        ctx.lineWidth   = 3;
        ctx.setLineDash([12, 6]);
        ctx.stroke();
        ctx.setLineDash([]);

        // ⑤ Water Level Bar (right edge, aligned with ROI)
        const barW = Math.max(18, W * 0.03);
        const barX = W - barW - 45;
        const barY = ry1;
        const fillH = (dv / 100) * roiH;

        ctx.fillStyle = 'rgba(255,255,255,0.07)';
        ctx.fillRect(barX, barY, barW, roiH);
        ctx.fillStyle = color;
        ctx.fillRect(barX, barY + roiH - fillH, barW, fillH);
        ctx.strokeStyle = 'rgba(255,255,255,0.35)';
        ctx.lineWidth   = 1.5;
        ctx.strokeRect(barX, barY, barW, roiH);

        ctx.fillStyle    = '#fff';
        ctx.font         = `bold ${Math.max(13, W * 0.019)}px "Plus Jakarta Sans",sans-serif`;
        ctx.textAlign    = 'center';
        ctx.textBaseline = 'bottom';
        ctx.shadowColor  = 'rgba(0,0,0,0.9)';
        ctx.shadowBlur   = 5;
        ctx.fillText(`${Math.round(dv)}%`, barX + barW / 2, barY - 6);
        ctx.shadowBlur   = 0;

        // ⑥ Status Badge (top-left)
        const fz  = Math.max(13, W * 0.021);
        ctx.font  = `bold ${fz}px "Plus Jakarta Sans",sans-serif`;
        ctx.textAlign = 'left';
        const label = `STATUS: ${status}`;
        const bx = 30, by = 24;
        const bw = ctx.measureText(label).width + 28;
        const bh = fz + 20;
        ctx.fillStyle = color;
        if (ctx.roundRect) {
            ctx.beginPath(); ctx.roundRect(bx, by, bw, bh, 8); ctx.fill();
        } else {
            ctx.fillRect(bx, by, bw, bh);
        }
        ctx.fillStyle    = '#fff';
        ctx.textBaseline = 'middle';
        ctx.shadowColor  = 'rgba(0,0,0,0.6)';
        ctx.shadowBlur   = 3;
        ctx.fillText(label, bx + 14, by + bh / 2);
        ctx.shadowBlur   = 0;

        // ③b "Data Tersimpan" saved badge — shown for 2 s after successful sync
        if (showSavedBadge) {
            const sfz = Math.max(11, W * 0.015);
            ctx.font  = `bold ${sfz}px "Plus Jakarta Sans",sans-serif`;
            const sLabel = '✓  Data Tersimpan';
            const sx  = bx;
            const sy  = by + bh + 8;
            const sw  = ctx.measureText(sLabel).width + 22;
            const sh  = sfz + 14;
            ctx.fillStyle = 'rgba(16,185,129,0.92)'; // emerald-500
            if (ctx.roundRect) {
                ctx.beginPath(); ctx.roundRect(sx, sy, sw, sh, 6); ctx.fill();
            } else {
                ctx.fillRect(sx, sy, sw, sh);
            }
            ctx.fillStyle    = '#fff';
            ctx.textBaseline = 'middle';
            ctx.shadowColor  = 'rgba(0,0,0,0.4)';
            ctx.shadowBlur   = 2;
            ctx.fillText(sLabel, sx + 11, sy + sh / 2);
            ctx.shadowBlur   = 0;
        }

        // ⑦ Military corner brackets
        const armLen = Math.max(22, W * 0.035);
        ctx.strokeStyle = 'rgba(255,255,255,0.65)';
        ctx.lineWidth   = 3;
        [[20,20,1],[W-20,20,2],[20,H-20,3],[W-20,H-20,4]].forEach(([cx,cy,t]) => {
            ctx.beginPath();
            if      (t===1){ ctx.moveTo(cx+armLen,cy); ctx.lineTo(cx,cy); ctx.lineTo(cx,cy+armLen); }
            else if (t===2){ ctx.moveTo(cx-armLen,cy); ctx.lineTo(cx,cy); ctx.lineTo(cx,cy+armLen); }
            else if (t===3){ ctx.moveTo(cx+armLen,cy); ctx.lineTo(cx,cy); ctx.lineTo(cx,cy-armLen); }
            else            { ctx.moveTo(cx-armLen,cy); ctx.lineTo(cx,cy); ctx.lineTo(cx,cy-armLen); }
            ctx.stroke();
        });

        return status;
    }

    // ── Main Render Loop ───────────────────────────────────────────────────────
    function processVideoFrame() {
        if (!isRunning) return;

        if (!video || video.readyState < 2 || video.paused || video.ended) {
            rafId = requestAnimationFrame(processVideoFrame);
            return;
        }

        if (!syncCanvas()) {
            rafId = requestAnimationFrame(processVideoFrame);
            return;
        }

        // ① Draw raw video — ALWAYS first. Guarantees no black screen.
        try {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        } catch (e) {
            console.error('[CV] drawImage failed:', e);
            rafId = requestAnimationFrame(processVideoFrame);
            return;
        }

        // ② Run detection on the canvas pixels just drawn
        const rawLevel = computeWaterLevel();

        // ③ SMA smoothing
        if (rawLevel >= 0) {
            displayValue = pushSMA(rawLevel);
        } else {
            // No edge: hold last value with slow decay
            displayValue = displayValue * 0.997;
        }

        // ④ Draw HUD overlay
        const currentStatus = drawHUD(displayValue);

        // ⑤ Smart DB sync (10 s, non-overlapping)
        trySyncDatabase(currentStatus, displayValue);

        rafId = requestAnimationFrame(processVideoFrame);
    }

    // ── Public API ─────────────────────────────────────────────────────────────
    window.startVisionSystem = function () {
        console.log('[CV] startVisionSystem');
        isRunning      = true;
        lastUploadTime = Date.now() - 10000; // force first sync immediately
        rafId = requestAnimationFrame(processVideoFrame);
    };

    window.stopVisionSystem = function () {
        console.log('[CV] stopVisionSystem');
        isRunning = false;
        if (rafId) { cancelAnimationFrame(rafId); rafId = null; }
        smaBuffer.length = 0;
        displayValue = 0;
    };

})();
