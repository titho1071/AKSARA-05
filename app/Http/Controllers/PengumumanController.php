<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Pengumuman::with('kelas');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kelas_id', 'like', "%{$search}%");
            })->orWhereHas('kelas', function ($q) use ($search) {
                $q->where('nama_kelas', 'like', "%{$search}%");
            });
        }

        $kelasId = $request->query('kelas_id');
        if ($kelasId !== null) {
            $query->where(function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId)->orWhereNull('kelas_id');
            });
        }

        $pengumuman = $query->orderByDesc('created_at')->get()->map(function (Pengumuman $pengumuman) {
            return [
                'id_pengumuman' => $pengumuman->id_pengumuman,
                'judul' => $pengumuman->judul,
                'deskripsi' => $pengumuman->deskripsi,
                'kelas_id' => $pengumuman->kelas_id,
                'kelas_nama' => $pengumuman->kelas ? $pengumuman->kelas->nama_kelas : 'Semua Kelas',
                'tanggal_mulai' => optional($pengumuman->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => optional($pengumuman->tanggal_selesai)->format('Y-m-d'),
                'file' => $pengumuman->file,
                'nama_file' => $pengumuman->display_file_name,
                'file_url' => $pengumuman->file ? route('pengumuman.file', $pengumuman->id_pengumuman) : null,
                'created_at' => $pengumuman->created_at->toDateTimeString(),
                'created_at_human' => $pengumuman->created_at->diffForHumans(),
                'updated_at' => $pengumuman->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $pengumuman,
        ]);
    }

    public function show(Pengumuman $pengumuman)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id_pengumuman' => $pengumuman->id_pengumuman,
                'judul' => $pengumuman->judul,
                'deskripsi' => $pengumuman->deskripsi,
                'kelas_id' => $pengumuman->kelas_id,
                'kelas_nama' => $pengumuman->kelas ? $pengumuman->kelas->nama_kelas : 'Semua Kelas',
                'tanggal_mulai' => optional($pengumuman->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => optional($pengumuman->tanggal_selesai)->format('Y-m-d'),
                'file' => $pengumuman->file,
                'nama_file' => $pengumuman->display_file_name,
                'file_url' => $pengumuman->file ? route('pengumuman.file', $pengumuman->id_pengumuman) : null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        $this->handleFileUpload($request, $validated);

        $pengumuman = Pengumuman::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dibuat',
            'data' => $pengumuman,
        ], 201);
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('file')) {
            if ($pengumuman->file && Storage::disk('public')->exists($pengumuman->file)) {
                Storage::disk('public')->delete($pengumuman->file);
            }

            $this->handleFileUpload($request, $validated);
        } else {
            unset($validated['file'], $validated['nama_file']);
        }

        $pengumuman->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil diperbarui',
            'data' => $pengumuman,
        ]);
    }

    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file && Storage::disk('public')->exists($pengumuman->file)) {
            Storage::disk('public')->delete($pengumuman->file);
        }

        $pengumuman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengumuman berhasil dihapus',
        ]);
    }

    protected function validateRequest(Request $request, ?int $id = null): array
    {
        if ($request->input('kelas_id') === '') {
            $request->merge(['kelas_id' => null]);
        }

        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kelas_id' => ['nullable', 'integer'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,pdf', 'max:2048'],
            'nama_file' => ['nullable', 'string', 'max:255'],
        ]);
    }

    protected function handleFileUpload(Request $request, array &$validated): void
    {
        if (!$request->hasFile('file')) {
            unset($validated['file']);

            return;
        }

        $file = $request->file('file');
        $validated['file'] = $file->store('pengumuman', 'public');
        $validated['nama_file'] = $request->input('nama_file') ?: $file->getClientOriginalName();
    }
    public function create()
    {
        $kelas = Kelas::all();

        return view('Dashboard_Admin.Pengumuman.pengumuman-tambah', compact('kelas'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        $kelas = Kelas::all();

        return view('Dashboard_Admin.Pengumuman.pengumuman-edit', compact('pengumuman', 'kelas'));
    }

    public function detail(Pengumuman $pengumuman)
    {
        return view('Dashboard_Admin.Pengumuman.pengumuman-detail', compact('pengumuman'));
    }

    public function file(Pengumuman $pengumuman)
    {
        if (!$pengumuman->file || !Storage::disk('public')->exists($pengumuman->file)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download(
            Storage::disk('public')->path($pengumuman->file),
            $pengumuman->display_file_name,
            ['Content-Type' => Storage::disk('public')->mimeType($pengumuman->file) ?: 'application/octet-stream'],
            'inline'
        );
    }
}
