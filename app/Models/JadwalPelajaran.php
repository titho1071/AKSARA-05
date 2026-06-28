<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table      = 'jadwal_pelajaran';
    protected $primaryKey = 'id_jadwal';
    protected $keyType    = 'int';
    public $incrementing  = true;

    protected $fillable = [
        'hari',
        'id_tapel',
        'jam_id',
        'kelas_id',
        'id_mapel',
        'nama_kegiatan',
        'id_guru',
    ];

    public function tahunPelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class, 'id_tapel', 'id_tapel');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function jamPelajaran()
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_id', 'id_jam');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}