<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    public function index()
{
    try {
        $kelas = DB::table('kelas')
            ->leftJoin('guru', 'kelas.guru_id', '=', 'guru.id_guru')
            ->leftJoin('tahun_pelajaran', 'kelas.tapel_id', '=', 'tahun_pelajaran.id_tapel')
            ->select(
                'kelas.*',
                'guru.nama as guru_nama',
                'tahun_pelajaran.tahun_pelajaran as tapel_nama'
            )
            ->get();

        return response()->json($kelas);

    } catch (\Exception $e) {

        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat' => 'required|integer|min:1',
            'tapel_id' => 'required|string|exists:tahun_pelajaran,id_tapel',
            'guru_id' => 'nullable|integer|exists:guru,id_guru',
        ]);

        $kelas = Kelas::create($request->only(['nama_kelas', 'tingkat', 'tapel_id', 'guru_id']));

        return response()->json($kelas, 201);
    }

    public function show($id)
    {
        return response()->json(Kelas::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
    'nama_kelas' => 'required|string|max:100',
    'tingkat' => 'required|integer|min:1',
    'tapel_id' => 'required|string|exists:tahun_pelajaran,id_tapel',
    'guru_id' => 'nullable|integer|exists:guru,id_guru',
]);

        $kelas->update($request->only(['nama_kelas', 'tingkat', 'tapel_id', 'guru_id']));

        return response()->json($kelas);
    }

    public function guruList()
    {
        $guru = DB::table('guru')->select('id_guru', 'nama')->get();
        return response()->json($guru);
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        $siswaCount = DB::table('siswa')->where('kelas_id', $kelas->id_kelas)->count();
        if ($siswaCount > 0) {
            return response()->json([
                'message' => 'Tidak dapat menghapus kelas karena masih ada siswa yang terdaftar.'
            ], 400);
        }

        $kelas->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
