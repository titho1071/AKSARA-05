<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
 
class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
 
    protected $fillable = [
        'kelas_id',
        'orang_tua_id',
        'nama',
        'nis',
        'nisn',
        'jenis_kelamin',
        'alamat',
        'tanggal_lahir',
        'status',
    ];

    /**
     * Get the kelas that owns the siswa.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    /**
     * Get all orang tua for this siswa (many-to-many).
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class, 'orang_tua_id', 'id_orang_tua');
    }
}