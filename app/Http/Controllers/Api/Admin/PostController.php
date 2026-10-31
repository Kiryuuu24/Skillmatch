<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Get all posts for admin (KF-11).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Post::with(['ketua.profile', 'skillRequirements.skill', 'applications']);

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lomba', 'like', "%{$search}%")
                    ->orWhere('kategori_lomba', 'like', "%{$search}%")
                    ->orWhereHas('ketua', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->has('status_postingan')) {
            $query->where('status_postingan', $request->status_postingan);
        }

        // Filter by kategori
        if ($request->has('kategori_lomba')) {
            $query->where('kategori_lomba', $request->kategori_lomba);
        }

        $posts = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $posts,
        ], 200);
    }

    /**
     * Get single post detail (KF-11).
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::with(['ketua.profile', 'skillRequirements.skill', 'applications.pelamar.profile'])->find($id);

        if (! $post) {
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
     * Update post (KF-11).
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

        $validator = Validator::make($request->all(), [
            'nama_lomba' => 'sometimes|string|max:255',
            'kategori_lomba' => 'sometimes|string|max:255',
            'jumlah_dibutuhkan' => 'sometimes|integer|min:1',
            'deadline_daftar' => 'sometimes|date',
            'link_sosmed' => 'sometimes|url',
            'deskripsi_syarat' => 'nullable|string',
            'status_postingan' => 'sometimes|in:aktif,penuh,ditutup,disembunyikan',
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
     * Hide post (KF-11).
     */
    public function hide(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        $post->status_postingan = 'disembunyikan';
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil disembunyikan',
            'data' => [
                'post' => $post,
            ],
        ], 200);
    }

    /**
     * Unhide post (KF-11).
     */
    public function unhide(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        $post->status_postingan = 'aktif';
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil ditampilkan kembali',
            'data' => [
                'post' => $post,
            ],
        ], 200);
    }

    /**
     * Delete post (KF-11).
     */
    public function destroy(int $id): JsonResponse
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Postingan berhasil dihapus',
        ], 200);
    }
}
