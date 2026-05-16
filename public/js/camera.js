(function() {
    const startCameraButton = document.getElementById('startCamera');
    const closeCameraButton = document.getElementById('closeCameraModal');
    const cameraModal = document.getElementById('cameraModal');
    const video = document.getElementById('videoInput');
    
    let currentStream = null;

    if (startCameraButton) {
        startCameraButton.addEventListener('click', async () => {
            try {
                console.log("[Camera] Requesting access...");
                currentStream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 1280 }, height: { ideal: 720 } },
                    audio: false
                });

                video.srcObject = currentStream;
                video.muted = true; // Ensure muted for autoplay policy

                // Show Modal FIRST, so canvas has layout/dimensions
                if (cameraModal) {
                    cameraModal.classList.remove('hidden');
                    cameraModal.classList.add('flex');
                }

                // Wait 500ms for DOM to render the modal + canvas
                await new Promise(resolve => setTimeout(resolve, 500));

                // Now play video
                await video.play();
                console.log("[Camera] Video playing. videoWidth:", video.videoWidth, "videoHeight:", video.videoHeight);

                // Hide placeholder
                const placeholder = document.getElementById('cameraPlaceholder');
                if (placeholder) placeholder.style.display = 'none';

                // Trigger OpenCV vision system
                if (typeof window.startVisionSystem === 'function') {
                    window.startVisionSystem();
                } else {
                    console.warn("[Camera] window.startVisionSystem not found.");
                }

            } catch (error) {
                console.error('[Camera] Initialization failed:', error);
                alert('Gagal mengakses kamera: ' + error.message);
            }
        });
    }

    if (closeCameraButton) {
        closeCameraButton.addEventListener('click', () => {
            // Stop Vision System
            if (typeof window.stopVisionSystem === 'function') {
                window.stopVisionSystem();
            }
            // Hide Modal
            if (cameraModal) {
                cameraModal.classList.add('hidden');
                cameraModal.classList.remove('flex');
            }
            // Turn off webcam light by stopping all tracks
            if (currentStream) {
                currentStream.getTracks().forEach(track => track.stop());
                currentStream = null;
            }
            video.srcObject = null;
            
            // Show placeholder for next session
            const placeholder = document.getElementById('cameraPlaceholder');
            if (placeholder) placeholder.style.display = 'flex';
        });
    }
})();
