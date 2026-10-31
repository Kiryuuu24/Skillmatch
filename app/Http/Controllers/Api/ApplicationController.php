<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    /**
     * Get user's applications (pelamar) or applications to user's posts (ketua).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $tab = $request->get('tab', 'menunggu');
        $role = $request->get('role', 'pelamar'); // pelamar or ketua

        if ($role === 'ketua') {
            // Ketua Tim: get applications to their posts (KF-08)
            $applications = Application::with(['pelamar.profile', 'pelamar.skills.skill', 'post'])
                ->whereHas('post', function ($q) use ($user) {
                    $q->where('ketua_id', $user->id);
                })
                ->where('status_pengajuan', $tab)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));
        } else {
            // Calon Anggota: get own applications
            $applications = Application::with(['post.ketua', 'post.skillRequirements.skill'])
                ->where('pelamar_id', $user->id)
                ->where('status_pengajuan', $tab)
                ->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));
        }

        if ($applications->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada pengajuan',
                'data' => $applications,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => $applications,
        ], 200);
    }

    /**
     * Submit application (KF-07).
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|exists:posts,id',
            'pesan_lamaran' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $post = Post::findOrFail($request->post_id);

        // Cannot apply to own post
        if ($post->ketua_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mendaftar ke postingan sendiri',
            ], 422);
        }

        // Cannot apply twice to same post
        $existingApplication = Application::where('post_id', $post->id)
            ->where('pelamar_id', $user->id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mendaftar ke postingan ini',
            ], 422);
        }

        // Check if post is open for applications
        if (! $post->isOpenForApplications()) {
            $message = 'Postingan tidak menerima pendaftaran';

            if ($post->isFull()) {
                $message = 'Kuota tim sudah penuh';
            } elseif ($post->isPastDeadline()) {
                $message = 'Tenggat pendaftaran sudah lewat';
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        $application = Application::create([
            'post_id' => $post->id,
            'pelamar_id' => $user->id,
            'pesan_lamaran' => $request->pesan_lamaran,
            'status_pengajuan' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil dikirim',
            'data' => [
                'application' => $application->load(['post.ketua']),
            ],
        ], 201);
    }

    /**
     * Show single application.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $application = Application::with(['post.ketua', 'post.skillRequirements.skill', 'pelamar.profile', 'pelamar.skills.skill'])
            ->find($id);

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan',
            ], 404);
        }

        $user = $request->user();

        // Only pelamar or ketua can view
        if ($application->pelamar_id !== $user->id && $application->post->ketua_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk melihat pengajuan ini',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'application' => $application,
            ],
        ], 200);
    }

    /**
     * Update application status (KF-08 - Ketua Tim accepts/rejects).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status_pengajuan' => 'required|in:diterima,ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $application = Application::with('post')->find($id);

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan',
            ], 404);
        }

        // Only the ketua can update application status
        if ($application->post->ketua_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah status pengajuan ini',
            ], 403);
        }

        // Only pending applications can be updated
        if (! $application->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan ini sudah diproses sebelumnya',
            ], 422);
        }

        // Use transaction for acceptance (to update jumlah_terisi)
        DB::transaction(function () use ($application, $request) {
            $application->status_pengajuan = $request->status_pengajuan;
            $application->save();

            if ($request->status_pengajuan === 'diterima') {
                $post = $application->post;
                $post->jumlah_terisi += 1;

                // Automatically set status to 'penuh' if quota is met
                if ($post->jumlah_terisi >= $post->jumlah_dibutuhkan) {
                    $post->status_postingan = 'penuh';
                }

                $post->save();
            }
        });

        $statusLabel = $request->status_pengajuan === 'diterima' ? 'diterima' : 'ditolak';

        return response()->json([
            'success' => true,
            'message' => "Pengajuan berhasil {$statusLabel}",
            'data' => [
                'application' => $application->fresh()->load(['post', 'pelamar']),
            ],
        ], 200);
    }

    /**
     * Delete/withdraw application (pelamar only, if still pending).
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $application = Application::find($id);

        if (! $application) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan',
            ], 404);
        }

        if ($application->pelamar_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk membatalkan pengajuan ini',
            ], 403);
        }

        if (! $application->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan yang sudah diproses tidak dapat dibatalkan',
            ], 422);
        }

        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan berhasil dibatalkan',
        ], 200);
    }
}
