<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\TahunPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalPelajaranController extends Controller
{
    // ── Helper: ambil tapel aktif ───────────────────────────
    private function getTapelAktif()
    {
        $tapel = TahunPelajaran::where('is_active', 1)->first();

        if (!$tapel) {
            abort(422, 'Tidak ada tahun pelajaran aktif. Aktifkan terlebih dahulu.');
        }

        return $tapel;
    }

    public function index(Request $request)
    {
        $tapel = $this->getTapelAktif();

        $query = JadwalPelajaran::with(['mataPelajaran', 'jamPelajaran', 'kelas', 'guru'])
            ->where('id_tapel', $tapel->id_tapel);

        if ($request->has('hari'))     $query->where('hari', $request->hari);
        if ($request->has('id_mapel')) $query->where('id_mapel', $request->id_mapel);
        if ($request->has('kelas_id')) $query->where('kelas_id', $request->kelas_id);

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal pelajaran berhasil diambil',
            'data'    => $query->get(),
            'tapel'   => $tapel,
        ]);
    }

    public function store(Request $request)
    {
        $tapel = $this->getTapelAktif();

        $validator = Validator::make($request->all(), [
            'hari'           => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_id'         => 'required|exists:jam_pelajaran,id_jam',
            'kelas_id'       => 'nullable|exists:kelas,id_kelas',
            'id_mapel'       => 'nullable|exists:mata_pelajaran,id_mapel',
            'nama_kegiatan'  => 'nullable|string|max:255',
            'id_guru'        => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        if (!$request->id_mapel && !$request->nama_kegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['id_mapel' => ['Pilih mata pelajaran atau isi kegiatan.']]
            ], 422);
        }

        // Cegah duplikat slot yang sama dalam tapel yang sama
        $exists = JadwalPelajaran::where([
            'id_tapel' => $tapel->id_tapel,
            'hari'     => $request->hari,
            'jam_id'   => $request->jam_id,
            'kelas_id' => $request->kelas_id,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['jam_id' => ['Slot jadwal ini sudah terisi untuk kelas dan hari yang dipilih.']]
            ], 422);
        }

        $jadwal = JadwalPelajaran::create([
            'id_tapel'      => $tapel->id_tapel,
            'hari'          => $request->hari,
            'jam_id'        => $request->jam_id,
            'kelas_id'      => $request->kelas_id      ?: null,
            'id_mapel'      => $request->id_mapel      ?: null,
            'nama_kegiatan' => $request->nama_kegiatan ?: null,
            'id_guru'       => $request->id_guru       ?: null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pelajaran berhasil ditambahkan',
            'data'    => $jadwal->load(['mataPelajaran', 'jamPelajaran', 'kelas', 'guru'])
        ], 201);
    }

    public function show($id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'jamPelajaran', 'kelas', 'guru'])->find($id);

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
            'hari'          => 'sometimes|required|string|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_id'        => 'sometimes|required|exists:jam_pelajaran,id_jam',
            'kelas_id'      => 'nullable|exists:kelas,id_kelas',
            'id_mapel'      => 'nullable|exists:mata_pelajaran,id_mapel',
            'nama_kegiatan' => 'nullable|string|max:255',
            'id_guru'       => 'nullable|exists:guru,id_guru',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $newMapel    = $request->has('id_mapel')       ? ($request->id_mapel      ?: null) : $jadwal->id_mapel;
        $newKegiatan = $request->has('nama_kegiatan')  ? ($request->nama_kegiatan ?: null) : $jadwal->nama_kegiatan;

        if (!$newMapel && !$newKegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['id_mapel' => ['Pilih mata pelajaran atau isi kegiatan.']]
            ], 422);
        }

        $jadwal->update([
            'hari'          => $request->hari     ?? $jadwal->hari,
            'jam_id'        => $request->jam_id   ?? $jadwal->jam_id,
            'kelas_id'      => $request->kelas_id ?? $jadwal->kelas_id,
            'id_mapel'      => $newMapel,
            'nama_kegiatan' => $newKegiatan,
            'id_guru'       => $request->has('id_guru') ? ($request->id_guru ?: null) : $jadwal->id_guru,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diupdate',
            'data'    => $jadwal->load(['mataPelajaran', 'jamPelajaran', 'kelas', 'guru'])
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
        $tapel    = $this->getTapelAktif();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $jadwalGrouped = [];
        foreach ($hariList as $hari) {
            $jadwalGrouped[$hari] = JadwalPelajaran::with([
                'mataPelajaran', 'jamPelajaran', 'kelas', 'guru'
            ])
                ->where('id_tapel', $tapel->id_tapel)
                ->where('hari', $hari)
                ->orderBy('jam_id')
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal per hari berhasil diambil',
            'data'    => $jadwalGrouped,
            'tapel'   => $tapel,
        ]);
    }

    public function listGuru()
    {
        $guru = Guru::where('status', 'aktif')
            ->orderBy('nama')
            ->get(['id_guru', 'nama', 'nip']);

        return response()->json(['success' => true, 'data' => $guru]);
    }
}