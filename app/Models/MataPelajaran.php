<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mapel';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama_mapel',
        'id_tapel',
    ];

    /**
     * Get the tahun pelajaran for this mata pelajaran.
     */
    public function tahunPelajaran()
    {
        return $this->belongsTo(TahunPelajaran::class, 'id_tapel', 'id_tapel');
    }

    /**
     * Get all jadwal for this mata pelajaran.
     */
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_mapel', 'id_mapel');
    }
}
