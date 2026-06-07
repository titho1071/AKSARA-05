<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiodataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $this->requireAdmin($request);

        $siswas = Siswa::with(['kelas', 'orangTua'])
            ->orderBy('nama')
            ->get();

        return response()->json(['status' => 'success', 'data' => $siswas]);
    }

    public function show(Request $request, $id)
    {
        $this->requireAdmin($request);

        $siswa = Siswa::with(['kelas', 'orangTua'])->find($id);
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Data siswa tidak ditemukan.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $siswa]);
    }

    public function store(Request $request)
    {
        $this->requireAdmin($request);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['nullable', 'exists:kelas,id_kelas'],
            'alamat' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
        ]);

        $siswa = Siswa::create([
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'kelas_id' => $validated['kelas_id'] ?: null,
            'alamat' => $validated['alamat'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'status' => $validated['status'] ?? 'aktif',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Siswa berhasil ditambahkan.', 'data' => $siswa], 201);
    }

    public function update(Request $request, $id)
    {
        $this->requireAdmin($request);

        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Data siswa tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis,' . $siswa->id_siswa . ',id_siswa'],
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'kelas_id' => ['nullable', 'exists:kelas,id_kelas'],
            'alamat' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:aktif,tidak_aktif'],
        ]);

        $siswa->update([
            'nama' => $validated['nama'],
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'kelas_id' => $validated['kelas_id'] ?: null,
            'alamat' => $validated['alamat'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'status' => $validated['status'] ?? 'aktif',
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data siswa berhasil diperbarui.', 'data' => $siswa]);
    }

    public function destroy(Request $request, $id)
    {
        $this->requireAdmin($request);

        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Data siswa tidak ditemukan.'], 404);
        }

        $siswa->delete();

        return response()->json(['status' => 'success', 'message' => 'Siswa berhasil dihapus.']);
    }

    private function requireAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role_id !== $this->adminRoleId()) {
            abort(response()->json(['status' => 'error', 'message' => 'Hanya admin yang dapat mengakses resource ini.'], 403));
        }
    }

    private function adminRoleId()
    {
        return DB::table('roles')->where('nama_role', 'admin')->value('id_role');
    }
}
