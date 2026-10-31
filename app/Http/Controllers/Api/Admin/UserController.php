<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get list of users (KF-10).
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('profile');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status_akun')) {
            $query->where('status_akun', $request->status_akun);
        }

        $users = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users,
        ], 200);
    }

    /**
     * Get single user detail (KF-10).
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with(['profile', 'skills.skill', 'posts', 'applications.post'])->find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    /**
     * Update user (KF-10).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,'.$id.'|alpha_dash',
            'email' => 'sometimes|email|max:255|unique:users,email,'.$id,
            'role' => 'sometimes|in:mahasiswa,admin',
            'status_akun' => 'sometimes|in:aktif,nonaktif',
            'password' => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $updateData = $request->only(['name', 'username', 'email', 'role', 'status_akun']);

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui',
            'data' => [
                'user' => $user->fresh(),
            ],
        ], 200);
    }

    /**
     * Deactivate user account (KF-10).
     */
    public function deactivate(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $user->status_akun = 'nonaktif';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil dinonaktifkan',
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    /**
     * Activate user account (KF-10).
     */
    public function activate(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        $user->status_akun = 'aktif';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Akun pengguna berhasil diaktifkan',
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    /**
     * Delete user (KF-10).
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan',
            ], 404);
        }

        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus',
        ], 200);
    }
}
