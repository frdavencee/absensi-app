<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl mb-8">

            <div class="flex justify-between items-center flex-wrap gap-5">

                <div>

                    <h1 class="text-4xl font-bold mb-2">
                        Data Karyawan
                    </h1>

                    <p class="text-blue-100">
                        Kelola data seluruh karyawan
                    </p>

                </div>

                <a
                    href="{{ route('karyawan.create') }}"
                    class="bg-white text-blue-700 px-6 py-3 rounded-2xl font-bold hover:scale-105 transition">

                    + Tambah Karyawan

                </a>

            </div>

        </div>

        <!-- Alert -->
        @if(session('success'))

            <div
                class="bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-2xl mb-6">

                {{ session('success') }}

            </div>

        @endif

        <!-- Table -->
        <div
            class="bg-white rounded-3xl shadow-lg overflow-hidden">

            <div
                class="overflow-x-auto">

                <table class="w-full">

                    <thead
                        class="bg-gray-100">

                        <tr>

                            <th class="text-left px-6 py-4">
                                No
                            </th>

                            <th class="text-left px-6 py-4">
                                Nama
                            </th>

                            <th class="text-left px-6 py-4">
                                Email
                            </th>

                            <th class="text-left px-6 py-4">
                                Role
                            </th>

                            <th class="text-left px-6 py-4">
                                Dibuat
                            </th>

                            <th class="text-center px-6 py-4">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            <tr
                                class="border-b hover:bg-gray-50 transition">

                                <td class="px-6 py-4">

                                    {{ $loop->iteration }}

                                </td>

                                <td class="px-6 py-4 font-semibold">

                                    {{ $user->name }}

                                </td>

                                <td class="px-6 py-4 text-gray-600">

                                    {{ $user->email }}

                                </td>

                                <td class="px-6 py-4">

                                    @if($user->role == 'admin')

                                        <span
                                            class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-bold">

                                            Admin

                                        </span>

                                    @else

                                        <span
                                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">

                                            User

                                        </span>

                                    @endif

                                </td>

                                <td class="px-6 py-4 text-gray-500">

                                    {{ $user->created_at->format('d M Y') }}

                                </td>

                                <td class="px-6 py-4">

                                    <div
                                        class="flex justify-center gap-3">

                                        <!-- Edit -->
                                        <a
                                            href="{{ route('karyawan.edit', $user->id) }}"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-xl transition">

                                            Edit

                                        </a>

                                        <!-- Hapus -->
                                        <form
                                            action="{{ route('karyawan.destroy', $user->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl transition">

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-10 text-gray-500">

                                    Belum ada data karyawan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-app-layout>