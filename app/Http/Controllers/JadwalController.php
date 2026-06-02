<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin' && !$request->filled('user_id')) {
            $users = User::orderBy('name')->get();
            $users = $users->map(function ($u) {
                $u->jadwals_count = $u->jadwals()->count();
                return $u;
            });

            return view('jadwal.index', compact('users'));
        }

        $query = Jadwal::with('user');

        if ($user->role === 'admin' && $request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        } elseif ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $jadwals = $query->orderBy('tanggal', 'desc')->get();

        $selectedUser = null;
        if ($request->filled('user_id')) {
            $selectedUser = User::find($request->user_id);
        }

        return view('jadwal.index', compact('jadwals', 'selectedUser'));
    }

    public function create()
    {
        $users = User::all();
        return view('jadwal.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tanggal' => 'required',
            'shift' => 'required',
            'status' => 'required'
        ]);

        $shiftTimes = [
            'Pagi' => ['jam_masuk' => '06:00:00', 'jam_pulang' => '14:00:00'],
            'Siang' => ['jam_masuk' => '14:00:00', 'jam_pulang' => '22:00:00'],
            'Malam' => ['jam_masuk' => '22:00:00', 'jam_pulang' => '06:00:00'],
        ];

        $data = $request->all();
        if (isset($shiftTimes[$request->shift])) {
            $data['jam_masuk'] = $shiftTimes[$request->shift]['jam_masuk'];
            $data['jam_pulang'] = $shiftTimes[$request->shift]['jam_pulang'];
        }

        Jadwal::create($data);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $users = User::all();

        return view('jadwal.edit', compact('jadwal', 'users'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $shiftTimes = [
            'Pagi' => ['jam_masuk' => '06:00:00', 'jam_pulang' => '14:00:00'],
            'Siang' => ['jam_masuk' => '14:00:00', 'jam_pulang' => '22:00:00'],
            'Malam' => ['jam_masuk' => '22:00:00', 'jam_pulang' => '06:00:00'],
        ];

        $data = $request->all();
        if (isset($shiftTimes[$request->shift])) {
            $data['jam_masuk'] = $shiftTimes[$request->shift]['jam_masuk'];
            $data['jam_pulang'] = $shiftTimes[$request->shift]['jam_pulang'];
        }

        $jadwal->update($data);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();

        return back()->with('success', 'Jadwal dihapus');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ]);

        $userId = $request->filled('user_id') ? $request->user_id : auth()->id();

        if (auth()->user()->role !== 'admin' && $userId != auth()->id()) {
            return back()->with('error', 'Tidak diizinkan');
        }

        Excel::import(new JadwalImport($userId), $request->file('file'));

        return back()->with('success', 'Jadwal berhasil diimport');
    }
}