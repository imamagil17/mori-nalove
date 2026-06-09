<?php

namespace App\Http\Controllers;

use App\Models\CitizenReport;
use Illuminate\Http\Request;

class CitizenReportController extends Controller
{
    public function index()
    {
        $reports = CitizenReport::with('user')->latest()->paginate(15);
        return view('admin.citizen_reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input nama_pelapor, lokasi, tingkat_genangan, deskripsi, dan foto_bukti
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tingkat_genangan' => 'required|in:Rendah,Sedang,Tinggi',
            'deskripsi' => 'nullable|string',
            'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // 2. Simpan file gambar ke storage public
            $fotoPath = null;
            if ($request->hasFile('foto_bukti')) {
                $fotoPath = $request->file('foto_bukti')->store('reports', 'public');
            }

            // 3. Simpan ke Database
            CitizenReport::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'nama_pelapor' => $request->nama_pelapor,
                'lokasi' => $request->lokasi,
                'tingkat_genangan' => $request->tingkat_genangan,
                'deskripsi' => $request->deskripsi,
                'foto_bukti' => $fotoPath,
                'status' => 'Pending'
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Laporan kondisi area berhasil dikirim.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $report = CitizenReport::with('user')->findOrFail($id);
        return view('admin.citizen_reports.show', compact('report'));
    }

    public function verify($id)
    {
        $report = CitizenReport::findOrFail($id);
        $report->status = 'Terverifikasi';
        $report->save();

        return redirect()->route('admin.citizen_reports.index')->with('success', 'Status laporan berhasil diverifikasi.');
    }

    public function destroy($id)
    {
        $report = CitizenReport::findOrFail($id);
        
        // Hapus file foto dari storage jika ada
        if ($report->foto_bukti) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($report->foto_bukti);
        }
        
        $report->delete();

        return redirect()->route('admin.citizen_reports.index')->with('success', 'Laporan berhasil ditolak dan dihapus.');
    }
}