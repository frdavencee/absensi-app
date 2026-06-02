<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">

            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    Buat Akun
                </h1>

                <p class="text-gray-500 mt-2">
                    Daftar untuk menggunakan sistem absensi
                </p>

            </div>

            <form method="POST" action="{{ route('register') }}">

                @csrf

                <!-- Nama -->
                <div class="mb-5">

                    <label class="block mb-2 text-gray-700">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        required
                        autofocus
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Masukkan nama">

                    <x-input-error
                        :messages="$errors->get('name')"
                        class="mt-2" />

                </div>

                <!-- Email -->
                <div class="mb-5">

                    <label class="block mb-2 text-gray-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Masukkan email">

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2" />

                </div>

                <!-- Password -->
                <div class="mb-5">

                    <label class="block mb-2 text-gray-700">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Masukkan password">

                </div>

                <!-- Confirm -->
                <div class="mb-5">

                    <label class="block mb-2 text-gray-700">
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Konfirmasi password">

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-bold py-3 rounded-xl">

                    Register

                </button>

                <div class="text-center mt-5">

                    <a
                        href="{{ route('login') }}"
                        class="text-blue-500 hover:underline">

                        Sudah punya akun?

                    </a>

                </div>

            </form>

        </div>

    </div>

</x-guest-layout>