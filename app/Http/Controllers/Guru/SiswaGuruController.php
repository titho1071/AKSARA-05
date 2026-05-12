<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaGuruController extends Controller
{
    /**
     * Display list of students in guru's class
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Get guru's class
            $guru = $user->guru;

            if (!$guru) {
                return view('Dashboard_Guru.Wali_Kelas.kelas', [
                    'siswas' => collect(),
                    'kelas' => null
                ]);
            }

            $kelas = Kelas::where('guru_id', $guru->id_guru)->first();
            
            if (!$kelas) {
                return view('Dashboard_Guru.Wali_Kelas.kelas', [
                    'siswas' => collect(),
                    'kelas' => null
                ]);
            }

            // Get all students in this class with their orang tua
            $siswas = Siswa::with('orangTua', 'kelas')
                ->where('kelas_id', $kelas->id_kelas)
                ->orderBy('nama')
                ->get();

            return view('Dashboard_Guru.Wali_Kelas.kelas', compact('siswas', 'kelas'));
        } catch (\Exception $e) {
            return view('Dashboard_Guru.Wali_Kelas.kelas', [
                'siswas' => collect(),
                'kelas' => null,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get detail of a student
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::with('orangTua', 'kelas')->findOrFail($id);
            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Show edit form for student
     */
    public function edit($id)
    {
        try {
            $siswa = Siswa::with('orangTua')->findOrFail($id);
            
            // Get all available orang tua
            $orangTuaList = OrangTua::orderBy('nama')->get();
            
            // Get current user's kelas
            $user = Auth::user();
            $guru = $user->guru;
            
            if (!$guru) {
                abort(403, 'Anda tidak memiliki profil guru');
            }
            
            $kelas = Kelas::where('guru_id', $guru->id_guru)->first();
            
            if (!$kelas) {
                abort(403, 'Anda tidak memiliki kelas');
            }
            
            // Verify siswa belongs to guru's class
            if ($siswa->kelas_id !== $kelas->id_kelas) {
                abort(403, 'Siswa tidak berada di kelas Anda');
            }

            return view('Dashboard_Guru.Wali_Kelas.siswa-edit', compact('siswa', 'orangTuaList', 'kelas'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update student data
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'nisn' => 'required|string|max:50',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'orang_tua_id' => 'nullable|exists:orang_tua,id_orang_tua',
        ]);

        $siswa->update([
            'nama' => $request->nama,
            'nis' => $request->nis,
            'nisn' => $request->nisn,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'orang_tua_id' => $request->orang_tua_id,
        ]);

        return redirect()
            ->route('guru.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }
}
