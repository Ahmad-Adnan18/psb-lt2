<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8">
        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Detail Santri & Wali --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Detail Santri Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                {{-- PERBAIKAN: Menggunakan $calonSantri --}}
                                <h3 class="text-xl font-semibold text-gray-800">{{ $calonSantri->nama_lengkap }}</h3>
                                <p class="text-sm text-gray-500">{{ $calonSantri->nomor_pendaftaran }}</p>
                            </div>
                            @php
                                $statusClass = [
                                    'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
                                    'lulus_seleksi' => 'bg-green-100 text-green-800',
                                    'belum_lulus' => 'bg-red-100 text-red-800',
                                ][$calonSantri->status_pendaftaran] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold leading-tight {{ $statusClass }} rounded-full">
                                {{ ucwords(str_replace('_', ' ', $calonSantri->status_pendaftaran)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6 text-sm">
                            <div>
                                <dt class="font-medium text-gray-500">NISN</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->nisn ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Tempat, Tgl Lahir</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->tempat_lahir }}, {{ \Carbon\Carbon::parse($calonSantri->tanggal_lahir)->isoFormat('D MMMM YYYY') }}</dd>
                            </div>
                             <div>
                                <dt class="font-medium text-gray-500">Jenis Kelamin</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->jenis_kelamin }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Asal Sekolah</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->asal_sekolah }}</dd>
                            </div>
                             <div class="sm:col-span-2">
                                <dt class="font-medium text-gray-500">Alamat</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->alamat }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Detail Wali Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800">Data Wali</h3>
                    </div>
                    <div class="p-6">
                         <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6 text-sm">
                            <div>
                                <dt class="font-medium text-gray-500">Nama Wali</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->wali->nama_wali ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Pekerjaan</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->wali->pekerjaan ?? '-' }}</dd>
                            </div>
                             <div>
                                <dt class="font-medium text-gray-500">Nomor WhatsApp</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->wali->nomor_whatsapp ?? '-' }}</dd>
                            </div>
                             <div class="sm:col-span-2">
                                <dt class="font-medium text-gray-500">Alamat Wali</dt>
                                <dd class="mt-1 text-gray-900">{{ $calonSantri->wali->alamat_wali ?? 'Sama dengan alamat santri' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Dokumen & Aksi --}}
            <div class="space-y-6">
                {{-- Panel Verifikasi Pendaftaran --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Verifikasi Pendaftaran</h3>
                    </div>
                    <form action="{{ route('santri.updateStatus', $calonSantri->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="p-6">
                            <label for="status_pendaftaran" class="block text-sm font-medium text-gray-700">Ubah Status</label>
                            <select id="status_pendaftaran" name="status_pendaftaran" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="menunggu_verifikasi" {{ $calonSantri->status_pendaftaran == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="lulus_seleksi" {{ $calonSantri->status_pendaftaran == 'lulus_seleksi' ? 'selected' : '' }}>Lulus Seleksi</option>
                                <option value="belum_lulus" {{ $calonSantri->status_pendaftaran == 'belum_lulus' ? 'selected' : '' }}>Belum Lulus</option>
                            </select>
                        </div>
                        <div class="px-6 pb-6 bg-gray-50 text-right">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Status
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Panel Input Hasil Tes --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Input Hasil Tes</h3>
                    </div>
                    <form action="{{ route('santri.simpanHasilTes', $calonSantri->id) }}" method="POST">
                        @csrf
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $mapel = [
                                    'nilai_alquran' => 'Al-Qur\'an', 'nilai_arab' => 'B. Arab',
                                    'nilai_inggris' => 'B. Inggris', 'nilai_matematika' => 'Matematika',
                                    'nilai_interview' => 'Interview',
                                ];
                                $nilaiOpsi = ['A', 'B', 'C', 'D'];
                            @endphp

                            @foreach($mapel as $key => $label)
                            <div>
                                <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                                <select name="{{ $key }}" id="{{ $key }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">-- Pilih Nilai --</option>
                                    @foreach($nilaiOpsi as $nilai)
                                    <option value="{{ $nilai }}" @if(old($key, $calonSantri->hasilTes->{$key} ?? '') == $nilai) selected @endif>{{ $nilai }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endforeach

                            <div class="md:col-span-2">
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan Penguji (Wajib Diisi)</label>
                                <textarea name="catatan" id="catatan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('catatan', $calonSantri->hasilTes->catatan ?? '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="nama_penguji" class="form-label">Nama Penguji</label>
                                <input type="text" name="nama_penguji" id="nama_penguji"
                                    class="form-control"
                                    value="{{ old('nama_penguji', $calonSantri->hasilTes->nama_penguji ?? '') }}"
                                    required>
                            </div>

                        </div>
                        <div class="px-6 pb-6 bg-gray-50 text-right flex gap-2 justify-end">
                            <a href="{{ route('santri.downloadPDF', $calonSantri->id) }}"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Unduh Surat Hasil
                            </a>

                            <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Simpan Nilai
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                {{-- Dokumen Card --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Dokumen Terlampir</h3>
                    </div>
                    <div class="p-6">
                        @forelse ($calonSantri->dokumen as $doc)
                            <div class="flex items-center justify-between py-2 border-b last:border-b-0">
                                <span class="text-sm text-gray-600">{{ $doc->jenis_dokumen }}</span>
                                <a href="{{ route('dokumen.download', $doc->id) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                    Lihat
                                </a>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Tidak ada dokumen yang diunggah.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
