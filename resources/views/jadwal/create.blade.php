<x-app-layout>

    <div class="p-6 max-w-4xl mx-auto">

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <a href="{{ request('user_id') ? route('jadwal.index', ['user_id' => request('user_id')]) : route('jadwal.index') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium transition mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>

            <h1 class="text-3xl font-bold mb-8">
                Tambah Jadwal
            </h1>

            <form action="{{ route('jadwal.store') }}" method="POST" class="space-y-6">

                @csrf

                {{-- USER --}}
                <div>
                    <label class="font-semibold">Karyawan</label>

                    @if(request('user_id'))
                        @php
                            $selectedUser = \App\Models\User::find(request('user_id'));
                        @endphp
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <input type="text"
                               value="{{ $selectedUser->name ?? '' }}"
                               class="w-full border rounded-2xl p-4 mt-2 bg-gray-100"
                               readonly>
                    @else
                        <select name="user_id"
                                class="w-full border rounded-2xl p-4 mt-2">

                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                    @endif
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="font-semibold">Tanggal</label>

                    <input type="date"
                           name="tanggal"
                           value="{{ old('tanggal') }}"
                           class="w-full border rounded-2xl p-4 mt-2">
                </div>

                {{-- SHIFT --}}
                <div>
                    <label class="font-semibold">Shift</label>

                    <select name="shift"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="Pagi">Pagi</option>
                        <option value="Siang">Siang</option>
                        <option value="Malam">Malam</option>

                    </select>
                </div>

                {{-- STATUS (FIX: hanya jadwal) --}}
                <div>
                    <label class="font-semibold">Status</label>

                    <select name="status"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="Kerja">Kerja</option>
                        <option value="Libur">Libur</option>

                    </select>
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold">

                    Simpan Jadwal

                </button>

            </form>

        </div>

    </div>

</x-app-layout>