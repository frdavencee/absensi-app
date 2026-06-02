<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">

            <h1 class="text-3xl font-bold text-center mb-3">
                Lupa Password
            </h1>

            <p class="text-gray-500 text-center mb-6">
                Masukkan email untuk reset password
            </p>

            <form method="POST"
                  action="{{ route('password.email') }}">

                @csrf

                <div class="mb-5">

                    <label class="block mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full border rounded-xl px-4 py-3">

                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl">

                    Kirim Link Reset

                </button>

            </form>

        </div>

    </div>

</x-guest-layout>