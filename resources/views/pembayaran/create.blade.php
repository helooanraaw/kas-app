<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catat Pembayaran Kas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembayaran.store') }}">
                        @csrf 

                        <div class="mb-4">
                            <label for="siswa_id" class="block text-sm font-medium text-gray-700">Pilih Siswa</label>
                            <select name="siswa_id" id="siswa_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="">-- Pilih Siswa --</option>
                                @foreach ($siswas as $siswa)
                                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>
                                        {{ $siswa->nama_lengkap }} ({{ $siswa->no_absen }})
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            <input type="number" name="jumlah" id="jumlah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('jumlah') }}" required min="1000">
                            @error('jumlah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" id="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('tanggal_bayar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>