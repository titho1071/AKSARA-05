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
}
