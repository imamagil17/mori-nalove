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
        // 1. Validasi disamakan dengan ENUM Database (Rendah, Sedang, Tinggi)
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'tingkat_genangan' => 'required|in:Rendah,Sedang,Tinggi',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            // 2. Simpan ke Database
            CitizenReport::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'lokasi' => $request->lokasi,
                'tingkat_genangan' => $request->tingkat_genangan,
                'deskripsi' => $request->deskripsi,
                'status' => 'Pending'
            ]);

            return redirect()->back()->with('success', 'Terima kasih! Laporan kondisi area berhasil dikirim.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function verify($id)
    {
        $report = CitizenReport::findOrFail($id);
        $report->status = 'Terverifikasi';
        $report->save();

        return redirect()->back()->with('success', 'Status laporan berhasil diverifikasi.');
    }
}