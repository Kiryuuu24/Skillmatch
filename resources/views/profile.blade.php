@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] pb-24">
    <!-- Profile Header -->
    <div id="profile-header" class="bg-white border-b border-[#E5E7EB] px-4 pt-8 pb-6 text-center">
        <div class="w-20 h-20 mx-auto mb-3 bg-[#FF6B35]/10 rounded-full flex items-center justify-center text-[#FF6B35] font-bold text-3xl">
            <span id="profile-initial">L</span>
        </div>
        <h1 id="profile-name" class="text-[#1A1A2E] font-bold text-xl font-heading">
            Nama User
        </h1>
        <p id="profile-prodi" class="text-[#6B7280] text-sm mt-1">Prodi belum diisi</p>
        <p id="profile-npm" class="text-[#9CA3AF] text-xs mt-0.5">NPM belum diisi</p>
        <div class="mt-3">
            <span id="status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                <span class="w-1.5 h-1.5 bg-[#10B981] rounded-full mr-1.5 animate-pulse"></span>
                Open to Join
            </span>
        </div>
    </div>

    <div class="px-4 -mt-6 space-y-4">
        <!-- Loading skeleton -->
        <div id="profile-loading" class="space-y-4">
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-56 border border-[#E5E7EB] p-5"></div>
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-32 border border-[#E5E7EB] p-5"></div>
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-48 border border-[#E5E7EB] p-5"></div>
        </div>

        <!-- Profile Content (hidden until loaded) -->
        <div id="profile-content" class="hidden space-y-4">
            <!-- Action Buttons -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-4">
                <button onclick="window.location.href='/profile/edit'"
                    class="w-full py-3 bg-[#FF6B35] text-white rounded-[var(--radius-md)] font-semibold text-sm flex items-center justify-center gap-2 hover:bg-[#E55A2B] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l.041-.02a.75.75 0 011.063.862l-.708 2.836a.75.75 0 01-.536.535l-2.836.709a.75.75 0 01-.862-1.062l.02-.04z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.75 10.5h-2.25l-.001 3h-2.25l-.001-3h-2.25l-.001-3H6.75l-1.5 6v6.75a.75.75 0 00.75.75h13.5a.75.75 0 00.75-.75V16.5l-1.5-6z"></path>
                    </svg>
                    Edit Profil
                </button>
                <button onclick="window.location.href='/profile/settings'"
                    class="w-full mt-2 py-3 bg-[#F8FAFC] text-[#1A1A2E] rounded-[var(--radius-md)] font-semibold text-sm border border-[#D1D5DB] hover:bg-[#F1F5F9] transition">
                    Pengaturan & Privasi
                </button>
            </div>

            <!-- Links & Portfolio -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Link & Portfolio</h3>
                <div id="links-container" class="space-y-2.5">
                    <div class="flex items-center text-[#9CA3AF] text-sm">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"></path>
                        </svg>
                        <span>Belum ada link portfolio yang ditambahkan</span>
                    </div>
                </div>
            </div>

            <!-- Skills -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Skill yang Dikuasai</h3>
                <div id="profile-skills" class="flex flex-wrap gap-1.5">
                    <span class="text-[#9CA3AF] text-xs">Belum menambahkan skill</span>
                </div>
            </div>

            <!-- CV -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">CV</h3>
                <div id="cv-container">
                    <p class="text-[#9CA3AF] text-sm">CV belum diunggah</p>
                </div>
            </div>

            <!-- Menu Options -->
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm overflow-hidden">
                <button onclick="window.location.href='/profile/settings'"
                    class="w-full flex items-center justify-between p-4 hover:bg-[#F8FAFC] transition">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-2.707 2.37-4.254 1.832a1.724 1.724 0 00-2.572-1.065c-1.756-.426-2.924-1.756-1.756-3.35z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-[#1A1A2E] font-medium">Pengaturan & Privasi</span>
                    </div>
                    <svg class="w-5 h-5 text-[#D1D5DB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
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
if (!token) window.location.href = '/login';

async function loadProfile() {
    try {
        const response = await fetch('/api/profile', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        console.log('👤 Profile API Response:', data);

        if (data.success) {
            const profileUser = data.data.user;
            const profile = profileUser.profile;

            document.getElementById('profile-name').textContent = profileUser.name;
            document.getElementById('profile-initial').textContent = profileUser.name.charAt(0).toUpperCase();
            document.getElementById('profile-prodi').textContent =
                profile?.prodi || 'Prodi belum diisi';
            document.getElementById('profile-npm').textContent =
                profile?.npm ? `NPM: ${profile.npm}` : 'NPM belum diisi';

            // Status badge
            const isOpen = profile?.is_open_to_join;
            const statusBadge = document.getElementById('status-badge');
            statusBadge.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${isOpen ? 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20' : 'bg-[#9CA3AF]/10 text-[#9CA3AF] border border-[#9CA3AF]/20'}`;
            statusBadge.innerHTML = `
                <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${isOpen ? 'bg-[#10B981]' : 'bg-[#9CA3AF]'}"></span>
                ${isOpen ? 'Open to Join' : 'Tidak Mencari Tim'}
            `;

            // Links
            const linksContainer = document.getElementById('links-container');
            if (profile?.link_portofolio) {
                const iconMatch = profile.link_portofolio.includes('github')
                    ? '<svg class="w-5 h-5 mr-3 text-[#1A1A2E]" fill="currentColor" viewBox="0 0 24 24">...</svg>'
                    : '<svg class="w-5 h-5 mr-3 text-[#1A1A2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>';
                linksContainer.innerHTML = `
                    <a href="${profile.link_portofolio}" target="_blank"
                        class="flex items-center gap-2 text-[#1A1A2E] text-sm font-medium hover:text-[#FF6B35] transition">
                        ${iconMatch}
                        <span>Lihat Portfolio</span>
                    </a>
                `;
            }

            // Skills
            const skillsContainer = document.getElementById('profile-skills');
            if (profileUser.skills && profileUser.skills.length > 0) {
                skillsContainer.innerHTML = profileUser.skills.map(us => `
                    <span class="px-3 py-1 bg-[#F1F5F9] text-[#6B7280] text-xs font-medium rounded-full">
                        ${us.skill?.nama_skill || 'Skill'}
                    </span>
                `).join('');
            }

            // CV
            if (profile?.cv) {
                document.getElementById('cv-container').innerHTML = `
                    <div class="flex items-center gap-3 text-[#1A1A2E] text-sm font-medium">
                        <svg class="w-5 h-5 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <a href="/storage/${profile.cv}" target="_blank">Lihat CV</a>
                    </div>
                `;
            }

            // Show profile content
            document.getElementById('profile-loading').classList.add('hidden');
            document.getElementById('profile-content').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading profile:', error);
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

loadProfile();
</script>
@endsection