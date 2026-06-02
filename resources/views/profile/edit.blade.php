<x-app-layout>

    <div class="min-h-screen bg-gray-100 py-10">

        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Header -->
            <div class="bg-white shadow rounded-2xl p-6">

                <h1 class="text-3xl font-bold text-gray-800">
                    Profile
                </h1>

                <p class="text-gray-500 mt-2">
                    Kelola akun dan informasi profile
                </p>

            </div>

            <!-- Update Profile -->
            <div class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-xl font-bold mb-5">
                    Informasi Profile
                </h2>

                @include('profile.partials.update-profile-information-form')

            </div>

            <!-- Update Password -->
            <div class="bg-white shadow rounded-2xl p-6">

                <h2 class="text-xl font-bold mb-5">
                    Ubah Password
                </h2>

                @include('profile.partials.update-password-form')

            </div>

            <!-- Delete Account -->
            <div class="bg-white shadow rounded-2xl p-6 border border-red-200">

                <h2 class="text-xl font-bold text-red-600 mb-5">
                    Hapus Account
                </h2>

                @include('profile.partials.delete-user-form')

            </div>

        </div>

    </div>

</x-app-layout>