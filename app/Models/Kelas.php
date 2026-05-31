<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tapel_id',
        'guru_id',
    ];

    /**
     * Get all siswa in this kelas.
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Get the guru (wali kelas) for this kelas.
     */
    public function guru()
    {
        return $this->belongsTo(\App\Models\Guru::class, 'guru_id', 'id_guru');
    }

    /**
     * Get the tahun pelajaran for this kelas.
     */
    public function tahunPelajaran()
    {
        return $this->belongsTo(
            TahunPelajaran::class,
            'tapel_id',
            'id_tapel'
        );
    }
}
