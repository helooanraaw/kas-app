<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('siswa.store') }}">
                        @csrf 
                        
                        <div class="mb-4">
                            <label for="no_absen" class="block text-sm font-medium text-gray-700">Nomor Absen</label>
                            <input type="number" name="no_absen" id="no_absen" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('no_absen') }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nama_lengkap') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="nis" class="block text-sm font-medium text-gray-700">NIS (Nomor Induk Siswa)</label>
                            <input type="text" name="nis" id="nis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('nis') }}" required>
                        </div>

                        <div>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Simpan Siswa
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>