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
                    <input type="hidden" name="user_id" value="{{ $jadwal->user_id }}">
                    <input type="text"
                           value="{{ $jadwal->user->name }}"
                           class="w-full border rounded-2xl p-4 mt-2 bg-gray-100"
                           readonly>
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="font-semibold">Tanggal</label>

                     <input type="date"
                            name="tanggal"
                            value="{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('Y-m-d') }}"
                            class="w-full border rounded-2xl p-4 mt-2">
                </div>

                {{-- SHIFT --}}
                <div>
                    <label class="font-semibold">Shift</label>

                    <select name="shift"
                            id="shift-select"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="">-- Pilih Shift --</option>
                        <option value="Pagi">Pagi</option>
                        <option value="Siang">Siang</option>
                        <option value="Malam">Malam</option>

                    </select>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="font-semibold">Status</label>

                    <select name="status"
                            id="status-select"
                            class="w-full border rounded-2xl p-4 mt-2">

                        <option value="Kerja">Kerja</option>
                        <option value="Libur">Libur</option>

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

    <script>
        const statusSelect = document.getElementById('status-select');
        const shiftSelect = document.getElementById('shift-select');

        function toggleShift() {
            if (statusSelect.value === 'Libur') {
                shiftSelect.value = '';
                shiftSelect.disabled = true;
                shiftSelect.classList.add('bg-gray-100', 'text-gray-400');
            } else {
                shiftSelect.disabled = false;
                shiftSelect.classList.remove('bg-gray-100', 'text-gray-400');
                if (!shiftSelect.value) {
                    shiftSelect.value = 'Pagi';
                }
            }
        }

        statusSelect.addEventListener('change', toggleShift);

        document.addEventListener('DOMContentLoaded', function() {
            statusSelect.value = "{{ $jadwal->status }}";
            shiftSelect.value = "{{ $jadwal->shift ?? '' }}";
            toggleShift();
        });
    </script>

</x-app-layout>