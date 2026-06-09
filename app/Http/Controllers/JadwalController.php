<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role === 'admin' && !$request->filled('user_id')) {
            $users = User::orderBy('name')->get();
            $users = $users->map(function ($u) {
                $jadwals = $u->jadwals()->get();
                $u->jadwals_count = $jadwals->count();
                $u->kerja_count = $jadwals->where('status', 'Kerja')->count();
                $u->libur_count = $jadwals->where('status', 'Libur')->count();
                $u->total_jam_kerja = $u->kerja_count * 8;
                return $u;
            });

            return view('jadwal.index', compact('users'));
        }

        $query = Jadwal::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $jadwals = $query->orderBy('tanggal', 'asc')->get();

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
            'shift' => 'nullable',
            'status' => 'required'
        ]);

        $shiftTimes = [
            'Pagi' => ['jam_masuk' => '06:00:00', 'jam_pulang' => '14:00:00'],
            'Siang' => ['jam_masuk' => '14:00:00', 'jam_pulang' => '22:00:00'],
            'Malam' => ['jam_masuk' => '22:00:00', 'jam_pulang' => '06:00:00'],
        ];

        $data = $request->all();
        if ($request->status === 'Libur') {
            $data['shift'] = null;
            $data['jam_masuk'] = null;
            $data['jam_pulang'] = null;
        } elseif (isset($shiftTimes[$request->shift])) {
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
        if ($request->status === 'Libur') {
            $data['shift'] = null;
            $data['jam_masuk'] = null;
            $data['jam_pulang'] = null;
        } elseif (isset($shiftTimes[$request->shift])) {
            $data['jam_masuk'] = $shiftTimes[$request->shift]['jam_masuk'];
            $data['jam_pulang'] = $shiftTimes[$request->shift]['jam_pulang'];
        }

        $jadwal->update($data);

        return redirect()->route('jadwal.index', ['user_id' => $jadwal->user_id])
            ->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $userId = $jadwal->user_id;
        $jadwal->delete();

        return redirect()->route('jadwal.index', ['user_id' => $userId])
            ->with('success', 'Jadwal dihapus');
    }

    public function destroyAll($userId)
    {
        Jadwal::where('user_id', $userId)->delete();

        return redirect()->route('jadwal.index', ['user_id' => $userId])
            ->with('success', 'Semua jadwal karyawan berhasil dihapus');
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

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if ($request->hasFile('file') && !$file->isValid()) {
            return back()->with('error', 'File tidak valid: ' . $file->getErrorMessage());
        }

        if ($extension === 'csv') {
            $handle = fopen($file->getPathname(), 'r');
            if ($handle === false) {
                return back()->with('error', 'Gagal membuka file CSV');
            }
            $header = fgetcsv($handle);
            $header = array_map('trim', $header);
            $header = array_map('strtolower', $header);
            $dataRows = [];
            while (($row = fgetcsv($handle)) !== false) {
                $dataRows[] = array_combine($header, array_map('trim', $row));
            }
            fclose($handle);
        } elseif (class_exists('ZipArchive')) {
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                $header = array_map('trim', $rows[0]);
                $header = array_map('strtolower', $header);
                $dataRows = array_slice($rows, 1);
                foreach ($dataRows as $i => $row) {
                    $dataRows[$i] = array_combine($header, array_map('trim', $row)) ?: [];
                }
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
            }
        } else {
            return back()->with('error', 'Import file Excel tidak didukung. Extensi PHP Zip belum aktif. Silakan upload file CSV atau aktifkan ekstensi Zip di php.ini.');
        }

        if (empty($dataRows)) {
            return back()->with('error', 'File tidak memiliki data yang valid. Pastikan baris pertama adalah header.');
        }

        $imported = 0;
        $skipped = 0;
        $skippedReasons = [];
        foreach ($dataRows as $i => $row) {
            if (!is_array($row)) {
                $skipped++;
                $skippedReasons[] = "Baris " . ($i + 2) . ": bukan array";
                continue;
            }

            if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) {
                $skipped++;
                $skippedReasons[] = "Baris " . ($i + 2) . ": kosong";
                continue;
            }

            $tanggal = $row['tanggal'] ?? $row['date'] ?? null;
            if (!$tanggal) {
                foreach ($row as $k => $v) {
                    if (stripos($k, 'tanggal') !== false || stripos($k, 'date') !== false) {
                        $tanggal = $v;
                        break;
                    }
                }
            }
            $shift = $row['shift'] ?? 'Pagi';
            $status = $row['status'] ?? 'Kerja';

            if (!$tanggal || trim($tanggal) === '') {
                $skipped++;
                $skippedReasons[] = "Baris " . ($i + 2) . ": tanggal kosong";
                continue;
            }

            $parsed = false;
            $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y', 'Y/m/d', 'd-M-Y', 'd M Y'];
            foreach ($formats as $format) {
                $dt = \DateTime::createFromFormat($format, trim($tanggal));
                if ($dt && $dt->format($format) === trim($tanggal)) {
                    $tanggal = $dt->format('Y-m-d');
                    $parsed = true;
                    break;
                }
            }

            if (!$parsed) {
                $parsed = @strtotime($tanggal);
                if ($parsed) {
                    $tanggal = date('Y-m-d', $parsed);
                    $parsed = true;
                }
            }

            if (!$parsed) {
                $skipped++;
                $skippedReasons[] = "Baris " . ($i + 2) . ": tanggal '{$tanggal}' tidak dikenali";
                continue;
            }

            $shiftTimes = [
                'Pagi' => ['jam_masuk' => '06:00:00', 'jam_pulang' => '14:00:00'],
                'Siang' => ['jam_masuk' => '14:00:00', 'jam_pulang' => '22:00:00'],
                'Malam' => ['jam_masuk' => '22:00:00', 'jam_pulang' => '06:00:00'],
            ];

            $data = [
                'user_id' => $userId,
                'tanggal' => $tanggal,
                'shift' => $shift ?: null,
                'status' => $status,
            ];

            if (isset($shiftTimes[$shift])) {
                $data['jam_masuk'] = $shiftTimes[$shift]['jam_masuk'];
                $data['jam_pulang'] = $shiftTimes[$shift]['jam_pulang'];
            }

            Jadwal::create($data);
            $imported++;
        }

        $msg = "Import selesai: {$imported} data berhasil disimpan";
        if ($skipped > 0) {
            $msg .= ", {$skipped} dilewati";
            if (!empty($skippedReasons)) {
                $msg .= " (" . implode('; ', array_slice($skippedReasons, 0, 5)) . ")";
            }
        }

        return back()->with('success', $msg);
    }
}