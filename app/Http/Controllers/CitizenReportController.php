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
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'kecamatan_desa' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'koordinat_lokasi' => 'required|string|max:255',
            'tingkat_genangan' => 'required|string|in:Rendah,Sedang,Tinggi',
            'deskripsi' => 'required|string',
            'jumlah_terdampak' => 'nullable|integer',
            'fasilitas_rusak' => 'nullable|string',
            
            // PERBAIKAN: Izinkan pengiriman Array banyak gambar sekaligus
            'foto_bukti' => 'required|array|min:1|max:5', // Maksimal 5 foto per laporan
            // PERBAIKAN: Perbesar batas maksimal masing-masing foto menjadi 15MB (15360 KB)
            'foto_bukti.*' => 'image|mimes:jpeg,png,jpg|max:15360', 
        ]);

        // Proses unggah BANYAK foto (Multiple Upload)
        $fotoPaths = [];
        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $file) {
                // Simpan setiap file dan masukkan path-nya ke dalam array
                $fotoPaths[] = $file->store('citizen_reports', 'public');
            }
        }

        \App\Models\CitizenReport::create([
            'nama_pelapor' => $validated['nama_pelapor'],
            'kecamatan_desa' => $validated['kecamatan_desa'],
            'lokasi' => $validated['lokasi'], 
            'koordinat_lokasi' => $validated['koordinat_lokasi'],
            'tingkat_genangan' => $validated['tingkat_genangan'],
            'deskripsi' => $validated['deskripsi'],
            'jumlah_terdampak' => $validated['jumlah_terdampak'] ?? 0,
            'fasilitas_rusak' => $validated['fasilitas_rusak'] ?? 'Tidak ada fasilitas rusak yang dilaporkan',
            
            'foto_bukti' => $fotoPaths, // Array yang akan otomatis menjadi JSON berkat Model
            'status' => 'Pending'
        ]);

        return redirect()->back()->with('success', 'Laporan beserta ' . count($fotoPaths) . ' foto bukti berhasil dikirim!');
    }

    public function show($id)
    {
        $report = CitizenReport::with('user')->findOrFail($id);
        return view('admin.citizen_reports.show', compact('report'));
    }

    public function verify($id)
    {
        $report = \App\Models\CitizenReport::findOrFail($id);

        // PERBAIKAN: Gunakan ejaan 'Terverifikasi' sesuai ENUM di database
        $report->update([
            'status' => 'Terverifikasi'
        ]);

        return redirect()->back()->with('success', 'Laporan dari wilayah ' . $report->kecamatan_desa . ' berhasil diverifikasi dan masuk ke database mitigasi!');
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