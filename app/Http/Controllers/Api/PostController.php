<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostSkillReq;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Get list of posts with filters (KF-06).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['ketua.profile', 'skillRequirements.skill', 'applications'])
            ->where('status_postingan', '!=', 'disembunyikan');

        // Search by keyword
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lomba', 'like', "%{$keyword}%")
                    ->orWhere('kategori_lomba', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi_syarat', 'like', "%{$keyword}%");
            });
        }

        // Filter by kategori lomba
        if ($request->has('kategori')) {
            $query->where('kategori_lomba', $request->kategori);
        }

        // Filter by skill required
        if ($request->has('skill_id')) {
            $query->whereHas('skillRequirements', function ($q) use ($request) {
                $q->where('skill_id', $request->skill_id);
            });
        }

        // Filter by availability (kuota)
        if ($request->has('tersedia') && $request->tersedia == true) {
            $query->where('status_postingan', 'aktif')
                ->whereRaw('jumlah_terisi < jumlah_dibutuhkan')
                ->where('deadline_daftar', '>=', now()->toDateString());
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $posts = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $posts,
        ], 200);
    }

    /**
     * Create new post (KF-04).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'required|string|max:255',
            'kategori_lomba' => 'required|string|max:255',
            'jumlah_dibutuhkan' => 'required|integer|min:1',
            'deadline_daftar' => 'required|date|after:today',
            'link_sosmed' => 'required|url',
            'deskripsi_syarat' => 'nullable|string',
            'skills' => 'required|array|min:1',
            'skills.*.skill_id' => 'required|exists:skills,id',
            'skills.*.prioritas' => 'required|in:wajib,opsional',
        ], [
            'nama_lomba.required' => 'Nama lomba wajib diisi',
            'kategori_lomba.required' => 'Kategori lomba wajib diisi',
            'jumlah_dibutuhkan.required' => 'Jumlah anggota yang dibutuhkan wajib diisi',
            'jumlah_dibutuhkan.min' => 'Jumlah anggota minimal 1',
            'deadline_daftar.required' => 'Tenggat pendaftaran wajib diisi',
            'deadline_daftar.after' => 'Tenggat pendaftaran harus setelah hari ini',
            'link_sosmed.required' => 'Link kontak wajib diisi',
            'link_sosmed.url' => 'Link kontak harus berupa URL yang valid',
            'skills.required' => 'Minimal 1 skill harus dipilih',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $post = Post::create([
            'ketua_id' => $request->user()->id,
            'nama_lomba' => $request->nama_lomba,
            'kategori_lomba' => $request->kategori_lomba,
            'jumlah_dibutuhkan' => $request->jumlah_dibutuhkan,
            'jumlah_terisi' => 0,
            'deadline_daftar' => $request->deadline_daftar,
            'link_sosmed' => $request->link_sosmed,
            'deskripsi_syarat' => $request->deskripsi_syarat,
            'status_postingan' => 'aktif',
        ]);

        // Add skill requirements
        foreach ($request->skills as $skill) {
            PostSkillReq::create([
                'post_id' => $post->id,
                'skill_id' => $skill['skill_id'],
                'prioritas' => $skill['prioritas'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dibuat dan dipublikasikan',
            'data' => [
                'post' => $post->load(['skillRequirements.skill', 'ketua']),
            ],
        ], 201);
    }

    /**
     * Get single post detail (KF-06).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $post = Post::with(['ketua.profile', 'skillRequirements.skill', 'applications'])
            ->find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        // Check if hidden and not owned by user
        if ($post->status_postingan === 'disembunyikan' && $post->ketua_id !== $request->user()?->id) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'post' => $post,
            ],
        ], 200);
    }

    /**
     * Update post (only by ketua).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        // Check ownership
        if ($post->ketua_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah postingan ini',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'sometimes|string|max:255',
            'kategori_lomba' => 'sometimes|string|max:255',
            'jumlah_dibutuhkan' => 'sometimes|integer|min:1',
            'deadline_daftar' => 'sometimes|date',
            'link_sosmed' => 'sometimes|url',
            'deskripsi_syarat' => 'nullable|string',
            'status_postingan' => 'sometimes|in:aktif,ditutup',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $post->update($request->only([
            'nama_lomba',
            'kategori_lomba',
            'jumlah_dibutuhkan',
            'deadline_daftar',
            'link_sosmed',
            'deskripsi_syarat',
            'status_postingan',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil diperbarui',
            'data' => [
                'post' => $post->fresh()->load(['skillRequirements.skill']),
            ],
        ], 200);
    }

    /**
     * Delete post (only by ketua).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        // Check ownership
        if ($post->ketua_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus postingan ini',
            ], 403);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dihapus',
        ], 200);
    }

    /**
     * Search candidates (KF-05 - untuk Ketua Tim).
     */
    public function searchCandidates(Request $request): JsonResponse
    {
        $query = User::with(['profile', 'skills.skill'])
            ->where('role', 'mahasiswa')
            ->whereHas('profile');

        // Filter by open to join status (default: true)
        if ($request->get('open_to_join', true)) {
            $query->whereHas('profile', function ($q) {
                $q->where('is_open_to_join', true);
            });
        }

        // Search by keyword (name, prodi)
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhereHas('profile', function ($pq) use ($keyword) {
                        $pq->where('prodi', 'like', "%{$keyword}%");
                    });
            });
        }

        // Filter by skill
        if ($request->has('skill_id')) {
            $query->whereHas('skills', function ($q) use ($request) {
                $q->where('skill_id', $request->skill_id);
            });
        }

        // Filter by prodi
        if ($request->has('prodi')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('prodi', $request->prodi);
            });
        }

        // Filter by angkatan
        if ($request->has('angkatan')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('angkatan', $request->angkatan);
            });
        }

        $candidates = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $candidates,
        ], 200);
    }
}
