<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Collection;

class SmartMatchService
{
    /**
     * Calculate match score between user and post.
     * Algorithm: content-based filtering based on skill matching (KF-09).
     */
    public function calculateMatchScore(User $user, Post $post): float
    {
        // Get user's skills
        $userSkills = $user->skills->pluck('skill_id')->toArray();

        if (empty($userSkills)) {
            return 0;
        }

        // Get post's required skills with priority
        $postSkillReqs = $post->skillRequirements;

        if ($postSkillReqs->isEmpty()) {
            return 0;
        }

        // Calculate weighted score
        $totalWeight = 0;
        $matchedWeight = 0;

        foreach ($postSkillReqs as $skillReq) {
            $weight = $skillReq->prioritas === 'wajib' ? 2 : 1;
            $totalWeight += $weight;

            if (in_array($skillReq->skill_id, $userSkills)) {
                $matchedWeight += $weight;
            }
        }

        $skillScore = $totalWeight > 0 ? ($matchedWeight / $totalWeight) : 0;

        // Bonus if kategori lomba matches user's interests
        $bonusScore = 0;
        if ($user->profile && $user->profile->minat_bidang_lomba) {
            $userInterests = explode(',', strtolower($user->profile->minat_bidang_lomba));
            $postCategory = strtolower($post->kategori_lomba);

            foreach ($userInterests as $interest) {
                if (str_contains($postCategory, trim($interest)) || str_contains(trim($interest), $postCategory)) {
                    $bonusScore = 0.1;
                    break;
                }
            }
        }

        return min($skillScore + $bonusScore, 1.0);
    }

    /**
     * Get recommended posts for user (KF-09).
     */
    public function getRecommendedPosts(User $user, int $limit = 10): Collection
    {
        // Get all active posts (not full, not past deadline, not owned by user)
        $posts = Post::with(['ketua.profile', 'skillRequirements.skill'])
            ->where('status_postingan', 'aktif')
            ->whereRaw('jumlah_terisi < jumlah_dibutuhkan')
            ->where('deadline_daftar', '>=', now()->toDateString())
            ->where('ketua_id', '!=', $user->id)
            ->get();

        $recommendations = [];

        foreach ($posts as $post) {
            $score = $this->calculateMatchScore($user, $post);

            if ($score > 0) {
                $recommendations[] = [
                    'post' => $post,
                    'match_score' => round($score * 100, 2),
                ];
            }
        }

        // Sort by match score descending
        usort($recommendations, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return collect($recommendations)->take($limit);
    }

    /**
     * Get recommended candidates for post (reverse matching for Ketua Tim).
     */
    public function getRecommendedCandidates(Post $post, int $limit = 10): Collection
    {
        // Get users with Open To Join status
        $users = User::with(['profile', 'skills.skill'])
            ->where('role', 'mahasiswa')
            ->where('id', '!=', $post->ketua_id)
            ->whereHas('profile', function ($q) {
                $q->where('is_open_to_join', true);
            })
            ->get();

        $recommendations = [];

        foreach ($users as $user) {
            $score = $this->calculateMatchScore($user, $post);

            if ($score > 0) {
                $recommendations[] = [
                    'user' => $user,
                    'match_score' => round($score * 100, 2),
                ];
            }
        }

        // Sort by match score descending
        usort($recommendations, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return collect($recommendations)->take($limit);
    }
}
