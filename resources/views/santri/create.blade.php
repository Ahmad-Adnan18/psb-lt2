<x-app-layout>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Input Data Santri Baru</h1>

    {{-- Nanti di sini kita letakkan form-nya. Untuk sekarang, ini adalah placeholder --}}
    <div class="p-6 bg-white rounded-lg shadow-md">
       <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 2. Tambahkan untuk menampilkan error general -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Terdapat beberapa kesalahan pada input Anda.</span>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- DATA DIRI SANTRI --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Data Diri Calon Santri</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                        <x-text-input id="nama_lengkap" class="block mt-1 w-full" type="text" name="nama_lengkap" :value="old('nama_lengkap')" required autofocus />
                        {{-- <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" /> --}}
                    </div>
                    <div>
                        <x-input-label for="nisn" :value="__('NISN')" />
                        <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn" :value="old('nisn')" />
                    </div>
                    <div>
                        <x-input-label for="tempat_lahir" :value="__('Tempat Lahir')" />
                        <x-text-input id="tempat_lahir" class="block mt-1 w-full" type="text" name="tempat_lahir" :value="old('tempat_lahir')" required />
                    </div>
                    <div>
                        <x-input-label for="tanggal_lahir" :value="__('Tanggal Lahir')" />
                        <x-text-input id="tanggal_lahir" class="block mt-1 w-full" type="date" name="tanggal_lahir" :value="old('tanggal_lahir')" required />
                    </div>
                    <div>
                        <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                        <select name="jenis_kelamin" id="jenis_kelamin" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" @if(old('jenis_kelamin') == 'Laki-laki') selected @endif>Laki-laki</option>
                            <option value="Perempuan" @if(old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                        </select>
                    </div>
                     <div>
                        <x-input-label for="asal_sekolah" :value="__('Asal Sekolah')" />
                        <x-text-input id="asal_sekolah" class="block mt-1 w-full" type="text" name="asal_sekolah" :value="old('asal_sekolah')" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="alamat" :value="__('Alamat Lengkap Santri')" />
                        <textarea id="alamat" name="alamat" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required></textarea>
                    </div>
                </div>
            </div>

            {{-- DATA WALI SANTRI --}}
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Data Wali Santri</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="nama_wali" :value="__('Nama Ayah / Ibu / Wali')" />
                        <x-text-input id="nama_wali" class="block mt-1 w-full" type="text" name="nama_wali" :value="old('nama_wali')" required />
                    </div>
                     <div>
                        <x-input-label for="pekerjaan" :value="__('Pekerjaan Wali')" />
                        <x-text-input id="pekerjaan" class="block mt-1 w-full" type="text" name="pekerjaan" :value="old('pekerjaan')" />
                    </div>
                    <div>
                        <x-input-label for="nomor_whatsapp" :value="__('Nomor WhatsApp Aktif')" />
                        <x-text-input id="nomor_whatsapp" class="block mt-1 w-full" type="text" name="nomor_whatsapp" :value="old('nomor_whatsapp')" placeholder="Contoh: 081234567890" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="alamat_wali" :value="__('Alamat Wali (jika berbeda)')" />
                        <textarea id="alamat_wali" name="alamat_wali" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-700 mt-8 mb-4 border-t pt-4">Upload Dokumen</h2>
            <p class="text-sm text-gray-500 mb-4">Format file yang diizinkan: PDF, JPG, PNG. Ukuran maksimal 2MB. (Opsional)</p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Akta Kelahiran -->
                <div>
                    <label for="dokumen_akta_kelahiran" class="block text-sm font-medium text-gray-700">Akta Kelahiran</label>
                    <input type="file" name="dokumen[akta_kelahiran]" id="dokumen_akta_kelahiran" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>
                
                <!-- Kartu Keluarga -->
                <div>
                    <label for="dokumen_kartu_keluarga" class="block text-sm font-medium text-gray-700">Kartu Keluarga</label>
                    <input type="file" name="dokumen[kartu_keluarga]" id="dokumen_kartu_keluarga" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>

                <!-- Foto Formal -->
                <div>
                    <label for="dokumen_foto_formal" class="block text-sm font-medium text-gray-700">Foto Formal (3x4)</label>
                    <input type="file" name="dokumen[foto_formal]" id="dokumen_foto_formal" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>

                <!-- Raport (Baru) -->
                <div>
                    <label for="dokumen_raport" class="block text-sm font-medium text-gray-700">Raport Terakhir</label>
                    <input type="file" name="dokumen[raport]" id="dokumen_raport" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>

                <!-- KTP Wali (Baru) -->
                <div>
                    <label for="dokumen_ktp_wali" class="block text-sm font-medium text-gray-700">KTP Wali</label>
                    <input type="file" name="dokumen[ktp_wali]" id="dokumen_ktp_wali" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                </div>
            </div>
            
            {{-- Tombol Submit --}}
            <div class="flex justify-end">
                <a href="{{ route('santri.index') }}" class="px-4 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Batal
                </a>
                <x-primary-button>
                    {{ __('Simpan Data') }}
                </x-primary-button>
            </div>

       </form>
    </div>
</x-app-layout>