<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AbsensiApp') }}</title>
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
                    <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
                    <p class="text-gray-500 mt-1">Masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login') }}" @submit="loading = true">
                    @csrf

                    <!-- Email -->
                    <div class="mb-5">
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

                    <!-- Password -->
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input
                                :type="showPass ? 'text' : 'password'"
                                name="password"
                                required
                                class="w-full pl-10 pr-12 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l-3.293-3.293m0 0a3 3 0 104.243-4.243l3.293 3.293m-3.293-3.293l3.293 3.293M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-red-500 text-xs" />
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>

                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500 transition">
                            Lupa password?
                        </a>
                        @endif
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
                        <span x-text="loading ? 'Sedang masuk...' : 'Masuk'">Masuk</span>
                    </button>

                </form>

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
