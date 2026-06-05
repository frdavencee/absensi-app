<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Jadwal Karyawan</h1>
                <p class="text-gray-500 mt-1">@if(Auth::user()->role == 'admin') Data jadwal seluruh karyawan @else Jadwal Anda @endif</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(Auth::user()->role == 'admin' && !isset($selectedUser))
        <!-- USER LIST VIEW -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Karyawan</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Jadwal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kerja</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Libur</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $u)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('jadwal.index', ['user_id' => $u->id]) }}" class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800 group-hover:text-blue-600 transition">{{ $u->name }}</span>
                                </a>
                            </td>

                            <td class="px-6 py-4 text-gray-600">{{ $u->jadwals_count }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->jadwals()->where('status', 'Kerja')->count() }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $u->jadwals()->where('status', 'Libur')->count() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-6 8h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada data jadwal</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @else
        <!-- DETAIL VIEW -->

        @if($selectedUser)
        <div class="ml-8 inline-flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 mt-2">
            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
            </div>
            <span class="font-semibold text-gray-800">{{ $selectedUser->name }}</span>
            <a href="{{ route('jadwal.index', ['user_id' => $selectedUser->id]) }}" class="text-blue-600 hover:text-blue-800 ml-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </a>
        </div>
        @endif

        @if(Auth::user()->role == 'admin' && isset($selectedUser))
        <div class="mb-4">
            <a href="{{ route('jadwal.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke daftar karyawan
            </a>
        </div>
        @endif

        @if(Auth::user()->role == 'admin')
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <a href="{{ route('jadwal.create', request('user_id') ? ['user_id' => request('user_id')] : []) }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-cap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Jadwal
            </a>

            <form action="{{ route('jadwal.import') }}" method="POST" enctype="multipart/form-data" class="inline-flex items-center gap-2">
                @csrf
                @if($selectedUser)
                <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                @endif
                <input type="file" name="file" id="excel-file" class="hidden" accept=".xlsx,.xls,.csv" onchange="this.form.submit()">
                <label for="excel-file" class="inline-flex items-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-5 py-2.5 rounded-xl font-medium transition shadow-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Import Excel
                </label>
            </form>
        </div>
        @endif
        <p class="text-xs text-gray-400 mt-2">Format: Tanggal, Shift (Pagi/Siang/Malam), Status (Kerja/Libur) — untuk 1 bulan penuh</p>
        <form method="GET" action="{{ route('jadwal.index', request()->only(['user_id'])) }}" class="mt-4 flex flex-wrap items-center gap-3">
            <label for="periode-filter" class="text-xs font-medium text-gray-600">Periode:</label>
            <select name="periode" id="periode-filter" class="border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="
                const value = this.value;
                if (value) {
                    const [tahun, bulan] = value.split('-');
                    document.getElementById('bulan-input').value = bulan;
                    document.getElementById('tahun-input').value = tahun;
                } else {
                    document.getElementById('bulan-input').value = '';
                    document.getElementById('tahun-input').value = '';
                }
                this.form.submit();
            ">
                <option value="">Semua Periode</option>
                @for($y = (date('Y') - 5); $y <= (date('Y') + 5); $y++)
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $y }}-{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" 
                            {{ request('tahun') == $y && request('bulan') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m,1)) }} {{ $y }}
                        </option>
                    @endfor
                @endfor
            </select>
            <input type="hidden" name="bulan" id="bulan-input" value="{{ request('bulan') }}">
            <input type="hidden" name="tahun" id="tahun-input" value="{{ request('tahun') }}">
        </form>
    </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 backdrop-blur">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Shift</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            @if(Auth::user()->role == 'admin')
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @php $currentMonth = null @endphp

                        @forelse($jadwals as $jadwal)
                        @php
                            $recordMonth = \Carbon\Carbon::parse($jadwal->tanggal)->format('F Y');
                        @endphp

                        @if($recordMonth != $currentMonth)
                        @php $currentMonth = $recordMonth @endphp
                        <tr class="bg-gray-100">
                            <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" class="px-6 py-2 text-xs font-bold text-gray-600 uppercase tracking-wider">
                                {{ $recordMonth }}
                            </td>
                        </tr>
                        @endif

                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($jadwal->shift == 'Pagi') bg-blue-100 text-blue-700
                                    @elseif($jadwal->shift == 'Siang') bg-orange-100 text-orange-700
                                    @else bg-purple-100 text-purple-700 @endif">
                                    {{ $jadwal->shift }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-mono text-gray-700">{{ $jadwal->jam_masuk }}</td>
                            <td class="px-6 py-4 font-mono text-gray-700">{{ $jadwal->jam_pulang }}</td>

                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($jadwal->status == 'Kerja') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $jadwal->status }}
                                </span>
                            </td>

                            @if(Auth::user()->role == 'admin')
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('jadwal.edit', $jadwal->id) }}" class="p-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414L12 13.586M16.5 8.5L12 13.5L8 8.5L12 4.5L16.5 8.5z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('jadwal.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Yakin hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6V7M9 7h6"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif

                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role == 'admin' ? 6 : 5 }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-6 8h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Tidak ada data jadwal</p>
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
