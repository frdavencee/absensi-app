<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pengajuan Izin / Sakit</h1>
                <p class="text-gray-500 mt-1">
                    @if(Auth::user()->role == 'admin')
                        Data pengajuan seluruh karyawan
                    @else
                        Data pengajuan Anda
                    @endif
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(Auth::user()->role == 'admin' && !isset($selectedUser))
        <!-- ADMIN: USER LIST VIEW -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Izin/Sakit</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pending</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approved</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('pengajuan', ['user_id' => $u->id]) }}" class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $u->name }}</span>
                                </a>
                            </td>

                            <td class="px-6 py-4 text-gray-600">{{ $u->absensis_count }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->absensis()->where('status', 'Izin')->where('approval', 'Pending')->count() + $u->absensis()->where('status', 'Sakit')->where('approval', 'Pending')->count() }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->absensis()->whereIn('status', ['Izin', 'Sakit'])->where('approval', 'Approved')->count() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada pengajuan izin/sakit</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @elseif(Auth::user()->role == 'admin' && isset($selectedUser))
        <!-- ADMIN: DETAIL VIEW WITH APPROVE -->
        <div class="mb-4">
            <a href="{{ route('pengajuan') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke daftar karyawan
            </a>

            <div class="ml-8 inline-flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 mt-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                    {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                </div>
                <span class="font-semibold text-gray-800">{{ $selectedUser->name }}</span>
            </div>

            <!-- Filter -->
            <form method="GET" action="{{ route('pengajuan') }}" class="mt-4 flex flex-wrap items-center gap-3">
                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">

                <select name="bulan" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>

                <select name="tahun" class="rounded-lg border-gray-300 border px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="">Semua Tahun</option>
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Filter</button>
                <a href="{{ route('pengajuan', ['user_id' => $selectedUser->id]) }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Reset</a>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto Bukti</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approval</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @php $currentMonth = null @endphp

                        @forelse($data as $d)
                        @php
                            $recordMonth = \Carbon\Carbon::parse($d->tanggal)->format('F Y');
                        @endphp

                        @if($recordMonth != $currentMonth)
                        @php $currentMonth = $recordMonth @endphp
                        <tr class="bg-gray-100">
                            <td colspan="6" class="px-6 py-2 text-xs font-bold text-gray-600 uppercase tracking-wider">
                                {{ $recordMonth }}
                            </td>
                        </tr>
                        @endif

                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($d->status == 'Izin') bg-yellow-100 text-yellow-700
                                    @elseif($d->status == 'Sakit') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $d->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">{{ $d->keterangan ?? '-' }}</td>

                            <td class="px-6 py-4">
                                @if($d->foto_masuk)
                                    <a href="{{ asset('storage/foto/' . $d->foto_masuk) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-12 0h16v12a2 2 0 002 2H8a2 2 0 00-2-2V7a2 2 0 002-2h8a2 2 0 002 2v12a2 2 0 00-2 2H8a2 2 0 00-2-2V7z"></path>
                                        </svg>
                                        Lihat Foto
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($d->approval == 'Approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                                @elseif($d->approval == 'Pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">{{ $d->approval }}</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if(in_array($d->status, ['Izin', 'Sakit']))
                                        <form action="{{ route('approve', $d->id) }}" method="POST" class="inline">
                                            @csrf
                                            @if($d->approval == 'Pending')
                                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 transition">
                                                    Approve
                                                </button>
                                            @else
                                                <span class="px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-500">Sudah diproses</span>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif

                                    <form action="{{ route('pengajuan.destroy', $d->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus pengajuan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada pengajuan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        @else
        <!-- USER: OWN PENGajuan VIEW (READ ONLY) -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto Bukti</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Approval</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @php $currentMonth = null @endphp

                        @forelse($data as $d)
                        @php
                            $recordMonth = \Carbon\Carbon::parse($d->tanggal)->format('F Y');
                        @endphp

                        @if($recordMonth != $currentMonth)
                        @php $currentMonth = $recordMonth @endphp
                        <tr class="bg-gray-100">
                            <td colspan="5" class="px-6 py-2 text-xs font-bold text-gray-600 uppercase tracking-wider">
                                {{ $recordMonth }}
                            </td>
                        </tr>
                        @endif

                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($d->status == 'Izin') bg-yellow-100 text-yellow-700
                                    @elseif($d->status == 'Sakit') bg-purple-100 text-purple-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $d->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">{{ $d->keterangan ?? '-' }}</td>

                            <td class="px-6 py-4">
                                @if($d->foto_masuk)
                                    <a href="{{ asset('storage/foto/' . $d->foto_masuk) }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-12 0h16v12a2 2 0 002 2H8a2 2 0 00-2-2V7a2 2 0 002-2h8a2 2 0 002 2v12a2 2 0 00-2 2H8a2 2 0 00-2-2V7z"></path>
                                        </svg>
                                        Lihat Foto
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($d->approval == 'Approved')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Approved</span>
                                @elseif($d->approval == 'Pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">{{ $d->approval }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada pengajuan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

</x-app-layout>
