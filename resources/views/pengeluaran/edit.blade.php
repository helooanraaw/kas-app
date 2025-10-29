<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pengeluaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('pengeluaran.update', $pengeluaran->id) }}">
                        @csrf 
                        @method('PATCH') 

                        <div class="mb-4">
                            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan Pengeluaran</label>
                            <input type="text" name="keterangan" id="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('keterangan', $pengeluaran->keterangan) }}" required>
                            @error('keterangan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('jumlah', $pengeluaran->jumlah) }}" required min="1">
                            @error('jumlah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pengeluaran</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $pengeluaran->tanggal) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('tanggal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>