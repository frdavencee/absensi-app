<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">

            <h1 class="text-3xl font-bold text-center mb-6">
                Reset Password
            </h1>

            <form method="POST"
                  action="{{ route('password.store') }}">

                @csrf

                <input type="hidden"
                       name="token"
                       value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">

                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Email">

                </div>

                <!-- Password -->
                <div class="mb-4">

                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Password baru">

                </div>

                <!-- Confirm -->
                <div class="mb-5">

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        class="w-full border rounded-xl px-4 py-3"
                        placeholder="Konfirmasi password">

                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl">

                    Reset Password

                </button>

            </form>

        </div>

    </div>

</x-guest-layout>