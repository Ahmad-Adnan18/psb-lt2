<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Santri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Oops! Terdapat beberapa kesalahan pada input Anda.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('santri.update', $calonSantri->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Data Diri Calon Santri --}}
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Diri Calon Santri</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $calonSantri->nama_lengkap) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="nisn" class="block text-sm font-medium text-gray-700">NISN</label>
                                    <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $calonSantri->nisn) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $calonSantri->tempat_lahir) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $calonSantri->tanggal_lahir) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $calonSantri->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="asal_sekolah" class="block text-sm font-medium text-gray-700">Asal Sekolah</label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah', $calonSantri->asal_sekolah) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap Santri</label>
                                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('alamat', $calonSantri->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Data Wali Santri --}}
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Wali Santri</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama_wali" class="block text-sm font-medium text-gray-700">Nama Wali (Ayah/Ibu)</label>
                                    <input type="text" name="nama_wali" id="nama_wali" value="{{ old('nama_wali', $calonSantri->wali->nama_wali ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div>
                                    <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan</label>
                                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $calonSantri->wali->pekerjaan ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp Aktif</label>
                                    <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" value="{{ old('nomor_whatsapp', $calonSantri->wali->nomor_whatsapp ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="col-span-2">
                                    <label for="alamat_wali" class="block text-sm font-medium text-gray-700">Alamat Wali (isi jika berbeda)</label>
                                    <textarea name="alamat_wali" id="alamat_wali" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('alamat_wali', $calonSantri->wali->alamat_wali ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Upload Dokumen --}}
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Upload/Perbarui Dokumen</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $jenisDokumen = [
                                        'akta_kelahiran' => 'Akta Kelahiran',
                                        'kartu_keluarga' => 'Kartu Keluarga',
                                        'foto_formal' => 'Foto Formal',
                                        'raport' => 'Raport Terakhir',
                                        'ktp_wali' => 'KTP Wali',
                                    ];
                                    // Membuat map dokumen yang ada untuk akses cepat
                                    $dokumenTersedia = $calonSantri->dokumen->keyBy('jenis_dokumen');
                                @endphp

                                @foreach ($jenisDokumen as $key => $label)
                                    <div>
                                        <label for="dokumen_{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                        <input type="file" name="dokumen[{{ $key }}]" id="dokumen_{{ $key }}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                                        
                                        {{-- Tampilkan link ke file lama jika ada --}}
                                        @if($dokumenTersedia->has($key))
                                            <div class="mt-2 text-xs text-gray-500">
                                                File saat ini: 
                                                <a href="{{ Storage::url($dokumenTersedia->get($key)->path_file) }}" target="_blank" class="text-blue-600 hover:underline">
                                                    {{ $dokumenTersedia->get($key)->nama_file }}
                                                </a>
                                            </div>
                                        @else
                                            <p class="mt-2 text-xs text-gray-500">Belum ada file.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        <div class="flex justify-end mt-6">
                            <a href="{{ route('santri.index') }}" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>