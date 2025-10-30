<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // <-- PASTIKAN IMPORT INI ADA
use Illuminate\Support\Facades\DB; // <-- PASTIKAN IMPORT INI ADA

class PublicReportController extends Controller
{
    /**
     * Menampilkan halaman laporan publik (homepage).
     */
    public function index()
    {
        // 1. Hitung Saldo
        $totalPemasukan = Pembayaran::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;
        
        // 2. Ambil Riwayat
        $listPemasukan = Pembayaran::with('siswa')
                                   ->latest('tanggal_bayar')
                                   ->take(10)
                                   ->get();
        $listPengeluaran = Pengeluaran::latest('tanggal')
                                     ->take(10)
                                     ->get();

        // ==========================================================
        // 3. LOGIKA GRAFIK (YANG HILANG)
        // ==========================================================
        $period = CarbonPeriod::create(now()->subDays(29), now());
        $labels = collect($period)->map(fn ($date) => $date->format('d M'));
        
        $pemasukanData = Pembayaran::where('tanggal_bayar', '>=', now()->subDays(29))
            ->select(DB::raw('DATE(tanggal_bayar) as date'), DB::raw('SUM(jumlah) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');
            
        $pengeluaranData = Pengeluaran::where('tanggal', '>=', now()->subDays(29))
            ->select(DB::raw('DATE(tanggal) as date'), DB::raw('SUM(jumlah) as total'))
            ->groupBy('date')
            ->pluck('total', 'date');

        $pemasukanChartData = collect($period)->map(
            fn ($date) => $pemasukanData->get($date->format('Y-m-d'), 0)
        );
        $pengeluaranChartData = collect($period)->map(
            fn ($date) => $pengeluaranData->get($date->format('Y-m-d'), 0)
        );

        // 4. Kirim semua data ke View
        return view('welcome', [
            // Data Lama
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'listPemasukan' => $listPemasukan,
            'listPengeluaran' => $listPengeluaran,
            
            // Data Baru untuk Grafik (YANG HILANG)
            'chartLabels' => $labels,
            'chartPemasukan' => $pemasukanChartData,
            'chartPengeluaran' => $pengeluaranChartData,
        ]);
    }
}