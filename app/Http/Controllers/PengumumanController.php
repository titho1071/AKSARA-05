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

        $query = Pengumuman::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kelas_id', 'like', "%{$search}%");
            });
        }

        $pengumuman = $query->orderByDesc('created_at')->get()->map(function (Pengumuman $pengumuman) {
            return [
                'id_pengumuman' => $pengumuman->id_pengumuman,
                'judul' => $pengumuman->judul,
                'deskripsi' => $pengumuman->deskripsi,
                'kelas_id' => $pengumuman->kelas_id,
                'tanggal_mulai' => optional($pengumuman->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => optional($pengumuman->tanggal_selesai)->format('Y-m-d'),
                'file' => $pengumuman->file,
                'file_url' => $pengumuman->file ? asset('storage/' . $pengumuman->file) : null,
                'created_at' => $pengumuman->created_at->toDateTimeString(),
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
                'tanggal_mulai' => optional($pengumuman->tanggal_mulai)->format('Y-m-d'),
                'tanggal_selesai' => optional($pengumuman->tanggal_selesai)->format('Y-m-d'),
                'file' => $pengumuman->file,
                'file_url' => $pengumuman->file ? asset('storage/' . $pengumuman->file) : null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('pengumuman', 'public');
        }

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

            $validated['file'] = $request->file('file')->store('pengumuman', 'public');
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
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'kelas_id' => ['nullable', 'integer'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,svg,pdf', 'max:10240'],
        ]);
    }
    public function create()
{
    $kelas = Kelas::all();

    return view('Dashboard_Admin.pengumuman-tambah', compact('kelas'));
}
}
