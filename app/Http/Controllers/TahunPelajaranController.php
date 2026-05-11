<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class TahunPelajaranController extends Controller
{
    public function index()
{
    $tahunPelajaran = \DB::table('tahun_pelajaran')
        ->leftJoin(
            'kelas',
            'tahun_pelajaran.id_tapel',
            '=',
            'kelas.tapel_id'
        )
        ->select(
            'tahun_pelajaran.id_tapel',
            'tahun_pelajaran.tahun_pelajaran',
            'tahun_pelajaran.semester',
            \DB::raw('COUNT(kelas.id_kelas) as jumlah_kelas')
        )
        ->groupBy(
            'tahun_pelajaran.id_tapel',
            'tahun_pelajaran.tahun_pelajaran',
            'tahun_pelajaran.semester'
        )
        ->get();

    return response()->json($tahunPelajaran);
}
    public function store(Request $request)
{
    $request->validate([
        'semester' => 'required|string',
        'tahun_pelajaran' => 'required|string',
    ]);

    // Ambil ID terakhir
    $lastData = TahunPelajaran::orderBy('id_tapel', 'desc')->first();

    if ($lastData) {

        $lastNumber = (int) substr($lastData->id_tapel, 2);
        $newNumber = $lastNumber + 1;

    } else {

        $newNumber = 1;
    }

    // Format: TP001
    $id_tapel = 'TP' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

    $tahunPelajaran = TahunPelajaran::create([
        'id_tapel' => $id_tapel,
        'semester' => $request->semester,
        'tahun_pelajaran' => $request->tahun_pelajaran,
    ]);

    return response()->json($tahunPelajaran, 201);
}

    public function show($id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);
        return response()->json($tahunPelajaran);
    }

    public function update(Request $request, $id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);

        $request->validate([
            'semester' => 'required|string',
            'tahun_pelajaran' => 'required|string',
        ]);

        $tahunPelajaran->update($request->all());
        return response()->json($tahunPelajaran);
    }

    public function destroy($id)
{
    $tahunPelajaran = TahunPelajaran::findOrFail($id);

    // Cek apakah masih dipakai di tabel kelas
    $kelasCount = \DB::table('kelas')
        ->where('tapel_id', $tahunPelajaran->id_tapel)
        ->count();

    if ($kelasCount > 0) {

        return response()->json([
            'message' => 'Tidak dapat menghapus tahun pelajaran karena masih digunakan oleh data kelas.'
        ], 400);
    }

    $tahunPelajaran->delete();

    return response()->json([
        'message' => 'Data berhasil dihapus'
    ]);
}
}
