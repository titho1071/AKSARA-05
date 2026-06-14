<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JamPelajaranController extends Controller
{
    /**
     * Display a listing of jam pelajaran.
     */
    public function index()
    {
        $jamPelajaran = JamPelajaran::orderBy('jam_mulai', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jam pelajaran berhasil diambil',
            'data' => $jamPelajaran
        ], 200);
    }

    /**
     * Store a newly created jam pelajaran.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jamPelajaran = JamPelajaran::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil ditambahkan',
            'data' => $jamPelajaran
        ], 201);
    }

    /**
     * Display the specified jam pelajaran.
     */
    public function show($id)
    {
        $jamPelajaran = JamPelajaran::find($id);

        if (!$jamPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jam pelajaran tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data jam pelajaran berhasil diambil',
            'data' => $jamPelajaran
        ], 200);
    }

    /**
     * Update the specified jam pelajaran.
     */
    public function update(Request $request, $id)
    {
        $jamPelajaran = JamPelajaran::find($id);

        if (!$jamPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jam pelajaran tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jam_mulai' => 'sometimes|required|date_format:H:i',
            'jam_selesai' => 'sometimes|required|date_format:H:i|after:jam_mulai',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $jamPelajaran->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil diupdate',
            'data' => $jamPelajaran
        ], 200);
    }

    /**
     * Remove the specified jam pelajaran.
     */
    public function destroy($id)
    {
        $jamPelajaran = JamPelajaran::find($id);

        if (!$jamPelajaran) {
            return response()->json([
                'success' => false,
                'message' => 'Jam pelajaran tidak ditemukan'
            ], 404);
        }

        $jamPelajaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil dihapus'
        ], 200);
    }
}
