<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Kegiatan;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalPelajaran::with(['mataPelajaran.tahunPelajaran', 'jamPelajaran', 'kelas', 'kegiatan', 'guru']);

        if ($request->has('hari'))     $query->where('hari', $request->hari);
        if ($request->has('id_mapel')) $query->where('id_mapel', $request->id_mapel);
        if ($request->has('kelas_id')) $query->where('kelas_id', $request->kelas_id);

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal pelajaran berhasil diambil',
            'data'    => $query->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hari'        => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_id'      => 'required|exists:jam_pelajaran,id_jam',
            'kelas_id'    => 'nullable|exists:kelas,id_kelas',
            'id_mapel'    => 'nullable|exists:mata_pelajaran,id_mapel',
            'kegiatan_id' => 'nullable|exists:kegiatan,id_kegiatan',
            'id_guru'     => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Minimal salah satu harus diisi
        if (!$request->id_mapel && !$request->kegiatan_id) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['id_mapel' => ['Pilih mata pelajaran atau kegiatan.']]
            ], 422);
        }

        $jadwal = JadwalPelajaran::create([
            'hari'        => $request->hari,
            'jam_id'      => $request->jam_id,
            'kelas_id'    => $request->kelas_id,
            'id_mapel'    => $request->id_mapel ?: null,
            'kegiatan_id' => $request->kegiatan_id ?: null,
            'id_guru'     => $request->id_guru ?: null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil ditambahkan',
            'data'    => $jadwal->load(['mataPelajaran.tahunPelajaran', 'jamPelajaran', 'kelas', 'kegiatan', 'guru'])
        ], 201);
    }

    public function show($id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran.tahunPelajaran', 'kegiatan'])->find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalPelajaran::find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'hari'        => 'sometimes|required|string|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_id'      => 'sometimes|required|exists:jam_pelajaran,id_jam',
            'kelas_id'    => 'nullable|exists:kelas,id_kelas',
            'id_mapel'    => 'nullable|exists:mata_pelajaran,id_mapel',
            'kegiatan_id' => 'nullable|exists:kegiatan,id_kegiatan',
            'id_guru'     => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $newMapel    = $request->has('id_mapel')    ? ($request->id_mapel    ?: null) : $jadwal->id_mapel;
        $newKegiatan = $request->has('kegiatan_id') ? ($request->kegiatan_id ?: null) : $jadwal->kegiatan_id;

        if (!$newMapel && !$newKegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['id_mapel' => ['Pilih mata pelajaran atau kegiatan.']]
            ], 422);
        }

        $newGuru = $request->has('id_guru') ? ($request->id_guru ?: null) : $jadwal->id_guru;

        $jadwal->update([
            'hari'        => $request->hari     ?? $jadwal->hari,
            'jam_id'      => $request->jam_id   ?? $jadwal->jam_id,
            'kelas_id'    => $request->kelas_id ?? $jadwal->kelas_id,
            'id_mapel'    => $newMapel,
            'kegiatan_id' => $newKegiatan,
            'id_guru'     => $newGuru,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diupdate',
            'data'    => $jadwal->load(['mataPelajaran.tahunPelajaran', 'jamPelajaran', 'kelas', 'kegiatan', 'guru'])
        ]);
    }

    public function destroy($id)
    {
        $jadwal = JadwalPelajaran::find($id);

        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        $jadwal->delete();

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus']);
    }

    public function getByHari()
    {
        $hariList      = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwalGrouped = [];

        foreach ($hariList as $hari) {
            $jadwalGrouped[$hari] = JadwalPelajaran::with([
                'mataPelajaran.tahunPelajaran', 'jamPelajaran', 'kelas', 'kegiatan'
            ])
                ->where('hari', $hari)
                ->orderBy('jam_id')
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal per hari berhasil diambil',
            'data'    => $jadwalGrouped
        ]);
    }

    /**
     * Endpoint: GET /api/kegiatan
     * Dipakai dropdown modal admin jadwal — hanya kegiatan aktif
     */

    public function listGuru()
    {
        $guru = Guru::where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id_guru', 'nama', 'nip']);

        return response()->json([
            'success' => true,
            'data'    => $guru,
        ]);
    }

    public function listKegiatan()
    {
        $kegiatan = Kegiatan::where('status', 'aktif')
            ->orderBy('tanggal', 'desc')
            ->get(['id_kegiatan', 'judul', 'tanggal', 'kelas_id']);

        return response()->json([
            'success' => true,
            'data'    => $kegiatan
        ]);
    }
}