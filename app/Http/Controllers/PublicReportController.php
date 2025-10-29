<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Carbon\Carbon; // Pastikan ini ada

class PublicReportController extends Controller
{
    public function index()
    {
        // ... (Hitungan Saldo sama seperti sebelumnya)

        $totalPemasukan = Pembayaran::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        
        // 4. Ambil 10 riwayat pemasukan terakhir
        $listPemasukan = Pembayaran::with('siswa')
                                   ->latest('tanggal_bayar')
                                   ->take(10)
                                   ->get();

        // 5. Ambil 10 riwayat pengeluaran terakhir
        $listPengeluaran = Pengeluaran::latest('tanggal')
                                     ->take(10)
                                     ->get();

        // 6. Kirim semua data ke View
        return view('welcome', [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'listPemasukan' => $listPemasukan,
            'listPengeluaran' => $listPengeluaran,
        ]);
    }
}