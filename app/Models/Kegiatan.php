<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id_kegiatan';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'tanggal',
        'status',
    ];

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'id_kegiatan', 'id_kegiatan');
    }

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }
}