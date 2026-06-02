<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'latitude',
        'longitude',
        'keterangan',
        'approval',
        'foto_masuk',
        'foto_pulang'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
