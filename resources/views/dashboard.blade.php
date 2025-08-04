<x-app-layout>
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Dashboard</h2>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Pendaftar -->
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
            <div class="bg-indigo-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.962 0zM16.5 9.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Pendaftar</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
            <div class="bg-yellow-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Menunggu Verifikasi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['menunggu_verifikasi'] }}</p>
            </div>
        </div>

        <!-- Lulus Seleksi -->
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
            <div class="bg-green-100 p-3 rounded-full">
                 <svg class="w-6 h-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Lulus Seleksi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['lulus_seleksi'] }}</p>
            </div>
        </div>

        <!-- Belum Lulus -->
        <div class="bg-white p-6 rounded-lg shadow-sm flex items-center space-x-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Belum Lulus</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['belum_lulus'] }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Registrations Table -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pendaftar Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Santri</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Wali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($santriTerbaru as $santri)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('santri.show', $santri->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">{{ $santri->nama_lengkap }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $santri->wali->nama_wali ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = [
                                        'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
                                        'lulus_seleksi' => 'bg-green-100 text-green-800',
                                        'belum_lulus' => 'bg-red-100 text-red-800',
                                    ][$santri->status_pendaftaran] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $santri->status_pendaftaran)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $santri->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Belum ada pendaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>