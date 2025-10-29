<?php

namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\User; // <-- Tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\Controller; 
use Carbon\Carbon;

class PengeluaranController extends Controller
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

    /**
     * Tampilkan daftar riwayat pengeluaran (BARU).
     */
    public function index()
    {
        $pengeluarans = Pengeluaran::with('user')
                       ->orderBy('tanggal', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->paginate(50); 

        return view('pengeluaran.index', compact('pengeluarans'));
    }


    /**
     * Menampilkan form untuk mencatat pengeluaran baru (Create).
     */
    public function create()
    {
        return view('pengeluaran.create');
    }

    /**
     * Menyimpan pengeluaran baru ke database (Store).
     */
    public function store(Request $request)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1', 
            'tanggal' => 'required|date',
        ]);

        Pengeluaran::create([
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'user_id' => Auth::id(), 
        ]);

        return redirect()->route('laporan.index')->with('success', 'Pengeluaran berhasil dicatat!');
    }
    
    // =========================================================================
    // CRUD TAMBAHAN: EDIT & DELETE
    // =========================================================================

    /**
     * Tampilkan form untuk mengedit pengeluaran.
     */
    public function edit(Pengeluaran $pengeluaran) // Route Model Binding
    {
        return view('pengeluaran.edit', compact('pengeluaran'));
    }

    /**
     * Simpan perubahan pada data pengeluaran.
     */
    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1', 
            'tanggal' => 'required|date',
        ]);

        $pengeluaran->update([
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            // user_id TIDAK diubah
        ]);

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    /**
     * Hapus data pengeluaran.
     */
    public function destroy(Pengeluaran $pengeluaran)
    {
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}