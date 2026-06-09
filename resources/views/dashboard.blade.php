<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <!-- Hero -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl mb-8">

            <div class="flex flex-col md:flex-row justify-between items-center gap-6">

                <div>

                    <h1 class="text-4xl font-bold mb-2">
                        Dashboard Absensi
                    </h1>

                    <p class="text-blue-100 text-lg">
                        Monitoring absensi karyawan realtime
                    </p>

                    <div class="mt-5 flex gap-3 flex-wrap">

                        <span
                            class="bg-white/20 px-4 py-2 rounded-xl text-sm">

                            User:
                            {{ Auth::user()->name }}

                        </span>

                        <span
                            class="bg-white/20 px-4 py-2 rounded-xl text-sm">

                            Role:
                            {{ Auth::user()->role }}

                        </span>

                    </div>

                </div>

                <!-- Clock -->
                <div
                    class="bg-white/10 backdrop-blur-lg rounded-2xl px-8 py-5 text-center min-w-[250px]">

                    <h2
                        id="clock"
                        class="text-5xl font-bold tracking-wider">

                        00:00:00

                    </h2>

                    <p
                        id="date"
                        class="mt-3 text-blue-100">

                    </p>

                </div>

            </div>

        </div>

        <!-- Alert -->
        @if(session('success'))

            <div
                class="bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-2xl mb-5">

                {{ session('success') }}

            </div>

        @endif

        @if(session('error'))

            <div
                class="bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-2xl mb-5">

                {{ session('error') }}

            </div>

        @endif

        <!-- Statistik -->
        <div
            class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-8">

            <!-- Total -->
            <div
                class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:scale-105 transition">

                <p class="text-gray-500 mb-2">
                    Total Jadwal
                </p>

                <h2 class="text-5xl font-bold text-blue-600">
                    {{ $total }}
                </h2>

            </div>

            <!-- Hadir -->
            <div
                class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:scale-105 transition">

                <p class="text-gray-500 mb-2">
                    Hadir
                </p>

                <h2 class="text-5xl font-bold text-green-500">
                    {{ $hadir }}
                </h2>

            </div>

            <!-- Telat -->
            <div
                class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:scale-105 transition">

                <p class="text-gray-500 mb-2">
                    Telat
                </p>

                <h2 class="text-5xl font-bold text-red-500">
                    {{ $telat }}
                </h2>

            </div>

            <!-- Izin/Sakit -->
            <div
                class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:scale-105 transition">

                <p class="text-gray-500 mb-2">
                    Izin / Sakit
                </p>

                <h2 class="text-5xl font-bold text-yellow-500">
                    {{ $izinSakit }}
                </h2>

            </div>

            <!-- Tidak Hadir -->
            <div
                class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100 hover:scale-105 transition">

                <p class="text-gray-500 mb-2">
                    Tidak Hadir
                </p>

                <h2 class="text-5xl font-bold text-gray-500">
                    {{ $tidakHadir }}
                </h2>

            </div>

        </div>

        @if(Auth::user()->role !== 'admin' && isset($jadwalHariIni))
        <!-- Jadwal Hari Ini -->
        <div class="mb-8">
            <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100">
                <h2 class="text-2xl font-bold mb-4">Jadwal Hari Ini</h2>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-6 8h6M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($jadwalHariIni->tanggal)->format('d M Y') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($jadwalHariIni->status == 'Libur')
                        <span class="px-4 py-1.5 rounded-full text-sm font-medium bg-gray-200 text-gray-600">
                            Libur
                        </span>
                        <span class="text-gray-500 text-sm">Tidak ada jam kerja</span>
                        @else
                        <span class="px-4 py-1.5 rounded-full text-sm font-medium
                            @if($jadwalHariIni->shift == 'Pagi') bg-blue-100 text-blue-700
                            @elseif($jadwalHariIni->shift == 'Siang') bg-orange-100 text-orange-700
                            @else bg-purple-100 text-purple-700 @endif">
                            {{ $jadwalHariIni->shift }}
                        </span>
                        <span class="text-gray-600 text-sm">
                            {{ $jadwalHariIni->jam_masuk ?? '-' }} - {{ $jadwalHariIni->jam_pulang ?? '-' }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::user()->role !== 'admin' && $absensiHariIni && in_array($absensiHariIni->status, ['Izin', 'Sakit']) && $absensiHariIni->approval == 'Approved')
        <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-800 px-5 py-4 rounded-2xl">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-bold">Anda sedang {{ strtolower($absensiHariIni->status) }} hari ini</span>
            </div>
            <p class="text-sm mt-1 text-yellow-700">Anda tidak dapat melakukan absen masuk/pulang untuk tanggal ini.</p>
        </div>
        @endif

        <!-- GPS -->
        <div class="grid grid-cols-1 gap-6 mb-8">

            <div class="bg-white rounded-3xl shadow-lg p-6">

                <h2 class="text-2xl font-bold mb-5">
                    Status Lokasi
                </h2>

                <button
                    onclick="getLocation()"
                    class="w-full bg-purple-600 hover:bg-purple-700 transition text-white py-3 rounded-2xl font-bold mb-5">

                    Cek Lokasi GPS

                </button>

                <div
                    class="bg-gray-100 rounded-2xl p-4 mb-4">

                    <p
                        id="lokasi"
                        class="text-sm text-gray-600 break-all">

                    </p>

                    <p
                        id="statusLokasi"
                        class="mt-3 font-bold">

                    </p>

                </div>

                <div id="map-container" class="hidden mb-4">
                    <iframe
                        id="office-map"
                        width="100%"
                        height="200"
                        style="border:0; border-radius: 1rem;"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"
                        src="">
                    </iframe>
                </div>

                <a
                    id="map-link"
                    href="#"
                    target="_blank"
                    class="hidden block w-full text-center bg-green-600 text-white py-2 rounded-xl mb-4">
                    Buka di Google Maps
                </a>

            </div>

        </div>

        </div>

        <!-- Action -->
        <div
            class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

