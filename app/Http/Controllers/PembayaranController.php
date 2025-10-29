<?php

namespace App\Http\Controllers;

use App\Models\Siswa; 
use App\Models\Pembayaran; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller; 

class PembayaranController extends Controller
{
    // Middleware untuk Role (Bendahara Utama)
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Akses Ditolak. Fitur ini hanya untuk Bendahara Utama.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $pembayarans = Pembayaran::with('siswa')
                       ->orderBy('tanggal_bayar', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(50); // Menampilkan 50 data per halaman

        return view('pembayaran.index', compact('pembayarans'));
    }

    /**
     * Tampilkan form untuk mencatat pembayaran baru.
     */
    public function create()
    {
        // Ambil semua data siswa untuk ditampilkan di dropdown
        $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();
        return view('pembayaran.create', compact('siswas'));
    }

    /**
     * Simpan data pembayaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jumlah' => ['required', 'integer', 'min:1000'],
        ]);

        $tanggalBayar = Carbon::parse($validated['tanggal_bayar']);
        
        Pembayaran::create([
            'siswa_id' => $validated['siswa_id'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah' => $validated['jumlah'],
            'minggu_ke' => $tanggalBayar->weekOfYear,
            'tahun' => $tanggalBayar->year,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pembayaran sebesar Rp ' . number_format($validated['jumlah'], 0, ',', '.') . ' berhasil dicatat.');
    }
    
    // =========================================================================
    // CRUD TAMBAHAN: EDIT & DELETE
    // =========================================================================

    /**
     * Tampilkan form untuk mengedit pembayaran.
     */
    public function edit(Pembayaran $pembayaran) // Route Model Binding
    {
        $siswas = Siswa::orderBy('nama_lengkap', 'asc')->get();
        return view('pembayaran.edit', compact('pembayaran', 'siswas'));
    }

    /**
     * Simpan perubahan pada data pembayaran.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'tanggal_bayar' => ['required', 'date'],
            'jumlah' => ['required', 'integer', 'min:1000'],
        ]);
        
        $tanggalBayar = Carbon::parse($validated['tanggal_bayar']);

        $pembayaran->update([
            'siswa_id' => $validated['siswa_id'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'jumlah' => $validated['jumlah'],
            'minggu_ke' => $tanggalBayar->weekOfYear,
            'tahun' => $tanggalBayar->year,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil diperbarui!');
    }

    /**
     * Hapus data pembayaran.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        
        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil dihapus!');
    }
}