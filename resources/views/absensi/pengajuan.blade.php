<x-app-layout>

<div class="p-6">

    <h1 class="text-2xl font-bold mb-5">
        Pengajuan Izin / Sakit
    </h1>

    <table class="table-auto border w-full">

        <tr class="bg-gray-200">

            <th class="border p-2">Nama</th>
            <th class="border p-2">Tanggal</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Keterangan</th>
            <th class="border p-2">Approval</th>
            <th class="border p-2">Action</th>

        </tr>

        @foreach($data as $d)

        <tr>

            <td class="border p-2">
                {{ $d->user->name }}
            </td>

            <td class="border p-2">
                {{ $d->tanggal }}
            </td>

            <td class="border p-2">
                {{ $d->status }}
            </td>

            <td class="border p-2">
                {{ $d->keterangan }}
            </td>

            <td class="border p-2">
                {{ $d->approval }}
            </td>

            <td class="border p-2">

                @if(
                    Auth::user()->role == 'admin'
                    && $d->approval == 'Pending'
                )

                <form
                    action="{{ route('approve', $d->id) }}"
                    method="POST">

                    @csrf

                    <button
                        class="bg-green-500 text-white px-3 py-1 rounded">

                        Approve

                    </button>

                </form>

                @endif

            </td>

        </tr>

        @endforeach

    </table>

</div>

</x-app-layout>