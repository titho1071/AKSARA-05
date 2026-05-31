<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'hari',
        'status_kehadiran',
        'keterangan'
    ];

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id',
            'id_siswa'
        );
    }
}
