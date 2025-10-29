<?php

namespace App\Http\Controllers;

use App\Models\Siswa; 
use Illuminate\Http\Request; 

class SiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Cek jika user login dan role-nya bukan 'admin'
            if (auth()->check() && auth()->user()->role !== 'admin') {
                abort(403, 'Akses Ditolak. Fitur ini hanya untuk Bendahara Utama.');
            }
            return $next($request);
        });
    }

    // READ: Menampilkan daftar semua siswa
    public function index()
    {
        $siswas = Siswa::orderBy('no_absen', 'asc')->get();
        return view('siswa.index', compact('siswas'));
    }

    // CREATE: Menampilkan form untuk menambah siswa baru
    public function create()
    {
        return view('siswa.create');
    }

    // CREATE: Menyimpan siswa baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nis' => 'required|string|max:20|unique:siswas',
            'no_absen' => 'required|integer|unique:siswas',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
    }

    // UPDATE: Menampilkan form untuk mengedit siswa
    // $siswa otomatis diisi oleh Laravel (Route Model Binding)
    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    // UPDATE: Menyimpan perubahan data siswa ke database
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Pastikan NIS dan No. Absen unik, kecuali untuk data siswa ini sendiri
            'nis' => 'required|string|max:20|unique:siswas,nis,' . $siswa->id, 
            'no_absen' => 'required|integer|unique:siswas,no_absen,' . $siswa->id,
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui!');
    }

    // DELETE: Menghapus siswa dari database
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        
        // Hapus pembayaran terkait juga akan otomatis terjadi karena onDelete('cascade') di migrasi

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus!');
    }
}