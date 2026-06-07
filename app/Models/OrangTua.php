<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tua';
    protected $primaryKey = 'id_orang_tua';

    protected $fillable = [
        'user_id',
        'nama',
        'nik',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'status',
    ];

    /**
     * Get all siswa for this orang tua (many-to-many).
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'orang_tua_id', 'id_orang_tua');
    }

    /**
     * Get the user associated with this orang tua.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
