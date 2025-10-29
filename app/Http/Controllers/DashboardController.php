<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa; 
use App\Models\Pembayaran; 
use App\Models\Pengeluaran; 
use Carbon\Carbon;
use Carbon\CarbonPeriod; // <-- IMPORT BARU
use Illuminate\Support\Facades\DB; // <-- IMPORT BARU
use Illuminate\Routing\Controller; // <-- IMPORT YANG BARU ANDA TAMBAHKAN

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Bendahara.
     */
    public function index()
    {
        // 1. Definisi Konstanta Uang Kas Mingguan
        $kasPerMinggu = 5000;
        
        // 2. Hitungan Saldo Kas
        $totalPemasukan = Pembayaran::sum('jumlah');
        $totalPengeluaran = Pengeluaran::sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // 3. Hitungan Tunggakan Kumulatif
        $jumlahSiswa = Siswa::count();
        $siswa = Siswa::all();
        $siswaMenunggak = collect();
        $tanggalMulai = Carbon::create(Carbon::now()->year, 1, 1); 
        $totalMingguBerlalu = $tanggalMulai->diffInWeeks(Carbon::now()) + 1;
        $totalKewajiban = $totalMingguBerlalu * $kasPerMinggu;

        foreach ($siswa as $s) {
            $totalPaid = Pembayaran::where('siswa_id', $s->id)->sum('jumlah');
            $tunggakan = $totalKewajiban - $totalPaid;
            
            if ($tunggakan > 0) {
                $s->tunggakan = $tunggakan;
                $s->minggu_tunggakan = ceil($tunggakan / $kasPerMinggu);
                $siswaMenunggak->push($s);
            }
        }
        
        // 4. Ambil riwayat transaksi terakhir
        $listPemasukan = Pembayaran::with('siswa') 
                                   ->latest('tanggal_bayar') 
                                   ->take(5) 
                                   ->get();
        $listPengeluaran = Pengeluaran::with('user') 
                                     ->latest('tanggal') 
                                     ->take(5) 
                                     ->get();

        // 5. LOGIKA BARU UNTUK GRAFIK (30 HARI TERAKHIR)
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

        // 6. Kirim semua data ke View
        return view('dashboard', [
            // Data Lama
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'jumlahSiswa' => $jumlahSiswa,
            'siswaBelumBayar' => $siswaMenunggak, 
            'listPemasukan' => $listPemasukan,
            'listPengeluaran' => $listPengeluaran,
            
            // Data Baru untuk Grafik
            'chartLabels' => $labels,
            'chartPemasukan' => $pemasukanChartData,
            'chartPengeluaran' => $pengeluaranChartData,
        ]);
    }
}