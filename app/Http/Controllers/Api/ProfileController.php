<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Get user profile (KF-02).
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'skills.skill']);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    /**
     * Update user profile (KF-02).
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'npm' => 'nullable|string|max:20',
            'prodi' => 'nullable|string|max:255',
            'angkatan' => 'nullable|string|max:10',
            'pengalaman_lomba' => 'nullable|string',
            'minat_bidang_lomba' => 'nullable|string',
            'link_portofolio' => 'nullable|url',
            'link_sosmed' => 'nullable|url',
            'cv' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();
        $profile = $user->profile;

        if (! $profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profil tidak ditemukan',
            ], 404);
        }

        // Update user name if provided
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
        }

        // Handle CV upload
        if ($request->hasFile('cv')) {
            // Delete old CV if exists
            if ($profile->cv) {
                Storage::disk('public')->delete($profile->cv);
            }

            $cvPath = $request->file('cv')->store('cvs', 'public');
            $profile->cv = $cvPath;
        }

        // Update profile fields
        $profile->fill($request->only([
            'npm',
            'prodi',
            'angkatan',
            'pengalaman_lomba',
            'minat_bidang_lomba',
            'link_portofolio',
            'link_sosmed',
        ]));

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'user' => $user->fresh()->load('profile'),
            ],
        ], 200);
    }

    /**
     * Update user skills (KF-02).
     */
    public function updateSkills(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'skills' => 'required|array',
            'skills.*.skill_id' => 'required|exists:skills,id',
            'skills.*.tingkat_keahlian' => 'required|in:pemula,menengah,mahir',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        // Delete existing skills
        $user->skills()->delete();

        // Add new skills
        foreach ($request->skills as $skillData) {
            UserSkill::create([
                'user_id' => $user->id,
                'skill_id' => $skillData['skill_id'],
                'tingkat_keahlian' => $skillData['tingkat_keahlian'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Skill berhasil diperbarui',
            'data' => [
                'skills' => $user->fresh()->load('skills.skill')->skills,
            ],
        ], 200);
    }

    /**
     * Update status ketersediaan (KF-03).
     */
    public function updateStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'is_open_to_join' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $profile = $request->user()->profile;

        if (! $profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profil tidak ditemukan',
            ], 404);
        }

        $profile->is_open_to_join = $request->is_open_to_join;
        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Status ketersediaan berhasil diperbarui',
            'data' => [
                'profile' => $profile,
            ],
        ], 200);
    }

    /**
     * Update privacy settings (KF-02a).
     */
    public function updatePrivacy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'is_portfolio_public' => 'sometimes|boolean',
            'is_sosmed_public' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $profile = $request->user()->profile;

        if (! $profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profil tidak ditemukan',
            ], 404);
        }

        $profile->fill($request->only([
            'is_portfolio_public',
            'is_sosmed_public',
        ]));

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan privasi berhasil diperbarui',
            'data' => [
                'profile' => $profile,
            ],
        ], 200);
    }

    /**
     * Get all available skills.
     */
    public function getSkills(Request $request): JsonResponse
    {
        $kategori = $request->query('kategori');

        $query = Skill::query();

        if ($kategori) {
            $query->where('kategori_skill', $kategori);
        }

        $skills = $query->orderBy('kategori_skill')->orderBy('nama_skill')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'skills' => $skills,
            ],
        ], 200);
    }

    /**
     * Get public profile of another user.
     */
    public function show(Request $request, int $userId): JsonResponse
    {
        $user = User::with(['profile', 'skills.skill'])->find($userId);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $profile = $user->profile;

        // Apply privacy settings (hide portfolio/sosmed if private)
        if ($profile && ! $profile->is_portfolio_public) {
            $profile->link_portofolio = null;
            $profile->cv = null;
        }

        if ($profile && ! $profile->is_sosmed_public) {
            $profile->link_sosmed = null;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }
}
