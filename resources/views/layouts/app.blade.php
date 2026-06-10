<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Laravel') }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen">

        <!-- Overlay -->
        <div
            id="overlay"
            onclick="toggleSidebar()"
            class="fixed inset-0 bg-black/50 z-30 hidden">

        </div>

        <!-- Sidebar -->
        <aside
            id="sidebar"
            class="fixed top-0 left-0 z-40 w-72 h-screen bg-gray-900 text-white p-5 transform -translate-x-full transition-transform duration-300 overflow-y-auto">

            <!-- Header -->
            <div class="flex items-center justify-between mb-8">

                <div>

                    <h1 class="text-3xl font-bold">
                        Absensi App
                    </h1>

                    <p class="text-gray-400 text-sm">
                        Sistem Absensi
                    </p>

                </div>

                <button
                    onclick="toggleSidebar()"
                    class="text-white text-3xl">

                    ×

                </button>

            </div>

            <!-- User -->
            <div class="mb-8 border-b border-gray-700 pb-6">

                <div class="flex items-center gap-4">

                    <!-- Avatar -->
                    <div
                        class="w-14 h-14 rounded-full bg-blue-500 flex items-center justify-center text-2xl font-bold">

                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                    </div>

                    <!-- Info -->
                    <div>

                        <h2 class="font-bold text-xl">
                            {{ Auth::user()->name }}
                        </h2>

                        <p class="text-blue-300 text-sm">
                            {{ ucfirst(Auth::user()->role) }}
                        </p>

                    </div>

                </div>

            </div>

            <!-- Menu -->
            <nav class="space-y-3">

                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    📊 Dashboard

                </a>

                <!-- Data Absensi -->
                <a
                    href="{{ route('data.absensi') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    🕒 Data Absensi

                </a>

                <!-- Pengajuan -->
                <a
                    href="{{ route('pengajuan') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    📝 Pengajuan Izin

                </a>

                <!-- Jadwal -->
                <a
                    href="{{ route('jadwal.index') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    📅 Jadwal Karyawan

                </a>

                <!-- Karyawan -->
                @if(Auth::user()->role == 'admin')

                <a
                    href="{{ route('karyawan.index') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    👨‍💼 Data Karyawan

                </a>

                @endif

                <!-- Profile -->
                <a
                    href="{{ route('profile.edit') }}"
                    class="block px-4 py-3 rounded-xl hover:bg-gray-800 transition">

                    ⚙️ Profile

                </a>

            </nav>

            <!-- Logout -->
            <div class="mt-10">

                <form
                    method="POST"
                    action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 transition px-4 py-3 rounded-xl font-bold">

                        🚪 Logout

                    </button>

                </form>

            </div>

        </aside>

        <!-- Main -->
        <main class="min-h-screen">

            <!-- Topbar -->
            <div
                class="sticky top-0 z-20 bg-white shadow-sm px-6 py-4 flex items-center justify-between">

                <!-- Hamburger -->
                <button
                    onclick="toggleSidebar()"
                    class="bg-gray-900 text-white px-4 py-2 rounded-xl">

                    ☰

                </button>

                <!-- Date -->
                <div class="text-gray-500">

                    {{ now()->format('d M Y') }}

                </div>

            </div>

            <!-- Content -->
            <div class="p-6">

                @isset($header)

                    <header
                        class="bg-white shadow rounded-xl p-4 mb-6">

                        {{ $header }}

                    </header>

                @endisset

                {{ $slot }}

            </div>

        </main>

    </div>

    <script>

    function toggleSidebar()
    {
        const sidebar =
            document.getElementById('sidebar');

        const overlay =
            document.getElementById('overlay');

        sidebar.classList.toggle(
            '-translate-x-full'
        );

        overlay.classList.toggle(
            'hidden'
        );
    }

    </script>

</body>

</html>