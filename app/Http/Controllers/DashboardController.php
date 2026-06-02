<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $hariIni = Carbon::today();

        $jadwalHariIni = Jadwal::where('user_id', $user->id)
            ->whereDate('tanggal', $hariIni)
            ->first();

        $absensiHariIni = Absensi::where('user_id', $user->id)
            ->whereDate('tanggal', $hariIni)
            ->first();

        $total = Absensi::where('user_id', $user->id)->count();

        $hadir = Absensi::where('user_id', $user->id)
            ->where('status', 'Hadir')->count();

        $telat = Absensi::where('user_id', $user->id)
            ->where('status', 'Telat')->count();

        return view('dashboard', compact(
            'jadwalHariIni',
            'absensiHariIni',
            'total',
            'hadir',
            'telat'
        ));
    }
}