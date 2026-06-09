<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use App\Notifications\PengajuanNotification;

class AbsensiController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $hariIni = Carbon::today();

        $jadwalHariIni = Jadwal::where('user_id', $user->id)
            ->whereDate('tanggal', $hariIni)
            ->first();

        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $hariIni)
            ->first();

        if ($user->role === 'admin') {
            $total = Jadwal::count();
            $hadir = Absensi::where('status', 'Hadir')->count();
            $telat = Absensi::where('status', 'Telat')->count();
            $izinSakit = Absensi::whereIn('status', ['Izin', 'Sakit'])->count();
            $tidakHadir = 0;
            $today = Carbon::today();
            foreach (\App\Models\User::all() as $u) {
                foreach (Jadwal::where('user_id', $u->id)->where('status', 'Kerja')->whereDate('tanggal', '<', $today)->get() as $jadwal) {
                    $ada = Absensi::where('user_id', $u->id)
                        ->where('tanggal', $jadwal->tanggal)
                        ->whereIn('status', ['Hadir', 'Telat', 'Izin', 'Sakit'])
                        ->exists();
                    if (!$ada) {
                        $tidakHadir++;
                    }
                }
            }
        } else {
            $total = Jadwal::where('user_id', $user->id)->count();
            $hadir = Absensi::where('user_id', $user->id)->where('status', 'Hadir')->count();
            $telat = Absensi::where('user_id', $user->id)->where('status', 'Telat')->count();
            $izinSakit = Absensi::where('user_id', $user->id)->whereIn('status', ['Izin', 'Sakit'])->count();
            $tidakHadir = 0;
            $today = Carbon::today();
            foreach (Jadwal::where('user_id', $user->id)->where('status', 'Kerja')->whereDate('tanggal', '<', $today)->get() as $jadwal) {
                $adaAbsensi = Absensi::where('user_id', $user->id)
                    ->where('tanggal', $jadwal->tanggal)
                    ->whereIn('status', ['Hadir', 'Telat', 'Izin', 'Sakit'])
                    ->exists();
                if (!$adaAbsensi) {
                    $tidakHadir++;
                }
            }
        }

        return view('dashboard', compact(
            'jadwalHariIni',
            'absensiHariIni',
            'total',
            'hadir',
            'telat',
            'izinSakit',
            'tidakHadir'
        ));
    }

    public function masuk(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required'
        ]);

        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        // Find active shift (today or yesterday)
        $jadwals = Jadwal::where('user_id', $user->id)
            ->whereIn('tanggal', [$yesterday, $today])
            ->get();

        $activeJadwal = null;
        foreach ($jadwals as $jadwal) {
            if (empty($jadwal->jam_masuk) || empty($jadwal->jam_pulang)) {
                continue;
            }

            $tanggalStr = $jadwal->tanggal instanceof \Carbon\Carbon ? $jadwal->tanggal->format('Y-m-d') : (string) $jadwal->tanggal;
            $start = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalStr . ' ' . $jadwal->jam_masuk);
            $end = Carbon::createFromFormat('Y-m-d H:i:s', $tanggalStr . ' ' . $jadwal->jam_pulang);

            if ($jadwal->jam_pulang < $jadwal->jam_masuk) {
                $end->addDay();
            }

            $clockInStart = $start->copy()->subHour();

            if ($now->between($clockInStart, $end)) {
                $activeJadwal = $jadwal;
                break;
            }
        }

        if (!$activeJadwal) {
            return back()->with('error', 'Absen masuk hanya bisa dilakukan mulai 1 jam sebelum jadwal dimulai');
        }

        // Check if already absen for this shift
        $tanggalJadwal = $tanggalStr ?? ($activeJadwal->tanggal instanceof \Carbon\Carbon ? $activeJadwal->tanggal->format('Y-m-d') : (string) $activeJadwal->tanggal);

        $hasIzinSakit = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggalJadwal)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->where('approval', 'Approved')
            ->exists();

        if ($hasIzinSakit) {
            return back()->with('error', 'Tidak bisa absen pada tanggal tersebut karena sedang izin/sakit yang sudah disetujui');
        }

        $absen = Absensi::where('user_id', $user->id)
            ->where('tanggal', $activeJadwal->tanggal)
            ->first();

        if ($absen) {
            return back()->with('error', 'Sudah absen untuk shift ini');
        }

        $start = Carbon::createFromFormat('Y-m-d H:i:s', ($activeJadwal->tanggal instanceof \Carbon\Carbon ? $activeJadwal->tanggal->format('Y-m-d') : (string) $activeJadwal->tanggal) . ' ' . $activeJadwal->jam_masuk);
        $status = $now->greaterThan($start) ? 'Telat' : 'Hadir';

        $data = [
            'user_id' => $user->id,
            'tanggal' => $activeJadwal->tanggal,
            'jam_masuk' => $now->format('H:i:s'),
            'status' => $status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if (strpos($request->foto, 'base64') !== false) {
            $image = explode(',', $request->foto);
            $imageData = base64_decode($image[1]);
            $namaFoto = time() . '_' . $user->id . '_masuk.jpg';
            Storage::disk('public')->put('foto/' . $namaFoto, $imageData);
            $data['foto_masuk'] = $namaFoto;
        } elseif ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $user->id . '_masuk.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/foto', $namaFoto);
            $data['foto_masuk'] = $namaFoto;
        }

        Absensi::create($data);

        return back()->with('success', 'Absen masuk berhasil');
    }

    public function pulang(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Find the most recent absen record without pulang time (open shift)
        $absen = Absensi::where('user_id', $user->id)
            ->whereNull('jam_pulang')
            ->orderBy('tanggal', 'desc')
            ->first();

        if (!$absen) {
            return back()->with('error', 'Belum absen masuk');
        }

        $tanggalStr = $absen->tanggal instanceof \Carbon\Carbon ? $absen->tanggal->format('Y-m-d') : (string) $absen->tanggal;
        $hasIzinSakit = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggalStr)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->where('approval', 'Approved')
            ->exists();

        if ($hasIzinSakit) {
            return back()->with('error', 'Tidak bisa absen pulang pada tanggal tersebut karena sedang izin/sakit yang sudah disetujui');
        }

        $jadwal = Jadwal::where('user_id', $user->id)
            ->whereDate('tanggal', $tanggalStr)
            ->whereNotNull('jam_pulang')
            ->first();

        if ($jadwal) {
            $jamPulangJadwal = Carbon::createFromFormat('H:i:s', $jadwal->jam_pulang);
            $batasPulang = $jamPulangJadwal->copy()->addHours(2);

            if ($now->greaterThanOrEqualTo($batasPulang)) {
                return back()->with('error', 'Absen pulang maksimal 2 jam setelah jadwal pulang (' . $jadwal->jam_pulang . '). Sekarang: ' . $now->format('H:i'));
            }
        }

        $absen->update([
            'jam_pulang' => $now->format('H:i:s'),
        ]);

        return back()->with('success', 'Absen pulang berhasil');
    }

    public function izin(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'keterangan' => 'required',
            'tanggal' => 'required|date',
            'foto' => 'nullable|image|max:2048'
        ]);

        $user = Auth::user();

        $exists = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $request->tanggal)
            ->first();

        if ($exists) {
            return back()->with('error', 'Sudah ada pengajuan untuk tanggal ini');
        }

        $data = [
            'user_id' => $user->id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ];

        if (strpos($request->foto, 'base64') !== false) {
            $image = explode(',', $request->foto);
            $imageData = base64_decode($image[1]);
            $namaFoto = time() . '_' . $user->id . '_izin.jpg';
            Storage::disk('public')->put('foto/' . $namaFoto, $imageData);
            $data['foto_masuk'] = $namaFoto;
        } elseif ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFoto = time() . '_' . $user->id . '_izin.' . $foto->getClientOriginalExtension();
            $foto->storeAs('public/foto', $namaFoto);
            $data['foto_masuk'] = $namaFoto;
        }

        Absensi::create($data);

        // Notify user and admins
        $user->notify(new PengajuanNotification($request->status, $request->tanggal));
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new PengajuanNotification($request->status, $request->tanggal));

        return back()->with('success', 'Pengajuan berhasil');
    }

    public function dataAbsensi(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin' && !$request->filled('user_id')) {
            $users = \App\Models\User::orderBy('name')->get();
            $users = $users->map(function ($u) {
                $u->total_hadir = $u->absensis()->where('status', 'Hadir')->count();
                $u->total_telat = $u->absensis()->where('status', 'Telat')->count();
                $u->total_izin = $u->absensis()->where('status', 'Izin')->count();
                $u->total_sakit = $u->absensis()->where('status', 'Sakit')->count();
                return $u;
            });

            return view('absensi.data', compact('users'));
        }

        $query = Absensi::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $selectedUser = null;
        if ($user->role === 'admin' && $request->filled('user_id')) {
            $selectedUser = \App\Models\User::find($request->user_id);
        }

        $users = \App\Models\User::orderBy('name')->get();

        return view('absensi.data', compact('data', 'users', 'selectedUser'));
    }

    public function pengajuan(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            if (!$request->filled('user_id')) {
                $users = \App\Models\User::orderBy('name')->get();
                $users = $users->map(function ($u) {
                    $u->absensis_count = $u->absensis()->whereIn('status', ['Izin', 'Sakit'])->count();
                    return $u;
                });

                return view('pengajuan.index', compact('users'));
            }

            $data = Absensi::with('user')
                ->whereIn('status', ['Izin', 'Sakit'])
                ->when($request->filled('bulan'), function ($q, $bulan) { $q->whereMonth('tanggal', $bulan); })
                ->when($request->filled('tahun'), function ($q, $tahun) { $q->whereYear('tanggal', $tahun); })
                ->where('user_id', $request->user_id)
                ->orderBy('tanggal', 'desc')
                ->get();

            $selectedUser = \App\Models\User::find($request->user_id);

            return view('pengajuan.index', compact('data', 'selectedUser'));
        }

        $data = Absensi::with('user')
            ->whereIn('status', ['Izin', 'Sakit'])
            ->where('user_id', $user->id)
            ->when($request->filled('bulan'), function ($q, $bulan) { $q->whereMonth('tanggal', $bulan); })
            ->when($request->filled('tahun'), function ($q, $tahun) { $q->whereYear('tanggal', $tahun); })
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pengajuan.index', compact('data'));
    }

    public function approve($id)
    {
        $absensi = Absensi::findOrFail($id);

        $absensi->update([
            'approval' => 'Approved'
        ]);

        // Notify user of approval
        $user = $absensi->user;
        $user->notify(new PengajuanNotification('disetujui', $absensi->tanggal));

        return back()->with('success', 'Disetujui');
    }

    public function destroyPengajuan($id)
    {
        $absensi = Absensi::findOrFail($id);
        // Notify user of rejection/deletion
        $user = $absensi->user;
        $user->notify(new PengajuanNotification('ditolak', $absensi->tanggal));

        $absensi->delete();

        return back()->with('success', 'Pengajuan dihapus');
    }

    public function destroyAbsensi($id)
    {
        Absensi::findOrFail($id)->delete();

        return back()->with('success', 'Data absensi dihapus');
    }

    public function exportCsv()
    {
        $user = Auth::user();

        $data = Absensi::with('user')
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        // CSV header
        $csv = "Tanggal,Jam Masuk,Jam Pulang,Status,Keterangan\n";

        foreach ($data as $d) {
            // Escape each field for CSV: enclose in double quotes and escape double quotes by doubling them
            $tanggal = $d->tanggal ?: '';
            $jam_masuk = $d->jam_masuk ?: '';
            $jam_pulang = $d->jam_pulang ?: '';
            $status = $d->status ?: '';
            $keterangan = $d->keterangan ?: '';

            $tanggal = '"' . str_replace('"', '""', $tanggal) . '"';
            $jam_masuk = '"' . str_replace('"', '""', $jam_masuk) . '"';
            $jam_pulang = '"' . str_replace('"', '""', $jam_pulang) . '"';
            $status = '"' . str_replace('"', '""', $status) . '"';
            $keterangan = '"' . str_replace('"', '""', $keterangan) . '"';

            $csv .= "{$tanggal},{$jam_masuk},{$jam_pulang},{$status},{$keterangan}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=laporan-absensi.csv');
    }
}