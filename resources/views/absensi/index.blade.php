<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="mb-6">

            <h1 class="text-3xl font-bold">
                Data Absensi
            </h1>

            <p class="text-gray-500">
                Riwayat absensi karyawan
            </p>

        </div>

        <!-- Alert -->
        @if(session('success'))

            <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-4">

                {{ session('success') }}

            </div>

        @endif

        @if(session('error'))

            <div class="bg-red-100 text-red-700 p-4 rounded-xl mb-4">

                {{ session('error') }}

            </div>

        @endif

        <!-- Table -->
        <div class="bg-white shadow rounded-2xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-100">

                        <tr>

                            @if(Auth::user()->role == 'admin')

                            <th class="p-4 text-left">
                                Nama
                            </th>

                            @endif

                            <th class="p-4 text-left">
                                Tanggal
                            </th>

                            <th class="p-4 text-left">
                                Jam Masuk
                            </th>

                            <th class="p-4 text-left">
                                Jam Pulang
                            </th>

                            <th class="p-4 text-left">
                                Status
                            </th>

                            <th class="p-4 text-left">
                                Lokasi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $d)

                        <tr class="border-t hover:bg-gray-50">

                            @if(Auth::user()->role == 'admin')

                            <td class="p-4">

                                {{ $d->user->name ?? '-' }}

                            </td>

                            @endif

                            <td class="p-4">

                                {{ $d->tanggal }}

                            </td>

                            <td class="p-4">

                                {{ $d->jam_masuk ?? '-' }}

                            </td>

                            <td class="p-4">

                                {{ $d->jam_pulang ?? '-' }}

                            </td>

                            <td class="p-4">

                                {{ $d->status }}

                            </td>

                            <td class="p-4">

                                @if($d->latitude && $d->longitude)
                                <a href="https://www.google.com/maps?q={{ $d->latitude }},{{ $d->longitude }}" target="_blank" class="text-blue-600 hover:underline">
                                    📍 Lihat di Maps
                                </a>
                                @else
                                <span class="text-gray-500">-</span>
                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" class="p-6 text-center text-gray-500">

                                Belum ada data absensi

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>