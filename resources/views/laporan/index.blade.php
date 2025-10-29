<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Kas Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form action="{{ route('laporan.index') }}" method="GET" class="flex space-x-4 items-end">
                    
                    <div>
                        <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                        <select name="bulan" id="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach ($bulanList as $key => $value)
                                <option value="{{ $key }}" {{ $selectedMonth == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                        <select name="tahun" id="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach ($tahunList as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('laporan.export', ['bulan' => $selectedMonth, 'tahun' => $selectedYear]) }}"
                       class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        Ekspor CSV
                    </a>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <h3 class="text-xl font-semibold mb-4 border-b pb-2">
                        Rekapitulasi {{ $bulanList[$selectedMonth] }} {{ $selectedYear }}
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        
                        <div class="p-4 bg-green-100 rounded-lg">
                            <p class="text-sm text-green-600 font-medium">Pemasukan</p>
                            <p class="text-2xl font-bold text-green-800 mt-1">
                                Rp {{ number_format($pemasukan, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="p-4 bg-red-100 rounded-lg">
                            <p class="text-sm text-red-600 font-medium">Pengeluaran</p>
                            <p class="text-2xl font-bold text-red-800 mt-1">
                                Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                            </p>
                        </div>
                        
                        <div class="p-4 bg-blue-100 rounded-lg">
                            <p class="text-sm text-blue-600 font-medium">Saldo Bersih</p>
                            <p class="text-2xl font-bold text-blue-800 mt-1">
                                Rp {{ number_format($saldoPeriode, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    *Detail transaksi harian dapat dilihat pada menu Riwayat Pemasukan dan Pengeluaran.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>