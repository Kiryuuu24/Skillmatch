@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] pb-24">
    <!-- Header -->
    <div class="bg-white border-b border-[#E5E7EB] px-4 py-3 flex items-center sticky top-0 z-10">
        <button onclick="history.back()" class="mr-3 -ml-1">
            <svg class="w-6 h-6 text-[#1A1A2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <h1 class="text-[#1A1A2E] font-semibold text-lg">Profil</h1>
    </div>

    <!-- Loading -->
    <div id="loading" class="px-4 pt-8 pb-6 text-center space-y-4">
        <div class="animate-pulse bg-white rounded-full w-20 h-20 mx-auto flex items-center justify-center text-[#FF6B35] font-bold text-3xl">
            <span>L</span>
        </div>
        <h1 id="profile-name-loading" class="text-[#1A1A2E] font-bold text-xl font-heading animate-pulse">Loading...</h1>
        <p class="text-[#6B7280] text-sm animate-pulse">Memuat profil...</p>
    </div>

    <!-- Profile Content -->
    <div id="profile-content" class="hidden">
        <!-- Header -->
        <div class="bg-white border-b border-[#E5E7EB] px-4 pt-8 pb-6 text-center">
            <div class="w-20 h-20 mx-auto mb-3 bg-[#FF6B35]/10 rounded-full flex items-center justify-center text-[#FF6B35] font-bold text-3xl">
                <span id="profile-view-initial">L</span>
            </div>
            <h1 id="profile-view-name" class="text-[#1A1A2E] font-bold text-xl font-heading">Nama User</h1>
            <p id="profile-view-prodi" class="text-[#6B7280] text-sm mt-1">Prodi belum diisi</p>
            <p id="profile-view-npm" class="text-[#9CA3AF] text-xs mt-0.5">NPM belum diisi</p>
            <div class="mt-3">
                <span id="profile-view-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                    <span class="w-1.5 h-1.5 bg-[#10B981] rounded-full mr-1.5 animate-pulse"></span>
                    Open to Join
                </span>
            </div>
        </div>

        <div class="px-4 space-y-4">
            <!-- Skills -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Skill yang Dikuasai</h3>
                <div id="profile-view-skills" class="flex flex-wrap gap-1.5">
                    <span class="text-[#9CA3AF] text-xs">Belum ada skill</span>
                </div>
            </div>

            <!-- Menu -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm overflow-hidden mb-4">
                <button onclick="logout()"
                    class="w-full flex items-center justify-between p-4 hover:bg-[#FEF2F2] transition border-t border-[#E5E7EB]">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-[#EF4444] font-medium">Keluar</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

@include('components.bottom-nav', ['active' => 'profile'])

<script>
const token = localStorage.getItem('token');
const userId = {{ $userId ?? 'null' }};

if (!token) {
    window.location.href = '/login';
}

async function loadProfileView() {
    try {
        const response = await fetch(`/api/profile/${userId}`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success) {
            const profileUser = data.data.user;
            const profile = profileUser.profile;

            document.getElementById('profile-view-name').textContent = profileUser.name;
            document.getElementById('profile-view-initial').textContent = profileUser.name.charAt(0).toUpperCase();
            document.getElementById('profile-view-prodi').textContent =
                profile?.prodi || 'Prodi belum diisi';
            document.getElementById('profile-view-npm').textContent =
                profile?.npm ? `NPM: ${profile.npm}` : 'NPM belum diisi';

            // Status
            const isOpen = profile?.is_open_to_join;
            const statusBadge = document.getElementById('profile-view-status-badge');
            statusBadge.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${isOpen ? 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20' : 'bg-[#9CA3AF]/10 text-[#9CA3AF] border border-[#9CA3AF]/20'}`;
            statusBadge.innerHTML = `
                <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${isOpen ? 'bg-[#10B981]' : 'bg-[#9CA3AF]'}"></span>
                ${isOpen ? 'Open to Join' : 'Tidak Mencari Tim'}
            `;

            // Skills
            const skillsContainer = document.getElementById('profile-view-skills');
            if (profileUser.skills && profileUser.skills.length > 0) {
                skillsContainer.innerHTML = profileUser.skills.map(us => `
                    <span class="px-3 py-1 bg-[#F1F5F9] text-[#6B7280] text-xs font-medium rounded-full">
                        ${us.skill?.nama_skill || 'Skill'}
                    </span>
                `).join('');
            }

            document.getElementById('loading').classList.add('hidden');
            document.getElementById('profile-content').classList.remove('hidden');
        } else {
            alert('Gagal memuat profil');
            history.back();
        }
    } catch (error) {
        console.error('Error loading profile view:', error);
        alert('Terjadi kesalahan');
        history.back();
    }
}

async function logout() {
    if (!confirm('Yakin ingin keluar?')) return;

    try {
        await fetch('/api/auth/logout', {
            method: 'POST',
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
    } catch (e) {
        console.error('Logout error:', e);
    }

    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.href = '/login';
}

loadProfileView();
</script>
@endsection