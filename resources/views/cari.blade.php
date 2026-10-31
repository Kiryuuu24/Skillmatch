@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] pb-24">
    <div class="px-4 pt-4 pb-2">
        <!-- Header -->
        <h1 class="text-[#1A1A2E] text-xl font-bold font-heading mb-4">Cari Tim</h1>

        <!-- Tabs -->
        <div class="flex bg-[#E5E7EB] rounded-full p-1 mb-4">
            <button onclick="switchTab('tim')" id="tab-tim"
                class="flex-1 py-2.5 rounded-full font-semibold text-sm transition-all">
                Cari Tim
            </button>
            <button onclick="switchTab('kandidat')" id="tab-kandidat"
                class="flex-1 py-2.5 rounded-full font-semibold text-sm transition-all">
                Cari Kandidat
            </button>
        </div>

        <!-- Search -->
        <div class="relative mb-3">
            <input
                type="text"
                id="search-input"
                placeholder="Cari nama lomba, kompetisi, atau skill..."
                class="w-full pl-12 pr-12 py-3 rounded-[var(--radius-md)] border border-[#E5E7EB] bg-white text-base text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
            />
            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <button id="clear-btn" onclick="clearSearch()" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="w-5 h-5 text-[#9CA3AF] hover:text-[#1A1A2E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Active Filters Row -->
        <div class="flex gap-2 items-center mb-3">
            <button onclick="openFilterSheet()"
                class="flex-shrink-0 px-3 py-2 bg-white border border-[#E5E7EB] rounded-[var(--radius-md)] font-medium text-sm text-[#1A1A2E] hover:bg-[#F8FAFC] transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
            <div id="active-chips" class="flex gap-1.5 overflow-x-auto flex-1"></div>
        </div>
    </div>

    <!-- Results -->
    <div class="px-4">
        <!-- Loading -->
        <div id="loading" class="space-y-3">
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-32 border border-[#E5E7EB]"></div>
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-32 border border-[#E5E7EB]"></div>
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-32 border border-[#E5E7EB]"></div>
        </div>

        <!-- Results container -->
        <div id="results-container" class="space-y-3"></div>

        <!-- Empty state -->
        <div id="empty-state" class="hidden text-center py-12">
            <div class="w-16 h-16 bg-[#F1F5F9] rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <p class="text-[#1A1A2E] font-medium text-sm">Tidak ada hasil</p>
            <p class="text-[#9CA3AF] text-xs mt-1">Coba ubah kata kunci atau filter pencarian</p>
        </div>

        <!-- Error state -->
        <div id="error-state" class="hidden text-center py-12">
            <div class="w-16 h-16 bg-[#FEF2F2] rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-[#EF4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <p class="text-[#1A1A2E] font-medium text-sm">Gagal memuat data</p>
            <button onclick="loadResults()" class="mt-3 px-4 py-2 bg-[#FF6B35] text-white rounded-[var(--radius-md)] text-sm font-semibold hover:bg-[#E55A2B] transition">
                Coba Lagi
            </button>
        </div>
    </div>
</div>

<!-- Bottom Sheet Filter -->
<div id="filter-overlay" class="hidden fixed inset-0 bg-black/40 z-40" onclick="closeFilterSheet()"></div>
<div id="filter-sheet" class="fixed bottom-0 left-0 right-0 bg-white rounded-t-[var(--radius-lg)] z-50 transform translate-y-full transition-transform duration-300 max-h-[80vh] overflow-y-auto">
    <div class="sticky top-0 bg-white px-4 py-3 border-b border-[#E5E7EB] flex items-center justify-between">
        <h3 class="font-bold text-[#1A1A2E] font-heading">Filter</h3>
        <button onclick="closeFilterSheet()" class="p-1">
            <svg class="w-6 h-6 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <div class="p-4 space-y-5">
        <!-- Kategori -->
        <div>
            <label class="block text-sm font-medium text-[#1A1A2E] mb-2">Kategori Lomba</label>
            <select id="filter-kategori" class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30">
                <option value="">Semua Kategori</option>
            </select>
        </div>

        <!-- Status (tim only) -->
        <div id="status-filter-group">
            <label class="block text-sm font-medium text-[#1A1A2E] mb-2">Status Rekrutmen</label>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="filter-status" value="" checked class="text-[#FF6B35] focus:ring-[#FF6B35]">
                    <span class="text-sm text-[#1A1A2E]">Semua</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="filter-status" value="terbuka" class="text-[#FF6B35] focus:ring-[#FF6B35]">
                    <span class="text-sm text-[#1A1A2E]">Masih Dibuka</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="filter-status" value="hampir_penuh" class="text-[#FF6B35] focus:ring-[#FF6B35]">
                    <span class="text-sm text-[#1A1A2E]">Hampir Penuh</span>
                </label>
            </div>
        </div>

        <!-- Sort -->
        <div>
            <label class="block text-sm font-medium text-[#1A1A2E] mb-2">Urutkan</label>
            <select id="filter-sort" class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30">
                <option value="desc">Terbaru</option>
                <option value="asc">Terlama</option>
            </select>
        </div>

        <div class="flex gap-2 pt-2">
            <button onclick="resetFilters()"
                class="flex-1 py-3 bg-[#F8FAFC] text-[#1A1A2E] rounded-[var(--radius-md)] font-semibold text-sm border border-[#E5E7EB] hover:bg-[#F1F5F9] transition">
                Reset
            </button>
            <button onclick="applyFilters()"
                class="flex-1 py-3 bg-[#FF6B35] text-white rounded-[var(--radius-md)] font-semibold text-sm hover:bg-[#E55A2B] transition">
                Terapkan
            </button>
        </div>
    </div>
</div>

@include('components.bottom-nav', ['active' => 'cari'])

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

let currentTab = 'tim';
let allCategories = [];
let filters = { keyword: '', kategori: '', status: '', sort: 'desc' };

// === Tab switching ===
function switchTab(tab) {
    currentTab = tab;
    const tabTim = document.getElementById('tab-tim');
    const tabKandidat = document.getElementById('tab-kandidat');

    if (tab === 'tim') {
        tabTim.classList.add('bg-[#FF6B35]', 'text-white', 'shadow-sm');
        tabTim.classList.remove('text-[#6B7280]');
        tabKandidat.classList.remove('bg-[#FF6B35]', 'text-white', 'shadow-sm');
        tabKandidat.classList.add('text-[#6B7280]');
        document.getElementById('status-filter-group').classList.remove('hidden');
    } else {
        tabKandidat.classList.add('bg-[#FF6B35]', 'text-white', 'shadow-sm');
        tabKandidat.classList.remove('text-[#6B7280]');
        tabTim.classList.remove('bg-[#FF6B35]', 'text-white', 'shadow-sm');
        tabTim.classList.add('text-[#6B7280]');
        document.getElementById('status-filter-group').classList.add('hidden');
    }

    filters.kategori = '';
    filters.status = '';
    document.getElementById('search-input').value = '';
    document.getElementById('clear-btn').classList.add('hidden');
    loadResults();
}

// Init active tab
document.getElementById('tab-tim').classList.add('bg-[#FF6B35]', 'text-white', 'shadow-sm');
document.getElementById('tab-kandidat').classList.add('text-[#6B7280]');

// === Load categories for filter ===
async function loadCategories() {
    try {
        const response = await fetch('/api/skills', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success) {
            // Get unique kategori_lomba from posts instead
            const postsRes = await fetch('/api/posts?per_page=100', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const postsData = await postsRes.json();

            if (postsData.success) {
                const posts = postsData.data?.data || postsData.data || [];
                allCategories = [...new Set(posts.map(p => p.kategori_lomba).filter(Boolean))].sort();

                const select = document.getElementById('filter-kategori');
                select.innerHTML = '<option value="">Semua Kategori</option>';
                allCategories.forEach(cat => {
                    select.innerHTML += `<option value="${cat}">${cat}</option>`;
                });
            }
        }
    } catch (e) {
        console.error('Error loading categories:', e);
    }
}

// === Bottom sheet ===
function openFilterSheet() {
    document.getElementById('filter-overlay').classList.remove('hidden');
    document.getElementById('filter-sheet').classList.remove('translate-y-full');
    document.body.style.overflow = 'hidden';
}

function closeFilterSheet() {
    document.getElementById('filter-overlay').classList.add('hidden');
    document.getElementById('filter-sheet').classList.add('translate-y-full');
    document.body.style.overflow = '';
}

function applyFilters() {
    filters.kategori = document.getElementById('filter-kategori').value;
    filters.status = document.querySelector('input[name="filter-status"]:checked')?.value || '';
    filters.sort = document.getElementById('filter-sort').value;
    closeFilterSheet();
    loadResults();
}

function resetFilters() {
    document.getElementById('filter-kategori').value = '';
    document.querySelector('input[name="filter-status"][value=""]').checked = true;
    document.getElementById('filter-sort').value = 'desc';
    filters = { keyword: '', kategori: '', status: '', sort: 'desc' };
    document.getElementById('search-input').value = '';
    document.getElementById('clear-btn').classList.add('hidden');
    closeFilterSheet();
    loadResults();
}

// === Search ===
function clearSearch() {
    document.getElementById('search-input').value = '';
    document.getElementById('clear-btn').classList.add('hidden');
    filters.keyword = '';
    loadResults();
}

document.getElementById('search-input').addEventListener('input', (e) => {
    filters.keyword = e.target.value;
    const clearBtn = document.getElementById('clear-btn');
    if (e.target.value) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }

    clearTimeout(window.searchTimer);
    window.searchTimer = setTimeout(loadResults, 350);
});

// === Load results ===
async function loadResults() {
    const loading = document.getElementById('loading');
    const container = document.getElementById('results-container');
    const emptyState = document.getElementById('empty-state');
    const errorState = document.getElementById('error-state');

    loading.classList.remove('hidden');
    container.innerHTML = '';
    emptyState.classList.add('hidden');
    errorState.classList.add('hidden');

    try {
        let url;
        if (currentTab === 'tim') {
            const params = new URLSearchParams();
            if (filters.keyword) params.append('keyword', filters.keyword);
            if (filters.kategori) params.append('kategori', filters.kategori);
            params.append('sort_order', filters.sort);
            params.append('per_page', '20');
            url = `/api/posts?${params.toString()}`;
        } else {
            const params = new URLSearchParams();
            if (filters.keyword) params.append('keyword', filters.keyword);
            params.append('open_to_join', '1');
            params.append('per_page', '20');
            url = `/api/posts/search-candidates?${params.toString()}`;
        }

        console.log('🔍 Fetching:', url);

        const response = await fetch(url, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const data = await response.json();
        loading.classList.add('hidden');

        let items = data.data?.data || data.data || [];

        // Client-side filter for status (API doesn't support this)
        if (currentTab === 'tim' && filters.status) {
            items = items.filter(post => {
                const ratio = post.jumlah_terisi / post.jumlah_dibutuhkan;
                if (filters.status === 'terbuka') {
                    return post.status_postingan === 'aktif' && ratio < 1;
                } else if (filters.status === 'hampir_penuh') {
                    return ratio >= 0.5 && ratio < 1;
                }
                return true;
            });
        }

        console.log(`✅ Got ${items.length} items`);

        if (items.length > 0) {
            container.innerHTML = currentTab === 'tim'
                ? renderPosts(items)
                : renderCandidates(items);
            renderActiveChips();
        } else {
            emptyState.classList.remove('hidden');
        }
    } catch (e) {
        console.error('Error loading results:', e);
        loading.classList.add('hidden');
        errorState.classList.remove('hidden');
    }
}

// === Render active filter chips ===
function renderActiveChips() {
    const chipsContainer = document.getElementById('active-chips');
    let chips = [];

    if (filters.kategori) {
        chips.push(`<span class="flex-shrink-0 px-2.5 py-1 bg-[#FF6B35]/10 text-[#FF6B35] text-xs font-medium rounded-full flex items-center gap-1">
            ${filters.kategori}
            <button onclick="removeFilter('kategori')" class="ml-0.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </span>`);
    }
    if (filters.status) {
        const statusLabel = filters.status === 'terbuka' ? 'Masih Dibuka' : 'Hampir Penuh';
        chips.push(`<span class="flex-shrink-0 px-2.5 py-1 bg-[#FF6B35]/10 text-[#FF6B35] text-xs font-medium rounded-full flex items-center gap-1">
            ${statusLabel}
            <button onclick="removeFilter('status')" class="ml-0.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </span>`);
    }

    chipsContainer.innerHTML = chips.join('');
}

function removeFilter(key) {
    filters[key] = '';
    if (key === 'kategori') document.getElementById('filter-kategori').value = '';
    if (key === 'status') document.querySelector('input[name="filter-status"][value=""]').checked = true;
    loadResults();
}

// === Render posts ===
function renderPosts(posts) {
    return posts.map(post => {
        const fillPct = Math.min((post.jumlah_terisi / post.jumlah_dibutuhkan) * 100, 100);
        const deadline = new Date(post.deadline_daftar);
        const daysLeft = Math.ceil((deadline - Date.now()) / 86400000);
        const isAlmostFull = (post.jumlah_terisi / post.jumlah_dibutuhkan) >= 0.5;
        const isFull = post.jumlah_terisi >= post.jumlah_dibutuhkan;

        return `
            <div class="bg-white border border-[#E5E7EB] rounded-[var(--radius-lg)] p-4 shadow-sm hover:shadow-md transition cursor-pointer" onclick="window.location.href='/posts/${post.id}'">
                <div class="flex items-start justify-between mb-2">
                    <span class="px-2.5 py-0.5 text-[10px] font-semibold bg-[#FF6B35]/10 text-[#FF6B35] rounded-full">
                        ${post.kategori_lomba || 'Umum'}
                    </span>
                    <span class="text-xs text-[#9CA3AF] font-medium">
                        ${daysLeft > 0 ? daysLeft + ' hari lagi' : 'Berakhir'}
                    </span>
                </div>
                <h3 class="text-[#1A1A2E] font-semibold text-sm mb-2">
                    ${post.nama_lomba}
                </h3>
                <div class="flex items-center justify-between mb-2 text-xs">
                    <span class="text-[#6B7280]">${post.jumlah_terisi}/${post.jumlah_dibutuhkan} anggota</span>
                    <span class="${isFull ? 'text-[#EF4444]' : isAlmostFull ? 'text-[#F59E0B]' : 'text-[#10B981]'} font-medium">
                        ${isFull ? 'Penuh' : isAlmostFull ? 'Hampir Penuh' : 'Tersedia'}
                    </span>
                </div>
                <div class="w-full h-2 bg-[#E5E7EB] rounded-full overflow-hidden mb-3">
                    <div class="h-full ${isFull ? 'bg-[#EF4444]' : isAlmostFull ? 'bg-[#F59E0B]' : 'bg-[#FF6B35]'} rounded-full transition-all" style="width: ${fillPct}%"></div>
                </div>
                <div class="flex flex-wrap gap-1">
                    ${(post.skill_requirements || []).slice(0, 3).map(sr => `
                        <span class="px-2 py-0.5 bg-[#F1F5F9] text-[#6B7280] text-xs rounded-full">
                            ${sr.skill?.nama_skill || 'Skill'}
                        </span>
                    `).join('')}
                    ${post.skill_requirements?.length > 3 ? `<span class="text-xs text-[#9CA3AF] px-2 py-0.5">+${post.skill_requirements.length - 3}</span>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

// === Render candidates ===
function renderCandidates(candidates) {
    return candidates.map(c => `
        <div class="bg-white border border-[#E5E7EB] rounded-[var(--radius-lg)] p-4 shadow-sm hover:shadow-md transition cursor-pointer" onclick="window.location.href='/profile/${c.id}'">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 bg-[#FF6B35]/10 rounded-full flex items-center justify-center text-[#FF6B35] font-bold text-lg flex-shrink-0">
                    ${(c.name || '?').charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="text-[#1A1A2E] font-semibold text-sm truncate">${c.name}</h3>
                        ${c.profile?.is_open_to_join ? '<span class="flex-shrink-0 px-2 py-0.5 bg-[#10B981]/10 text-[#10B981] text-xs font-medium rounded-full">Tersedia</span>' : ''}
                    </div>
                    <p class="text-[#6B7280] text-xs mb-2 truncate">
                        ${c.profile?.prodi || 'Prodi belum diisi'} • Angkatan ${c.profile?.angkatan || '-'}
                    </p>
                    <div class="flex flex-wrap gap-1">
                        ${(c.skills || []).slice(0, 4).map(us => `
                            <span class="px-2 py-0.5 bg-[#F1F5F9] text-[#6B7280] text-xs rounded-full">
                                ${us.skill?.nama_skill || 'Skill'}
                            </span>
                        `).join('')}
                        ${c.skills?.length > 4 ? `<span class="text-xs text-[#9CA3AF] px-2 py-0.5">+${c.skills.length - 4}</span>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// === Init ===
loadCategories();
loadResults();
</script>
@endsection
