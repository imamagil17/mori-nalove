<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    // Menampilkan halaman kelola berita di Admin
    public function index()
    {
        $beritas = Berita::latest()->get();
        return view('admin.berita.index', compact('beritas'));
    }

    // Menyimpan berita baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto'  => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'konten'=> 'nullable|string',
        ]);

        // Proses Upload Foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('berita_fotos', 'public');
        }

        // Simpan ke Database
        Berita::create([
            'judul'  => $request->judul,
            'foto'   => $fotoPath,
            'konten' => $request->konten,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    // Menghapus berita
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // Hapus file foto dari storage jika ada
        if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
            Storage::disk('public')->delete($berita->foto);
        }

        // Hapus data dari database
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
    // Menampilkan halaman Edit
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    // Memproses data Update
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Boleh kosong kalau tidak ganti foto
            'konten'=> 'required|string', // Wajib diisi deskripsinya
        ]);

        $fotoPath = $berita->foto; // Ambil foto lama
        
        // Kalau admin upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage
            if ($berita->foto && Storage::disk('public')->exists($berita->foto)) {
                Storage::disk('public')->delete($berita->foto);
            }
            // Simpan foto baru
            $fotoPath = $request->file('foto')->store('berita_fotos', 'public');
        }

        // Update database
        $berita->update([
            'judul' => $request->judul,
            'foto' => $fotoPath,
            'konten' => $request->konten,
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }
}