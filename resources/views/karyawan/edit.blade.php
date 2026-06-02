<x-app-layout>

    <div class="max-w-3xl mx-auto p-6">

        <div
            class="bg-white rounded-3xl shadow-xl p-8">

            <h1
                class="text-3xl font-bold mb-8">

                Edit Karyawan

            </h1>

            <form
                action="{{ route('karyawan.update', $user->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <!-- Nama -->
                <div class="mb-5">

                    <label class="block mb-2 font-semibold">

                        Nama

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ $user->name }}"
                        class="w-full border rounded-2xl px-4 py-3">

                </div>

                <!-- Email -->
                <div class="mb-5">

                    <label class="block mb-2 font-semibold">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ $user->email }}"
                        class="w-full border rounded-2xl px-4 py-3">

                </div>

                <div class="mb-4">

                    <label class="block font-semibold mb-2">
                        Password (kosongkan jika tidak diubah)
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded-xl p-3"
                        placeholder="Masukkan password baru (opsional)"
                    >

                </div>

                <!-- Role -->
                <div class="mb-8">

                    <label class="block mb-2 font-semibold">

                        Role

                    </label>

                    <select
                        name="role"
                        class="w-full border rounded-2xl px-4 py-3">

                        <option
                            value="admin"
                            {{ $user->role == 'admin' ? 'selected' : '' }}>

                            Admin

                        </option>

                        <option
                            value="user"
                            {{ $user->role == 'user' ? 'selected' : '' }}>

                            User

                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-bold">

                    Simpan Perubahan

                </button>

            </form>

        </div>

    </div>

</x-app-layout>