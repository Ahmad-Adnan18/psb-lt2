<x-app-layout>
    @if (session('status'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Calon Santri</h1>
        <div class="flex items-center space-x-2">
            {{-- PERUBAHAN 1: Menggunakan @if secara langsung --}}
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('santri.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Ekspor ke Excel
                </a>
            @endif
            <a href="{{ route('santri.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                + Tambah Santri
            </a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Daftar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Wali</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($santri->isEmpty())
                    <tr>
                        <td colspan="5" class="p-5 text-center text-gray-500">
                            Belum ada data santri yang diinput.
                        </td>
                    </tr>
                @else
                    @foreach ($santri as $item)
                        <tr>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $item->nomor_pendaftaran }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('santri.show', $item->id) }}" class="hover:underline">
                                    <div class="text-sm text-indigo-600 font-medium">{{ $item->nama_lengkap }}</div>
                                </a>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $item->wali->nama_wali ?? '-' }}</p>
                                <p class="text-xs text-gray-600 whitespace-no-wrap">{{ $item->wali->nomor_whatsapp ?? '' }}</p>
                            </td>
                            <td class="px-5 py-5 text-sm bg-white border-b border-gray-200">
                                @php
                                    $statusClass = '';
                                    if ($item->status_pendaftaran == 'menunggu_verifikasi') {
                                        $statusClass = 'bg-yellow-200 text-yellow-800';
                                    } elseif ($item->status_pendaftaran == 'lulus_seleksi') {
                                        $statusClass = 'bg-green-200 text-green-800';
                                    } elseif ($item->status_pendaftaran == 'belum_lulus') {
                                        $statusClass = 'bg-red-200 text-red-800';
                                    }
                                @endphp
                                <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $statusClass }} rounded-full">
                                    <span class="relative">{{ str_replace('_', ' ', Str::title($item->status_pendaftaran)) }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                <a href="{{ route('santri.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                {{-- PERUBAHAN 2: Menggunakan @if secara langsung --}}
                                @if(Auth::user() && Auth::user()->role === 'admin')
                                <form action="{{ route('santri.destroy', $item->id) }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="px-5 py-5 bg-white border-t">
            {{ $santri->links() }}
        </div>
    </div>
</x-app-layout>
