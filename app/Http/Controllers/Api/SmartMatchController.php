<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SmartMatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SmartMatchController extends Controller
{
    protected SmartMatchService $smartMatchService;

    public function __construct(SmartMatchService $smartMatchService)
    {
        $this->smartMatchService = $smartMatchService;
    }

    /**
     * Get recommended posts for current user (KF-09).
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);

        $recommendations = $this->smartMatchService->getRecommendedPosts($user, $limit);

        if ($recommendations->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada rekomendasi. Lengkapi profil dan skill Anda untuk mendapatkan rekomendasi.',
                'data' => [
                    'recommendations' => [],
                    'count' => 0,
                ],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'recommendations' => $recommendations,
                'count' => $recommendations->count(),
            ],
        ], 200);
    }

    /**
     * Get recommended candidates for a specific post (for Ketua Tim).
     */
    public function getCandidateRecommendations(Request $request, int $postId): JsonResponse
    {
        $post = Post::with(['skillRequirements.skill'])->find($postId);

        if (! $post) {
            return response()->json([
                'success' => false,
                'message' => 'Postingan tidak ditemukan',
            ], 404);
        }

        // Only ketua can get recommendations for their post
        if ($post->ketua_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke postingan ini',
            ], 403);
        }

        $limit = $request->get('limit', 10);
        $recommendations = $this->smartMatchService->getRecommendedCandidates($post, $limit);

        if ($recommendations->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Belum ada kandidat yang cocok',
                'data' => [
                    'recommendations' => [],
                    'count' => 0,
                ],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'recommendations' => $recommendations,
                'count' => $recommendations->count(),
            ],
        ], 200);
    }
}
