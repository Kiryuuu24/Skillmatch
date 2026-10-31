@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] pb-24">
    <!-- Status Bar -->
    <div class="flex items-center justify-between px-4 py-2 text-[#1A1A2E]">
        <span class="text-sm font-medium" id="status-time">08:34</span>
        <div class="flex items-center gap-1">
            <div class="flex items-end gap-0.5">
                <div class="w-0.5 h-2 bg-[#1A1A2E] rounded-sm"></div>
                <div class="w-0.5 h-2.5 bg-[#1A1A2E] rounded-sm"></div>
                <div class="w-0.5 h-3 bg-[#1A1A2E] rounded-sm"></div>
                <div class="w-0.5 h-3.5 bg-[#1A1A2E] rounded-sm opacity-30"></div>
            </div>
            <span class="text-xs font-semibold ml-1">4G</span>
            <div class="relative ml-2 w-6 h-3">
                <div class="absolute inset-0 border border-[#1A1A2E] rounded-[2px]"></div>
                <div class="absolute left-0.5 top-0.5 w-4 h-2 bg-[#FF6B35] rounded-[1px]"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="px-4 pt-4">
        <!-- Greeting -->
        <div class="mb-6">
            <h1 class="text-[#1A1A2E] text-2xl font-bold font-heading" id="user-greeting">Hi, User!</h1>
            <p class="text-[#6B7280] text-sm font-medium mt-1">Cari tim lomba untuk kompetisi favoritmu</p>
        </div>

        <!-- Quick Search -->
        <div class="relative mb-6">
            <input
                type="text"
                id="quick-search"
                placeholder="Cari tim, skill, atau lomba..."
                class="w-full pl-12 pr-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
            />
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>

        <!-- Smart Match Section -->
        <section class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-[#1A1A2E] font-semibold text-lg font-heading">Smart Match</h2>
                <a href="/cari" class="text-[#FF6B35] text-xs font-semibold">Lihat semua</a>
            </div>
            <div id="smart-match-card" class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-[var(--radius-lg)] p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#FF6B35] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.25 11.25l0 2.01m0 2.01l0 2.25m0-4.26l3.75-3.75M9 15l6.75-6.75M9 15c0 2.76-2.24 5-5 5s-5-2.24-5-5 2.24-5 5-5 5 2.24 5 5z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-[#1A1A2E] font-semibold text-sm mb-1">Rekomendasi Tim untukmu</p>
                        <p class="text-[#6B7280] text-xs" id="match-description">Cari tim dengan skill yang kamu kuasai</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Open Teams Section -->
        <section class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-[#1A1A2E] font-semibold text-lg font-heading">Tim Butuh Anggota</h2>
                <a href="/cari" class="text-[#FF6B35] text-xs font-semibold">Lihat semua</a>
            </div>

            <div id="team-cards" class="space-y-3">
                <!-- Skeleton Loader -->
                <div class="animate-pulse bg-[#FFFFFF] rounded-[var(--radius-lg)] h-48 border border-[#E5E7EB]"></div>
                <div class="animate-pulse bg-[#FFFFFF] rounded-[var(--radius-lg)] h-48 border border-[#E5E7EB]"></div>
                <div class="animate-pulse bg-[#FFFFFF] rounded-[var(--radius-lg)] h-48 border border-[#E5E7EB]"></div>
            </div>

            <!-- Empty state (will be shown if no teams) -->
            <div id="empty-state" class="hidden text-center py-12">
                <svg class="w-16 h-16 text-[#D1D5DB] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7-7-7 7M14 14l-3-3m0 0l3-3m-3 3h6a3 3 0 11-6 0z"></path>
                </svg>
                <p class="text-[#9CA3AF] text-sm">Belum ada tim yang membuka rekrutmen</p>
                <p class="text-[#9CA3AF] text-xs mt-1">Jadilah yang pertama buat postingan tim!</p>
            </div>
        </section>
    </main>
</div>

@include('components.bottom-nav', ['active' => 'beranda'])

<script>
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user') || '{}');

console.log('🔍 Debug User:', user);
console.log('🔑 Token:', token);

if (!token) {
    window.location.href = '/login';
}

// Format time
function updateTime() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('status-time').textContent = `${hours}:${minutes}`;
}
updateTime();
setInterval(updateTime, 60000);

// Set greeting
document.getElementById('user-greeting').textContent = `Hi, ${user.name?.split(' ')[0] || 'User'}!`;

// Load smart match
async function loadSmartMatch() {
    try {
        const response = await fetch('/api/smart-match/recommendations?limit=5', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success && data.data.count > 0) {
            document.getElementById('match-description').textContent =
                `${data.data.count} tim matching skill kamu`;
        }
    } catch (e) {
        console.error('Smart match error:', e);
    }
}

// Load open teams
async function loadTeams() {
    try {
        const response = await fetch('/api/posts?tersedia=1&per_page=6', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        console.log('📦 API Response:', data);
        console.log('📋 Posts data:', data.data?.data);

        const container = document.getElementById('team-cards');
        const emptyState = document.getElementById('empty-state');

        if (data.success && data.data.data && data.data.data.length > 0) {
            container.innerHTML = data.data.data.map(post => {
                const fillPct = (post.jumlah_terisi / post.jumlah_dibutuhkan) * 100;
                const skillName = post.skill_requirements?.[0]?.skill?.nama_skill || 'Skill';
                const deadline = new Date(post.deadline_daftar);
                const isAlmostDue = deadline.getTime() - Date.now() < 86400000;
                const formattedDate = deadline.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });

                return `
                    <div class="bg-[#FFFFFF] border border-[#E5E7EB] rounded-[var(--radius-lg)] p-4 shadow-sm hover:shadow-md transition-shadow cursor-pointer" onclick="window.location.href='/posts/${post.id}'">
                        <div class="flex items-start justify-between mb-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-semibold bg-[#FF6B35]/10 text-[#FF6B35] rounded-full">
                                ${post.kategori_lomba}
                            </span>
                            <span class="text-xs text-[#9CA3AF] font-medium">
                                ${formattedDate}
                            </span>
                        </div>
                        <h3 class="text-[#1A1A2E] font-semibold text-sm mb-1">
                            ${post.nama_lomba}
                        </h3>
                        <p class="text-[#6B7280] text-xs mb-3">
                            Dibutuhkan: ${skillName}${post.skill_requirements?.length > 1 ? ' +' + (post.skill_requirements.length - 1) + ' lainnya' : ''}
                        </p>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center gap-1">
                                <span class="text-xs text-[#9CA3AF] font-medium">
                                    ${post.jumlah_terisi}/${post.jumlah_dibutuhkan}
                                </span>
                                <span class="text-xs text-[#9CA3AF]">anggota</span>
                            </div>
                            ${isAlmostDue ? '<span class="text-xs text-[#EF4444] font-medium">Deadline dekat!</span>' : ''}
                        </div>
                        <div class="w-full h-2 bg-[#E5E7EB] rounded-full overflow-hidden mb-3">
                            <div class="h-full bg-[#FF6B35] rounded-full transition-all" style="width: ${fillPct}%"></div>
                        </div>
                        <button class="w-full py-2 text-xs font-semibold text-[#FF6B35] hover:text-[#E55A2B] transition-colors">
                            Lihat Detail
                        </button>
                    </div>
                `;
            }).join('');
            emptyState.classList.add('hidden');
        } else {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
        }
    } catch (e) {
        console.error('Load teams error:', e);
        const container = document.getElementById('team-cards');
        const emptyState = document.getElementById('empty-state');
        container.innerHTML = '';
        emptyState.classList.remove('hidden');
    }
}

loadSmartMatch();
loadTeams();
</script>
@endsection