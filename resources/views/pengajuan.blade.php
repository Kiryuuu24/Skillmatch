@extends('layouts.auth')

@section('content')
<div class="min-h-screen bg-[#F4F6F9] pb-24">
    <!-- Header -->
    <div class="bg-white border-b border-[#E5E7EB] px-4 py-4">
        <h1 class="text-[#1A1A2E] font-bold text-xl font-heading">Pengajuan</h1>

        <!-- Role Tabs -->
        <div class="flex bg-[#E5E7EB] rounded-full p-1 mt-3">
            <button onclick="switchRole('pelamar')" id="role-pelamar"
                class="flex-1 py-2.5 rounded-full font-semibold text-sm transition-all">
                Lamaran Saya
            </button>
            <button onclick="switchRole('ketua')" id="role-ketua"
                class="flex-1 py-2.5 rounded-full font-semibold text-sm transition-all">
                Pelamar Masuk
            </button>
        </div>

        <!-- Status Tabs -->
        <div class="flex gap-2 mt-3 overflow-x-auto">
            <button onclick="switchTab('menunggu')" id="tab-menunggu"
                class="flex-shrink-0 px-4 py-2 rounded-full font-semibold text-xs transition-all whitespace-nowrap">
                Menunggu
            </button>
            <button onclick="switchTab('diterima')" id="tab-diterima"
                class="flex-shrink-0 px-4 py-2 rounded-full font-semibold text-xs transition-all whitespace-nowrap">
                Diterima
            </button>
            <button onclick="switchTab('ditolak')" id="tab-ditolak"
                class="flex-shrink-0 px-4 py-2 rounded-full font-semibold text-xs transition-all whitespace-nowrap">
                Ditolak
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 mt-4">
        <!-- Loading -->
        <div id="loading" class="space-y-3">
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-24 border border-[#E5E7EB]"></div>
            <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-24 border border-[#E5E7EB]"></div>
        </div>

        <!-- Applications list -->
        <div id="applications-list" class="space-y-3"></div>

        <!-- Empty state -->
        <div id="empty-state" class="hidden text-center py-12">
            <div class="w-16 h-16 bg-[#F1F5F9] rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-[#9CA3AF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-[#6B7280] text-sm">Tidak ada pengajuan pada tab ini</p>
            <p class="text-[#9CA3AF] text-xs mt-1">Cek kembali nanti atau ubah filter</p>
        </div>
    </div>
</div>

@include('components.bottom-nav', ['active' => 'pengajuan'])

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

let currentTab = 'menunggu';
let currentRole = 'pelamar';

// Initialize active states
document.getElementById('role-pelamar').classList.add('bg-[#FF6B35]', 'text-white', 'shadow-sm');
document.getElementById('tab-menunggu').classList.add('bg-[#FF6B35]', 'text-white');

function switchRole(role) {
    currentRole = role;
    document.getElementById('role-pelamar').className = role === 'pelamar'
        ? 'flex-1 py-2.5 rounded-full font-semibold text-sm transition-all bg-[#FF6B35] text-white shadow-sm'
        : 'flex-1 py-2.5 rounded-full font-semibold text-sm transition-all text-[#6B7280]';
    document.getElementById('role-ketua').className = role === 'ketua'
        ? 'flex-1 py-2.5 rounded-full font-semibold text-sm transition-all bg-[#FF6B35] text-white shadow-sm'
        : 'flex-1 py-2.5 rounded-full font-semibold text-sm transition-all text-[#6B7280]';
    loadApplications();
}

function switchTab(tab) {
    currentTab = tab;
    ['menunggu', 'diterima', 'ditolak'].forEach(t => {
        const el = document.getElementById(`tab-${t}`);
        if (t === tab) {
            el.className = 'flex-shrink-0 px-4 py-2 rounded-full font-semibold text-xs transition-all whitespace-nowrap bg-[#FF6B35] text-white';
        } else {
            el.className = 'flex-shrink-0 px-4 py-2 rounded-full font-semibold text-xs transition-all whitespace-nowrap text-[#9CA3AF] hover:text-[#1A1A2E]';
        }
    });
    loadApplications();
}

async function loadApplications() {
    const loading = document.getElementById('loading');
    const list = document.getElementById('applications-list');
    const emptyState = document.getElementById('empty-state');

    loading.classList.remove('hidden');
    list.innerHTML = '';
    emptyState.classList.add('hidden');

    try {
        const response = await fetch(`/api/applications?role=${currentRole}&tab=${currentTab}`, {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });

        const data = await response.json();
        loading.classList.add('hidden');

        const items = data.data?.data || data.data || [];
        if (items.length > 0) {
            list.innerHTML = items.map(app => renderApplication(app)).join('');
        } else {
            emptyState.classList.remove('hidden');
        }
    } catch (e) {
        loading.classList.add('hidden');
        emptyState.classList.remove('hidden');
    }
}

function renderApplication(app) {
    if (currentRole === 'pelamar') {
        const statusColors = {
            'menunggu': 'bg-[#F59E0B]/10 text-[#F59E0B] border border-[#F59E0B]/20',
            'diterima': 'bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20',
            'ditolak': 'bg-[#EF4444]/10 text-[#EF4444] border border-[#EF4444]/20'
        };
        const statusIcons = {
            'menunggu': '⧗',
            'diterima': '✓',
            'ditolak': '✕'
        };

        return `
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-[#1A1A2E] font-semibold text-sm mb-1">
                            ${app.post?.nama_lomba || 'Nama Lomba'}
                        </h3>
                        <p class="text-[#6B7280] text-xs">
                            oleh ${app.post?.ketua?.name || 'Unknown'}
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold border ${statusColors[app.status_pengajuan] || statusColors.menunggu}">
                        ${app.status_pengajuan || 'menunggu'}
                    </span>
                </div>
                <button onclick="window.location.href='/posts/${app.post_id}'"
                    class="text-[#FF6B35] text-xs font-semibold hover:text-[#E55A2B] transition">
                    Lihat Detail Tim
                </button>
            </div>
        `;
    } else {
        const statusColors = {
            'menunggu': 'bg-[#F59E0B]/10 text-[#F59E0B]',
            'diterima': 'bg-[#10B981]/10 text-[#10B981]',
            'ditolak': 'bg-[#EF4444]/10 text-[#EF4444]'
        };

        return `
            <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-[#1A1A2E] font-semibold text-sm mb-1">
                            ${app.pelamar?.name || 'Nama User'}
                        </h3>
                        <p class="text-[#6B7280] text-xs mb-2">
                            ${app.pelamar?.profile?.prodi || '-'} • Angkatan ${app.pelamar?.profile?.angkatan || '-'}
                        </p>
                        <p class="text-[#9CA3AF] text-xs mb-2">
                            untuk: ${app.post?.nama_lomba || 'N/A'}
                        </p>
                        ${app.pesan_lamaran ? `<p class="text-[#6B7280] text-xs mt-2 italic">"${app.pesan_lamaran}"</p>` : ''}
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${statusColors[app.status_pengajuan] || ''}">
                        ${app.status_pengajuan}
                    </span>
                </div>
                ${currentTab === 'menunggu' ? `
                    <div class="flex gap-2 mt-3">
                        <button onclick="updateStatus(${app.id}, 'diterima')"
                            class="flex-1 py-2.5 bg-[#10B981] text-white rounded-[var(--radius-sm)] text-xs font-semibold hover:bg-[#059669] transition">
                            Terima
                        </button>
                        <button onclick="updateStatus(${app.id}, 'ditolak')"
                            class="flex-1 py-2.5 bg-[#EF4444] text-white rounded-[var(--radius-sm)] text-xs font-semibold hover:bg-[#DC2626] transition">
                            Tolak
                        </button>
                    </div>
                ` : `
                    <button onclick="window.location.href='/profile/${app.pelamar_id}'"
                        class="mt-3 w-full py-2.5 bg-[#FF6B35] text-white rounded-[var(--radius-sm)] text-xs font-semibold hover:bg-[#E55A2B] transition">
                        Lihat Profil
                    </button>
                `}
            </div>
        `;
    }
}

async function updateStatus(appId, status) {
    if (!confirm(`Yakin ingin ${status === 'diterima' ? 'menerima' : 'menolak'} pengajuan ini?`)) return;

    try {
        const response = await fetch(`/api/applications/${appId}`, {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status_pengajuan: status })
        });

        const data = await response.json();

        if (response.ok) {
            alert(data.message || 'Status berhasil diperbarui');
            loadApplications();
        } else {
            alert(data.message || 'Gagal memperbarui status');
        }
    } catch (e) {
        alert('Terjadi kesalahan');
    }
}

loadApplications();
</script>
@endsection