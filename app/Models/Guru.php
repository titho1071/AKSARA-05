<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'nuptk',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'status',
    ];

    /**
     * Get the kelas this guru teaches (wali kelas).
     */
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'guru_id', 'id_guru');
    }

    /**
     * Get the user associated with this guru.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
