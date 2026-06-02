<div class="h-screen bg-white shadow-2xl w-72 fixed left-0 top-0 p-6 overflow-y-auto border-r border-gray-100">

    <!-- Logo -->
    <div class="mb-10">

        <div class="flex items-center gap-3">

            <div
                class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white text-2xl shadow-lg">

                📊

            </div>

            <div>

                <h1 class="text-3xl font-extrabold text-gray-800 tracking-wide">
                    ABSENSI
                </h1>

                <p class="text-gray-500 text-sm mt-1">
                    Sistem Absensi Karyawan
                </p>

            </div>

        </div>

    </div>

    <!-- User Card -->
    <div
        class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 text-white mb-8 shadow-xl">

        <div class="flex items-center gap-4">

            <div
                class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-lg flex items-center justify-center text-2xl font-bold">

                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

            </div>

            <div>

                <h2 class="font-bold text-xl">
                    {{ Auth::user()->name }}
                </h2>

                <p class="text-blue-100 mt-1">
                    {{ Auth::user()->role }}
                </p>

            </div>

        </div>

    </div>

    <!-- Menu -->
    <div class="space-y-3">

        <!-- Dashboard -->
        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300 group">

            <span class="text-2xl">
                📊
            </span>

            Dashboard

        </a>

        <!-- Data Absensi -->
        <a
            href="{{ route('data.absensi') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                🕒
            </span>

            Data Absensi

        </a>

        <!-- Pengajuan -->
        <a
            href="{{ route('pengajuan') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                📝
            </span>

            Pengajuan

        </a>

        <!-- Notifikasi -->
        <a
            href="{{ route('notifications.index') }}"
            class="relative flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                🔔
            </span>

            Notifikasi

            @php
                $unreadCount = Auth::user()->unreadNotifications()->count();
            @endphp
            @if($unreadCount > 0)
            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">
                {{ $unreadCount }}
            </span>
            @endif

        </a>

        <!-- Jadwal -->
        <a
            href="{{ route('jadwal.index') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                📅
            </span>

            Jadwal Karyawan

        </a>

        <!-- Karyawan -->
        @if(Auth::user()->role === 'admin')
        <a
            href="{{ route('karyawan.index') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                👨‍💼
            </span>

            Data Karyawan

        </a>
        @endif

        <!-- Profile -->
        <a
            href="{{ route('profile.edit') }}"
            class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-blue-50 hover:text-blue-600 text-gray-700 font-semibold transition-all duration-300">

            <span class="text-2xl">
                ⚙️
            </span>

            Profile

        </a>

    </div>

    <!-- Statistik -->
    <div
        class="mt-10 bg-gray-50 rounded-3xl p-5 border border-gray-100">

        <h3 class="font-bold text-gray-700 mb-4">
            Statistik Cepat
        </h3>

        <div class="space-y-4">

            <div
                class="flex justify-between items-center">

                <span class="text-gray-500">
                    Status
                </span>

                <span
                    class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">

                    Online

                </span>

            </div>

            <div
                class="flex justify-between items-center">

                <span class="text-gray-500">
                    Hari Ini
                </span>

                <span class="font-bold text-gray-700">
                    {{ now()->translatedFormat('d M Y') }}
                </span>

            </div>

        </div>

    </div>

    <!-- Logout -->
    <div class="mt-10">

        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:scale-105 hover:shadow-xl transition-all duration-300 text-white py-4 rounded-2xl font-bold">

                🚪 Logout

            </button>

        </form>

    </div>

</div>