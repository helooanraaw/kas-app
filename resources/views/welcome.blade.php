<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laporan Kas Kelas</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        
        <div class="container mx-auto p-4 sm:p-6 lg:p-8">

            <header class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Laporan Uang Kas Kelas
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Uang Kas Kelas From XI RPL 1 SMK Negeri 1 Denpasar
                    </p>
                </div>
                
            </header>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500">Total Saldo Kas</h3>
                    <p class="mt-1 text-3xl font-semibold text-blue-600">
                        Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-green-500">
                    <h3 class="text-sm font-medium text-gray-500">Total Pemasukan</h3>
                    <p class="mt-1 text-3xl font-semibold text-green-600">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-gray-500">Total Pengeluaran</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"> <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium mb-4">10 Pemasukan Terakhir</h3>
                    <div class="overflow-x-auto h-96 overflow-y-auto">
                        <table class="min-w-full w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($listPemasukan as $pemasukan)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $pemasukan->siswa->nama_lengkap ?? 'Siswa Dihapus' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ date('d M Y', strtotime($pemasukan->tanggal_bayar)) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 text-right">+ Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Belum ada pemasukan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-medium mb-4">10 Pengeluaran Terakhir</h3>
                    <div class="overflow-x-auto h-96 overflow-y-auto">
                        <table class="min-w-full w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($listPengeluaran as $pengeluaran)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $pengeluaran->keterangan }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ date('d M Y', strtotime($pengeluaran->tanggal)) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 text-right">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Belum ada pengeluaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div> <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Progres Kas (30 Hari Terakhir)</h3>
                <div>
                    <canvas id="kasProgressChartPublic"></canvas> </div>
            </div>

        </div> 
    </body>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($chartLabels);
            const pemasukanData = @json($chartPemasukan);
            const pengeluaranData = @json($chartPengeluaran);

            const data = { labels: labels, datasets: [ { label: 'Pemasukan', backgroundColor: 'rgba(75, 192, 192, 0.2)', borderColor: 'rgb(75, 192, 192)', data: pemasukanData, fill: true, tension: 0.3 }, { label: 'Pengeluaran', backgroundColor: 'rgba(255, 99, 132, 0.2)', borderColor: 'rgb(255, 99, 132)', data: pengeluaranData, fill: true, tension: 0.3 } ] };
            const config = { type: 'line', data: data, options: { responsive: true, scales: { y: { beginAtZero: true } } } };
            const myChart = new Chart( document.getElementById('kasProgressChartPublic'), config );
        });
    </script>
</html>