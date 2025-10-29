<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Pengeluaran Kas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('pengeluaran.store') }}">
                        @csrf 

                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Pengeluaran</label>
                            <input type="text" name="keterangan" id="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Beli spidol, fotokopi, dll." value="{{ old('keterangan') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="10000" value="{{ old('jumlah') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Simpan Pengeluaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>