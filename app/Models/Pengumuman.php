<?php

namespace App\Models;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $primaryKey = 'id_pengumuman';
    public $incrementing = true;

    protected $fillable = [
        'judul',
        'deskripsi',
        'kelas_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'file',
        'nama_file',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function getDisplayFileNameAttribute(): ?string
    {
        if ($this->nama_file) {
            return $this->nama_file;
        }

        if (!$this->file) {
            return null;
        }

        $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        return $ext ? "Lampiran.{$ext}" : 'Lampiran';
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file) {
            return null;
        }

        return route('pengumuman.file', $this->id_pengumuman);
    }

    public function getFilePreviewUrlAttribute(): ?string
    {
        if (!$this->file) {
            return null;
        }

        return asset('storage/' . $this->file);
    }
}