<!-- Absen Masuk -->
            <form
                action="{{ route('absen.masuk') }}"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white rounded-3xl shadow-lg p-6" id="form-absen-masuk">

                @csrf

                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="hidden" name="foto" id="foto-base64">

                <div class="flex justify-between items-center mb-5">

                    <h2 class="text-2xl font-bold">
                        Absen Masuk
                    </h2>

                    <span
                        class="bg-blue-100 text-blue-600 px-4 py-1 rounded-full text-sm">

                        Masuk

                    </span>

                </div>

                <div class="mb-5">

                    <div id="camera-masuk" class="hidden mb-3">
                        <video id="video-masuk" autoplay playsinline class="w-full rounded-lg bg-gray-900 max-h-64"></video>
                        <canvas id="canvas-masuk" class="hidden"></canvas>
                    </div>

                    <img id="preview-masuk" class="hidden mb-3 max-h-64 rounded-lg" alt="Preview">

                    <button
                        type="button"
                        onclick="startCamera('masuk')"
                        id="btn-camera-masuk"
                        class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold mb-2">

                        Buka Kamera Absen Masuk

                    </button>

                    <button
                        type="button"
                        onclick="capturePhoto('masuk')"
                        id="btn-capture-masuk"
                        class="hidden w-full bg-green-600 text-white py-3 rounded-xl font-bold mb-2">

                        Ambil Foto

                    </button>

                    <button
                        type="button"
                        onclick="retakePhoto('masuk')"
                        id="btn-retake-masuk"
                        class="hidden w-full bg-gray-500 text-white py-3 rounded-xl font-bold mb-2">

                        Ulangi Foto

                    </button>

                </div>

                <button
                    id="btnAbsen"
                    type="submit"
                    disabled
                    class="w-full bg-gray-400 text-white py-3 rounded-2xl font-bold">

                    Absen Masuk

                </button>

            </form>

            <!-- Absen Pulang -->
            <form
                action="{{ route('absen.pulang') }}"
                method="POST"
                enctype="multipart/form-data"
                class="bg-white rounded-3xl shadow-lg p-6" id="form-absen-pulang">

                @csrf

                <input type="hidden" name="foto_pulang" id="foto-pulang-base64">

                <div class="flex justify-between items-center mb-5">

                    <h2 class="text-2xl font-bold">
                        Absen Pulang
                    </h2>

                    <span
                        class="bg-green-100 text-green-600 px-4 py-1 rounded-full text-sm">

                        Pulang

                    </span>

                </div>

                <div class="mb-5">

                    <div id="camera-pulang" class="hidden mb-3">
                        <video id="video-pulang" autoplay playsinline class="w-full rounded-lg bg-gray-900 max-h-64"></video>
                        <canvas id="canvas-pulang" class="hidden"></canvas>
                    </div>

                    <img id="preview-pulang" class="hidden mb-3 max-h-64 rounded-lg" alt="Preview">

                    <button
                        type="button"
                        onclick="startCamera('pulang')"
                        id="btn-camera-pulang"
                        class="w-full bg-green-600 text-white py-3 rounded-xl font-bold mb-2">

                        Buka Kamera Absen Pulang

                    </button>

                    <button
                        type="button"
                        onclick="capturePhoto('pulang')"
                        id="btn-capture-pulang"
                        class="hidden w-full bg-green-600 text-white py-3 rounded-xl font-bold mb-2">

                        Ambil Foto

                    </button>

                    <button
                        type="button"
                        onclick="retakePhoto('pulang')"
                        id="btn-retake-pulang"
                        class="hidden w-full bg-gray-500 text-white py-3 rounded-xl font-bold mb-2">

                        Ulangi Foto

                    </button>

                </div>

                <button
                    id="btnPulang"
                    type="submit"
                    disabled
                    class="w-full bg-gray-400 text-white py-3 rounded-2xl font-bold">

                    Absen Pulang

                </button>

            </form>

        </div>

        <!-- Form Pengajuan -->
        <div
            class="bg-white rounded-3xl shadow-lg p-6">

            <div
                class="flex justify-between items-center mb-5 flex-wrap gap-3">

                <div>

                    <h2 class="text-2xl font-bold">
                        Pengajuan Izin / Sakit
                    </h2>

                    <p class="text-gray-500">
                        Pengajuan akan dicek admin
                    </p>

                </div>

                <div
                    class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-xl text-sm">

                    Menunggu Approval

                </div>

            </div>

            <form
                action="{{ route('izin') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">

                    <!-- Status -->
                    <div>

                        <label class="block mb-2 font-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="w-full border rounded-2xl px-4 py-3">

                            <option value="Izin">
                                Izin
                            </option>

                            <option value="Sakit">
                                Sakit
                            </option>

                        </select>

                    </div>

                    <!-- Keterangan -->
                    <div>

                        <label class="block mb-2 font-semibold">
                            Keterangan
                        </label>

                        <input
                            type="text"
                            name="keterangan"
                            class="w-full border rounded-2xl px-4 py-3">

                    </div>

                    <!-- Tanggal -->
                    <div>

                        <label class="block mb-2 font-semibold">
                            Tanggal Izin/Sakit
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            value="{{ date('Y-m-d') }}"
                            class="w-full border rounded-2xl px-4 py-3">

                    </div>

                </div>

                <!-- Upload Foto -->
                <div class="mb-5">

                    <label class="block mb-2 font-semibold">
                        Upload Foto (Opsional)
                    </label>

                    <input
                        type="file"
                        name="foto"
                        accept="image/*"
                        class="w-full border rounded-2xl px-4 py-3">

                </div>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 transition text-white px-6 py-3 rounded-2xl font-bold">

                    Kirim Pengajuan

                </button>

            </form>

        </div>

    </div>

    <script>

    function updateClock()
    {
        const now = new Date();

        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        document.getElementById('clock').innerHTML = `${hours}:${minutes}:${seconds}`;

        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        document.getElementById('date').innerHTML = now.toLocaleDateString('id-ID', options);
    }

    setInterval(updateClock, 1000);
    updateClock();

    const OFFICE_LAT = -6.8048;
    const OFFICE_LON = 110.8405;

    const MAX_DISTANCE = 5000;

    function getLocation()
    {
        if (navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position)
    {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lon;

        document.getElementById("lokasi").innerHTML = "Latitude: " + lat + "<br>Longitude: " + lon;

        let distance = calculateDistance(lat, lon, OFFICE_LAT, OFFICE_LON);

        document.getElementById("map-link").href = "https://www.google.com/maps?q=" + lat + "," + lon;
        document.getElementById("map-link").classList.remove("hidden");
        document.getElementById("map-container").classList.remove("hidden");
        document.getElementById("office-map").src = "https://www.openstreetmap.org/export/embed.html?lat=" + lat + "&lon=" + lon + "&zoom=16&marker=" + lat + "%2C" + lon;

        if (distance <= MAX_DISTANCE)
        {
            document.getElementById("statusLokasi").innerHTML = "Anda berada di area kantor";
            document.getElementById("statusLokasi").className = "mt-3 font-bold text-green-600";
        }
        else
        {
            document.getElementById("statusLokasi").innerHTML = "Anda berada di luar area kantor";
            document.getElementById("statusLokasi").className = "mt-3 font-bold text-red-600";

            document.getElementById("btnAbsen").disabled = true;
        }
    }

    function calculateDistance(lat1, lon1, lat2, lon2)
    {
        const R = 6371e3;

        const φ1 = lat1 * Math.PI / 180;
        const φ2 = lat2 * Math.PI / 180;
        const Δφ = (lat2 - lat1) * Math.PI / 180;
        const Δλ = (lon2 - lon1) * Math.PI / 180;

        const a =
            Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
            Math.cos(φ1) *
            Math.cos(φ2) *
            Math.sin(Δλ / 2) *
            Math.sin(Δλ / 2);

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return R * c;
    }

    window.onload = getLocation;

    // Camera functions
    let mediaStream = null;

    function startCamera(type) {
        const video = document.getElementById(`video-${type}`);
        const container = document.getElementById(`camera-${type}`);
        const btnCamera = document.getElementById(`btn-camera-${type}`);
        const btnCapture = document.getElementById(`btn-capture-${type}`);

        container.classList.remove('hidden');
        btnCamera.classList.add('hidden');
        btnCapture.classList.remove('hidden');

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user' }
        }).then(stream => {
            mediaStream = stream;
            video.srcObject = stream;
        }).catch(err => {
            alert('Tidak dapat mengakses kamera: ' + err.message);
            container.classList.add('hidden');
            btnCamera.classList.remove('hidden');
        });
    }

    function capturePhoto(type) {
        const video = document.getElementById(`video-${type}`);
        const canvas = document.getElementById(`canvas-${type}`);
        const preview = document.getElementById(`preview-${type}`);
        const input = document.getElementById(type === 'masuk' ? 'foto-base64' : 'foto-pulang-base64');
        const btnCapture = document.getElementById(`btn-capture-${type}`);
        const btnRetake = document.getElementById(`btn-retake-${type}`);
        const container = document.getElementById(`camera-${type}`);

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0);

        // Stop camera
        if (mediaStream) {
            mediaStream.getTracks().forEach(track => track.stop());
            mediaStream = null;
        }

        // Convert to base64
        const dataURL = canvas.toDataURL('image/jpeg');
        input.value = dataURL;

        // Show preview
        preview.src = dataURL;
        preview.classList.remove('hidden');

        // Hide camera, show retake
        container.classList.add('hidden');
        btnCapture.classList.add('hidden');
        btnRetake.classList.remove('hidden');

        // Enable submit button if masuk
        if (type === 'masuk') {
            document.getElementById('btnAbsen').disabled = false;
            document.getElementById('btnAbsen').classList.remove('bg-gray-400');
            document.getElementById('btnAbsen').classList.add('bg-blue-600');
        }

        // Enable submit button if pulang
        if (type === 'pulang') {
            const btnPulang = document.getElementById('btnPulang');
            if (btnPulang) {
                btnPulang.disabled = false;
                btnPulang.classList.remove('bg-gray-400');
                btnPulang.classList.add('bg-green-500');
            }
        }
    }

    function retakePhoto(type) {
        const preview = document.getElementById(`preview-${type}`);
        const input = document.getElementById(type === 'masuk' ? 'foto-base64' : 'foto-pulang-base64');
        const btnRetake = document.getElementById(`btn-retake-${type}`);
        const btnCamera = document.getElementById(`btn-camera-${type}`);

        preview.classList.add('hidden');
        input.value = '';

        btnRetake.classList.add('hidden');
        btnCamera.classList.remove('hidden');

        // Disable submit button if masuk
        if (type === 'masuk') {
            document.getElementById('btnAbsen').disabled = true;
            document.getElementById('btnAbsen').classList.remove('bg-blue-600');
            document.getElementById('btnAbsen').classList.add('bg-gray-400');
        }

        // Disable submit button if pulang
        if (type === 'pulang') {
            const btnPulang = document.getElementById('btnPulang');
            if (btnPulang) {
                btnPulang.disabled = true;
                btnPulang.classList.remove('bg-green-500');
                btnPulang.classList.add('bg-gray-400');
            }
        }
    }

    </script>

</x-app-layout>