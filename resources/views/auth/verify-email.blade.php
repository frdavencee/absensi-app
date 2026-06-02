<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8 text-center">

            <h1 class="text-3xl font-bold mb-4">
                Verifikasi Email
            </h1>

            <p class="text-gray-500 mb-6">

                Silakan cek email kamu untuk verifikasi akun.

            </p>

            <form method="POST"
                  action="{{ route('verification.send') }}">

                @csrf

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl">

                    Kirim Ulang Email

                </button>

            </form>

        </div>

    </div>

</x-guest-layout>