@props(['class' => ''])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AbsensiApp') }} - Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100 antialiased">

    <div class="min-h-screen flex items-center justify-center p-6" x-data="{ showPass: false, loading: false, shake: false }">
        
        @if(session('error'))
        <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg animate-pulse" 
             x-init="shake = true; setTimeout(() => shake = false, 500)">
            {{ session('error') }}
        </div>
        @endif

        <div class="w-full max-w-md" 
             x-data="{ loading: false }"
             :class="{ 'animate-shake': shake }">
            
            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4-1a2 2 0 011 1v2a6 6 0 01-6 6h-.5a.5.5 0 01-.5-.5v-1.5a.5.5 0 01.5-.5H12m0 0V6a2 2 0 012-2h.5a.5.5 0 01.5.5v1.5a.5.5 0 01-.5.5H12a6 6 0 00-6 6v2a6 6 0 006 6h.5a.5.5 0 01.5.5v1.5a.5.5 0 01-.5.5h-.5a6 6 0 01-6-6v-2a6 6 0 016-6h.5a.5.5 0 01.5.5v-1.5a.5.5 0 01-.5.5H12a6 6 0 006-6V6a6 6 0 00-6-6h.5a.5.5 0 01.5-.5z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
                    <p class="text-gray-500 mt-2 text-sm">
                        Tidak masalah. Masukkan email Anda dan kami akan mengirimkan link untuk mereset password.
                    </p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" @submit="loading = true">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206l-1.103 2.06a.5.5 0 01-.91-.42l.85-3.2a.5.5 0 01.49-.35h3.808a.5.5 0 01.48.64l-1.103 2.06a.5.5 0 01-.91.42l-.85-3.2a.5.5 0 01.49-.35H15.5z"/>
                                </svg>
                            </span>
                            <input
                                type="email"
                                name="email"
                                required
                                autofocus
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="nama@email.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-red-500 text-xs" />
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-70 disabled:cursor-not-allowed text-white font-semibold py-3.5 rounded-xl shadow-lg shadow-blue-500/30 transition-all duration-200 flex items-center justify-center gap-2">

                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loading ? 'Mengirim...' : 'Kirim Link Reset Password'">Kirim Link Reset Password</span>
                    </button>

                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali ke login
                    </a>
                </div>

            </div>
        </div>
    </div>

    @if(session('status'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg animate-bounce">
        {{ session('status') }}
    </div>
    @endif

</body>
</html>
