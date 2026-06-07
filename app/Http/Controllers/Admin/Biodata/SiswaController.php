<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Siswa::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('nama')->get();

        return view('Dashboard_Admin.Biodata.biodata-siswa', compact('siswas', 'search'));
    }

    public function create()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('Dashboard_Admin.Biodata.biodata-siswa-create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'nis'           => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nisn'          => ['required', 'string', 'max:20', 'unique:siswa,nisn'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id'      => ['nullable', 'exists:kelas,id_kelas'],
            'alamat'        => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status'        => ['nullable', 'string', 'in:aktif,tidak_aktif'],
        ]);

        Siswa::create([
            'nama'          => $request->nama,
            'nis'           => $request->nis,
            'nisn'          => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id'      => $request->kelas_id ?: null,
            'alamat'        => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status'        => $request->status ?? 'aktif',
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        return view('Dashboard_Admin.Biodata.biodata-siswa-edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'nis'           => ['required', 'string', 'max:20', 'unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa'],
            'nisn'          => ['required', 'string', 'max:20', 'unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id'      => ['nullable', 'exists:kelas,id_kelas'],
            'alamat'        => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status'        => ['nullable', 'string', 'in:aktif,tidak_aktif'],
        ]);

        $siswa->update([
            'nama'          => $request->nama,
            'nis'           => $request->nis,
            'nisn'          => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas_id'      => $request->kelas_id ?: null,
            'alamat'        => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status'        => $request->status ?? 'aktif',
        ]);

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
{
    $siswa = Siswa::findOrFail($id);
    $siswa->delete();

    return response()->json([
        'success' => true,
        'message' => 'Data siswa berhasil dihapus.'
    ]);
}
}