<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Bendahara') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-sm font-medium opacity-75">Total Saldo Kas</h3>
                    <p class="mt-1 text-3xl font-semibold">
                        Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-sm font-medium opacity-75">Total Pemasukan</h3>
                    <p class="mt-1 text-3xl font-semibold">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-red-500 text-white p-6 rounded-lg shadow-md">
                    <h3 class="text-sm font-medium opacity-75">Total Pengeluaran</h3>
                    <p class="mt-1 text-3xl font-semibold">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">
                        Siswa Belum Bayar Minggu Ini ({{ $siswaBelumBayar->count() }} orang)
                    </h3>
                    <ul class="divide-y divide-gray-200 h-64 overflow-y-auto">
                        @forelse ($siswaBelumBayar as $siswa)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</p>
                                    <p class="text-sm text-gray-500">{{ $siswa->nis }}</p>
                                </div>
                                <a href="{{ route('pembayaran.create') }}?siswa_id={{ $siswa->id }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Bayar Sekarang
                                </a>
                            </li>
                        @empty
                            <li class="py-3 text-center text-gray-500">
                                Mantap! Semua siswa sudah lunas minggu ini.
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">Statistik Siswa</h3>
                    <div class="flex items-center justify-center h-64">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total Siswa Terdaftar</p>
                            <p class="text-6xl font-bold text-gray-900">{{ $jumlahSiswa }}</p>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">5 Pemasukan Terakhir</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($listPemasukan as $pemasukan)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $pemasukan->siswa->nama_lengkap ?? 'Siswa Dihapus' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($pemasukan->tanggal_bayar)->isoFormat('DD MMM YYYY') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-4 py-3 text-center text-gray-500">Belum ada pemasukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-medium mb-4">5 Pengeluaran Terakhir</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($listPengeluaran as $pengeluaran)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-800">{{ $pengeluaran->keterangan }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 text-right">- Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-4 py-3 text-center text-gray-500">Belum ada pengeluaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-0">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-medium mb-4">Progres Kas (30 Hari Terakhir)</h3>
                    <div>
                        <canvas id="kasProgressChart"></canvas>
                    </div>
                </div>
            </div>


        </div>
    </div>  
</x-app-layout>

<script>
    // TAMBAHKAN PEMBUNGKUS INI:
    // Menunggu halaman (termasuk app.js) selesai loading
    document.addEventListener('DOMContentLoaded', function() {
        
        // Ambil data dari PHP (Controller)
        const labels = @json($chartLabels);
        const pemasukanData = @json($chartPemasukan);
        const pengeluaranData = @json($chartPengeluaran);

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Pemasukan',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Hijau
                    borderColor: 'rgb(75, 192, 192)',
                    data: pemasukanData,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Merah
                    borderColor: 'rgb(255, 99, 132)',
                    data: pengeluaranData,
                    fill: true,
                    tension: 0.3
                }
            ]
        };

        const config = {
            type: 'line', // Jenis grafik (line chart)
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Render grafik
        const myChart = new Chart(
            document.getElementById('kasProgressChart'),
            config
        );

    }); // <-- TUTUP PEMBUNGKUS
</script>