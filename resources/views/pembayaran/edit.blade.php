<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pembayaran Kas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('pembayaran.update', $pembayaran->id) }}">
                        @csrf 
                        @method('PATCH') <div class="mb-4">
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700">Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                @foreach ($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id', $pembayaran->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} ({{ $siswa->no_absen }})
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('jumlah', $pembayaran->jumlah) }}" required min="1000">
                            @error('jumlah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('tanggal_bayar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
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