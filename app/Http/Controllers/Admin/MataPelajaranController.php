<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\TahunPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of mata pelajaran.
     */
    public function index(Request $request)
    {
        $query = MataPelajaran::with('tahunPelajaran');

        // Filter by tahun pelajaran if provided
        if ($request->has('id_tapel')) {
            $query->where('id_tapel', $request->id_tapel);
        }

        $mataPelajaran = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran berhasil diambil',
            'data' => $mataPelajaran
        ], 200);
    }

    /**
     * Store a newly created mata pelajaran.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mapel' => 'required|string|max:255',
            'id_tapel' => 'required|exists:tahun_pelajaran,id_tapel',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mataPelajaran = MataPelajaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan',
            'data' => $mataPelajaran->load('tahunPelajaran')
        ], 201);
    }

    /**
     * Display the specified mata pelajaran.
     */
    public function show($id)
    {
        $mataPelajaran = MataPelajaran::with('tahunPelajaran')->find($id);

        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data mata pelajaran berhasil diambil',
            'data' => $mataPelajaran
        ], 200);
    }

    /**
     * Update the specified mata pelajaran.
     */
    public function update(Request $request, $id)
    {
        $mataPelajaran = MataPelajaran::find($id);

        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_mapel' => 'sometimes|required|string|max:255',
            'id_tapel' => 'sometimes|required|exists:tahun_pelajaran,id_tapel',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $mataPelajaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diupdate',
            'data' => $mataPelajaran->load('tahunPelajaran')
        ], 200);
    }

    /**
     * Remove the specified mata pelajaran.
     */
    public function destroy($id)
    {
        $mataPelajaran = MataPelajaran::find($id);

        if (!$mataPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran tidak ditemukan'
            ], 404);
        }

        $mataPelajaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus'
        ], 200);
    }
}
