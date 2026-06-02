<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pengajuans = Absensi::with('user')
            ->whereIn('status', ['Izin', 'Sakit'])
            ->when($user->role !== 'admin', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('pengajuan.index', compact('pengajuans'));
    }

    public function approve($id)
    {
        $data = Absensi::findOrFail($id);

        $data->update([
            'status' => 'Approved'
        ]);

        return back()->with('success', 'Pengajuan disetujui');
    }
}