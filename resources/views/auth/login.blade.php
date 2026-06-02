<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">

            <!-- Title -->
            <div class="text-center mb-8">

                <h1 class="text-3xl font-bold text-gray-800">
                    Sistem Absensi
                </h1>

                <p class="text-gray-500 mt-2">
                    Login untuk melanjutkan
                </p>

            </div>

            <!-- Session Status -->
            <x-auth-session-status
                class="mb-4"
                :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">

                @csrf

                <!-- Email -->
                <div class="mb-5">

                    <label class="block mb-2 text-gray-700">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        autofocus
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan password">

                    <x-input-error
                        :messages="$errors->get('password')"
                        class="mt-2" />

                </div>

                <!-- Remember -->
                <div class="flex items-center mb-5">

                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-gray-300 text-blue-600 shadow-sm">

                    <span class="ml-2 text-sm text-gray-600">
                        Remember me
                    </span>

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-bold py-3 rounded-xl">

                    Login

                </button>

            </form>

        </div>

    </div>

</x-guest-layout>