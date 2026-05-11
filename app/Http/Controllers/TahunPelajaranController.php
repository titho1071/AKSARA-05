<?php

namespace App\Http\Controllers;

use App\Models\TahunPelajaran;
use Illuminate\Http\Request;

class TahunPelajaranController extends Controller
{
    public function index()
    {
        $tahunPelajaran = TahunPelajaran::all();
        return response()->json($tahunPelajaran);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tapel' => 'required|string|unique:tahun_pelajaran,id_tapel',
            'semester' => 'required|string',
            'tahun_pelajaran' => 'required|string',
            'kelas_id' => 'nullable|integer',
        ]);

        $tahunPelajaran = TahunPelajaran::create($request->all());
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
            'kelas_id' => 'nullable|integer',
        ]);

        $tahunPelajaran->update($request->all());
        return response()->json($tahunPelajaran);
    }

    public function destroy($id)
    {
        $tahunPelajaran = TahunPelajaran::findOrFail($id);

        // Cek apakah ada kelas yang menggunakan tahun pelajaran ini
        $kelasCount = \DB::table('kelas')->where('tahun_pelajaran', $tahunPelajaran->tahun_pelajaran)->count();
        if ($kelasCount > 0) {
            return response()->json(['message' => 'Tidak dapat menghapus tahun pelajaran karena masih ada kelas yang terkait'], 400);
        }

        $tahunPelajaran->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
