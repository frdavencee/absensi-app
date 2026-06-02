<x-app-layout>

    <div class="max-w-3xl mx-auto p-6">

        <div class="bg-white rounded-3xl shadow-lg p-8">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h1 class="text-3xl font-bold">
                        Tambah Karyawan
                    </h1>

                    <p class="text-gray-500 mt-1">
                        Tambahkan data karyawan baru
                    </p>

                </div>

                <a
                    href="{{ route('karyawan.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 transition px-5 py-3 rounded-2xl">

                    Kembali

                </a>

            </div>

            @if ($errors->any())

                <div class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl mb-6">

                    <ul class="list-disc pl-5">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <form
                action="{{ route('karyawan.store') }}"
                method="POST">

                @csrf

                <!-- Nama -->
                <div class="mb-6">

                    <label class="block mb-2 font-semibold">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="w-full border rounded-2xl px-4 py-3"
                        placeholder="Masukkan nama karyawan">

                </div>

                <!-- Email -->
                <div class="mb-6">

                    <label class="block mb-2 font-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="w-full border rounded-2xl px-4 py-3"
                        placeholder="Masukkan email">

                </div>

                <!-- Password -->
                <div class="mb-6">

                    <label class="block mb-2 font-semibold">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded-2xl px-4 py-3"
                        placeholder="Masukkan password">

                </div>

                <!-- Role -->
                <div class="mb-8">

                    <label class="block mb-2 font-semibold">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full border rounded-2xl px-4 py-3">

                        <option value="user">
                            User
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-4 rounded-2xl font-bold text-lg">

                    Tambah Karyawan

                </button>

            </form>

        </div>

    </div>

</x-app-layout>