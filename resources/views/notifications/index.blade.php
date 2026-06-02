<x-app-layout>

    <div class="max-w-4xl mx-auto p-6">

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Notifikasi</h1>
            <p class="text-gray-500 mt-1">Pesan dan pengumuman sistem</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            @forelse($notifications as $notification)
            <div class="p-5 border-b border-gray-100 hover:bg-gray-50 transition {{ $notification->read_at ? '' : 'bg-blue-50/30' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-gray-800 font-medium">{{ $notification->data['message'] ?? $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                        @if(isset($notification->data['tanggal']))
                        <p class="text-sm text-gray-500 mt-1">Tanggal: {{ $notification->data['tanggal'] }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>

                    @if(!$notification->read_at)
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                            Tandai Dibaca
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            @empty
            <div class="p-12 text-center">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.5 17h5l-2.5-2.5L15 12h-5l-2.5 2.5L10.5 17z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Belum ada notifikasi</p>
                </div>
            </div>
            @endforelse

        </div>

    </div>

</x-app-layout>