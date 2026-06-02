<x-app-layout>

    <div class="p-6 max-w-4xl mx-auto">

        <div class="bg-white rounded-3xl shadow-xl p-8">

            <h1 class="text-3xl font-bold mb-8">
                Edit Jadwal
            </h1>

            <form action="{{ route('jadwal.update', $jadwal->id) }}"
                  method="POST"
                  class="space-y-6">

                @csrf
                @method('PUT')

                {{-- USER --}}
                <div>
                    <label class="font-semibold">Karyawan</label>

                    <select name="user_id"
                            class="w-full border rounded-2xl p-4 mt-2">

                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $jadwal->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="font-semibold">Tanggal</label>

                    <input type="date"
                           name="tanggal"
                           value="{{ $jadwal->tanggal }}"
                           class="w-full border rounded-2xl p-4 mt-2">
                </div>

                {{-- SHIFT --}}
                <div>
                    <label class="font-semibold">Shift</label>

                    <select name="shift"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="Pagi"
                            {{ $jadwal->shift == 'Pagi' ? 'selected' : '' }}>
                            Pagi
                        </option>

                        <option value="Siang"
                            {{ $jadwal->shift == 'Siang' ? 'selected' : '' }}>
                            Siang
                        </option>

                        <option value="Malam"
                            {{ $jadwal->shift == 'Malam' ? 'selected' : '' }}>
                            Malam
                        </option>

                    </select>
                </div>

                {{-- STATUS (FIX FINAL) --}}
                <div>
                    <label class="font-semibold">Status</label>

                    <select name="status"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="Kerja"
                            {{ $jadwal->status == 'Kerja' ? 'selected' : '' }}>
                            Kerja
                        </option>

                        <option value="Libur"
                            {{ $jadwal->status == 'Libur' ? 'selected' : '' }}>
                            Libur
                        </option>

                    </select>
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-4 rounded-2xl font-bold">

                    Update Jadwal

                </button>

            </form>

        </div>

    </div>

</x-app-layout>