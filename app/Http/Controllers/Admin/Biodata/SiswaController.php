<?php

namespace App\Http\Controllers\Admin\Biodata;

use App\Imports\SiswaImport;
use App\Exports\TemplateExport;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:2048'],
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return redirect()->route('admin.siswa.index')
            ->with('success', 'Data siswa berhasil diimport.');
    }

    public function templateSiswa()
    {
        $headers = ['nama', 'nis', 'nisn', 'jenis_kelamin', 'nama_kelas', 'tanggal_lahir', 'alamat', 'status'];
        $contoh  = ['Ahmad Fauzi', '2024001', '0012345678', 'L', 'VII A', '2010-05-15', 'Jl. Merdeka No. 3', 'aktif'];

        return Excel::download(new TemplateExport($headers, $contoh), 'template-import-siswa.xlsx');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:2048'],
        ]);

        $sheets = Excel::toArray(null, $request->file('file'));
        $sheet = $sheets[0] ?? [];

        if (count($sheet) === 0) {
            return response()->json(['success' => false, 'message' => 'File kosong atau tidak dapat dibaca.']);
        }

        $headersRaw = $sheet[0];
        $headers = array_map(function ($h) {
            return strtolower(str_replace(' ', '_', trim((string)$h)));
        }, $headersRaw);

        $rows = [];
        $max = min(10, count($sheet) - 1);
        for ($i = 1; $i <= $max; $i++) {
            $row = $sheet[$i];
            $assoc = [];
            foreach ($headers as $idx => $key) {
                $assoc[$key] = $row[$idx] ?? null;
            }

            $warnings = [];
            if (!empty($assoc['nis']) && Siswa::where('nis', $assoc['nis'])->exists()) {
                $warnings[] = 'NIS sudah terdaftar';
            }
            if (!empty($assoc['nisn']) && Siswa::where('nisn', $assoc['nisn'])->exists()) {
                $warnings[] = 'NISN sudah terdaftar';
            }

            $status = strtolower(trim((string)($assoc['status'] ?? '')));
            $nonaktifValues = ['tidak aktif', 'tidak_aktif', 'nonaktif', 'non-active', 'no', 'tidak'];
            if ($status !== '' && in_array($status, $nonaktifValues, true)) {
                $warnings[] = 'Status terdeteksi non-aktif';
            }

            $gender = strtolower(trim((string)($assoc['jenis_kelamin'] ?? '')));
            $male = ['l', 'laki', 'laki-laki', 'laki laki', 'male'];
            $female = ['p', 'perempuan', 'wanita', 'female'];
            if ($gender !== '' && !in_array($gender, array_merge($male, $female), true)) {
                $warnings[] = 'Format jenis_kelamin tidak dikenali';
            }

            $rows[] = ['data' => $assoc, 'warnings' => $warnings];
        }

        return response()->json(['success' => true, 'headers' => $headers, 'rows' => $rows]);
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