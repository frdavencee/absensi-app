<?php

namespace App\Imports;

use App\Models\Jadwal;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class JadwalImport implements OnEachRow, WithHeadingRow, WithValidation
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        if (empty($row['tanggal']) && empty($row['date'])) {
            return;
        }

        $tanggal = $row['tanggal'] ?? $row['date'] ?? null;
        $shift = $row['shift'] ?? 'Pagi';
        $status = $row['status'] ?? 'Kerja';

        if (!$tanggal) {
            return;
        }

        if (is_numeric($tanggal)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal);
            $tanggal = $tanggal->format('Y-m-d');
        } else {
            $formats = ['d/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y', 'Y-m-d'];
            $parsed = false;
            foreach ($formats as $fmt) {
                try {
                    $tanggal = \Carbon\Carbon::createFromFormat($fmt, $tanggal)->format('Y-m-d');
                    $parsed = true;
                    break;
                } catch (\Exception $e) {
                    continue;
                }
            }
            if (!$parsed) {
                try {
                    $tanggal = \Carbon\Carbon::parse($tanggal)->format('Y-m-d');
                } catch (\Exception $e) {
                    return;
                }
            }
        }

        $shiftTimes = [
            'Pagi' => ['jam_masuk' => '06:00:00', 'jam_pulang' => '14:00:00'],
            'Siang' => ['jam_masuk' => '14:00:00', 'jam_pulang' => '22:00:00'],
            'Malam' => ['jam_masuk' => '22:00:00', 'jam_pulang' => '06:00:00'],
        ];

        $data = [
            'user_id' => $this->userId,
            'tanggal' => $tanggal,
            'shift' => $shift,
            'status' => $status,
        ];

        if (isset($shiftTimes[$shift])) {
            $data['jam_masuk'] = $shiftTimes[$shift]['jam_masuk'];
            $data['jam_pulang'] = $shiftTimes[$shift]['jam_pulang'];
        }

        Jadwal::create($data);
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'shift' => 'required|in:Pagi,Siang,Malam',
            'status' => 'required|in:Kerja,Libur',
        ];
    }
}
