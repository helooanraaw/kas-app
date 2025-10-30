<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
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
     * Menampilkan laporan kas bulanan.
     */
    public function index(Request $request)
    {
        // 1. Tentukan Periode Laporan (Default: Bulan dan Tahun Saat Ini)
        $selectedMonth = $request->input('bulan', Carbon::now()->month);
        $selectedYear = $request->input('tahun', Carbon::now()->year);

        // 2. Query Data Pemasukan (Pembayaran)
        $pemasukan = Pembayaran::whereYear('tanggal_bayar', $selectedYear)
                                ->whereMonth('tanggal_bayar', $selectedMonth)
                                ->sum('jumlah');

        // 3. Query Data Pengeluaran
        $pengeluaran = Pengeluaran::whereYear('tanggal', $selectedYear)
                                 ->whereMonth('tanggal', $selectedMonth)
                                 ->sum('jumlah');

        // 4. Hitung Saldo Periode
        $saldoPeriode = $pemasukan - $pengeluaran;

        // 5. Kirim data ke View
        return view('laporan.index', [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldoPeriode' => $saldoPeriode,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'bulanList' => $this->getAvailableMonths(), // Method tambahan untuk list filter
            'tahunList' => $this->getAvailableYears(), // Method tambahan untuk list filter
        ]);
    }

    // Helper untuk membuat list bulan (1 sampai 12)
    private function getAvailableMonths()
    {
        return collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => Carbon::create()->month($month)->translatedFormat('F')];
        });
    }

    // Helper untuk mendapatkan list tahun dari data transaksi
    private function getAvailableYears()
        {
            // Menggunakan YEAR(...) yang kompatibel dengan MySQL
            $pembayaranYears = Pembayaran::selectRaw('YEAR(tanggal_bayar) as year');
            
            $pengeluaranYears = Pengeluaran::selectRaw('YEAR(tanggal) as year')
                                        ->union($pembayaranYears)
                                        ->distinct()
                                        ->pluck('year');

            // Tambahkan tahun saat ini dan pastikan hasilnya unik dan terurut
            $pengeluaranYears->prepend(Carbon::now()->year);
            
            return $pengeluaranYears->unique()->sortDesc();
        }

    public function export(Request $request)
    {
        $month = $request->input('bulan', Carbon::now()->month);
        $year = $request->input('tahun', Carbon::now()->year);

        // 1. Ambil data Pemasukan dan Pengeluaran untuk bulan/tahun terpilih
        $pemasukanData = Pembayaran::with('siswa')
                                   ->whereYear('tanggal_bayar', $year)
                                   ->whereMonth('tanggal_bayar', $month)
                                   ->get();
        
        $pengeluaranData = Pengeluaran::with('user')
                                   ->whereYear('tanggal', $year)
                                   ->whereMonth('tanggal', $month)
                                   ->get();

        $filename = "Laporan_Kas_{$month}_{$year}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 2. Format Data CSV
        $callback = function() use ($pemasukanData, $pengeluaranData, $year, $month)
        {
            $file = fopen('php://output', 'w');
            
            // Header Utama
            fputcsv($file, ["LAPORAN KAS BULAN {$month} TAHUN {$year}"]);
            fputcsv($file, ['']);
            
            // Pemasukan Header
            fputcsv($file, ["PEMASUKAN KAS (DEBIT)"]);
            fputcsv($file, ['ID', 'TANGGAL', 'NAMA SISWA', 'NO. ABSEN', 'JUMLAH (RP)']);

            // Pemasukan Data
            foreach ($pemasukanData as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->tanggal_bayar,
                    $p->siswa->nama_lengkap ?? 'Siswa Dihapus',
                    $p->siswa->no_absen ?? '-',
                    $p->jumlah
                ]);
            }

            fputcsv($file, ['']);

            // Pengeluaran Header
            fputcsv($file, ["PENGELUARAN KAS (KREDIT)"]);
            fputcsv($file, ['ID', 'TANGGAL', 'KETERANGAN', 'DICATAT OLEH', 'JUMLAH (RP)']);
            
            // Pengeluaran Data
            foreach ($pengeluaranData as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->tanggal,
                    $p->keterangan,
                    $p->user->name ?? 'Admin',
                    $p->jumlah
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}