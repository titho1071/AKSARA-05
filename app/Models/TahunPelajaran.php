<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunPelajaran extends Model
{
    protected $table = 'tahun_pelajaran';
    protected $primaryKey = 'id_tapel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_tapel',
        'semester',
        'tahun_pelajaran',
        'is_active'
    ];
}
