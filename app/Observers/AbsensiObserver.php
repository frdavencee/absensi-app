<?php

namespace App\Observers;

use App\Models\Absensi;
use App\Models\Jadwal;
use Carbon\Carbon;

class AbsensiObserver
{
    public function creating(Absensi $absensi): void
    {
        if (!$absensi->hari || !$absensi->jenis_hari || !$absensi->keterangan_absen) {
            $tanggal = Carbon::parse($absensi->tanggal);
            $namaHari = $tanggal->isoFormat('dddd');

            $jadwal = Jadwal::where('user_id', $absensi->user_id)
                ->whereDate('tanggal', $tanggal->toDateString())
                ->first();

            $isLibur = false;
            if ($jadwal) {
                $isLibur = $jadwal->status === 'Libur';
            } else {
                $isLibur = in_array($tanggal->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
            }

            $absensi->hari = $namaHari;
            $absensi->jenis_hari = $isLibur ? 'Libur' : 'Masuk';

            if (!$absensi->keterangan_absen) {
                if ($isLibur) {
                    $absensi->keterangan_absen = 'Libur';
                } elseif ($absensi->status === 'Izin') {
                    $absensi->keterangan_absen = 'Izin';
                } elseif ($absensi->status === 'Sakit') {
                    $absensi->keterangan_absen = 'Sakit';
                } elseif ($absensi->status === 'Hadir') {
                    $absensi->keterangan_absen = 'Hadir';
                } elseif ($absensi->status === 'Telat') {
                    $absensi->keterangan_absen = 'Telat';
                } else {
                    $absensi->keterangan_absen = 'Tidak Absen';
                }
            }
        }
    }

    public function created(Absensi $absensi): void
    {
        //
    }

    public function updated(Absensi $absensi): void
    {
        //
    }

    public function deleted(Absensi $absensi): void
    {
        //
    }

    public function restored(Absensi $absensi): void
    {
        //
    }

    public function forceDeleted(Absensi $absensi): void
    {
        //
    }
}
