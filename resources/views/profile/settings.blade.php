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
        <h1 class="text-[#1A1A2E] font-semibold text-lg font-heading">Pengaturan & Privasi</h1>
    </div>

    <!-- Loading -->
    <div id="loading" class="px-4 pt-8 text-center">
        <div class="animate-pulse bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] h-64 p-5"></div>
    </div>

    <!-- Content -->
    <div id="settings-content" class="hidden px-4 pt-4 space-y-4">
        <!-- Status Ketersediaan -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
            <h3 class="font-bold text-[#1A1A2E] text-sm mb-3">Status Ketersediaan</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-[#1A1A2E] font-medium">Open to Join</p>
                    <p class="text-xs text-[#6B7280] mt-0.5">Izinkan tim mengundang kamu</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input id="toggle-open" type="checkbox" class="sr-only peer"
                        onchange="updateStatus(this.checked)">
                    <div class="w-11 h-6 bg-[#E5E7EB] peer-checked:bg-[#FF6B35] rounded-full transition-colors relative">
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5" style="transform: translateX(0)"></span>
                    </div>
                </label>
            </div>
        </div>

        <!-- Privasi -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
            <h3 class="font-bold text-[#1A1A2E] text-sm mb-3">Privasi</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#1A1A2E] font-medium">Portfolio Publik</p>
                        <p class="text-xs text-[#6B7280] mt-0.5">Tampilkan portfolio ke publik</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input id="toggle-portfolio" type="checkbox" class="sr-only peer"
                            onchange="updatePrivacy('is_portfolio_public', this.checked)">
                        <div class="w-11 h-6 bg-[#E5E7EB] peer-checked:bg-[#FF6B35] rounded-full transition-colors relative">
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5" style="transform: translateX(0)"></span>
                        </div>
                    </label>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-[#E5E7EB]">
                    <div>
                        <p class="text-sm text-[#1A1A2E] font-medium">Sosmed Publik</p>
                        <p class="text-xs text-[#6B7280] mt-0.5">Tampilkan sosmed ke publik</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input id="toggle-sosmed" type="checkbox" class="sr-only peer"
                            onchange="updatePrivacy('is_sosmed_public', this.checked)">
                        <div class="w-11 h-6 bg-[#E5E7EB] peer-checked:bg-[#FF6B35] rounded-full transition-colors relative">
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5" style="transform: translateX(0)"></span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Info Akun -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
            <h3 class="font-bold text-[#1A1A2E] text-sm mb-3">Info Akun</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-[#6B7280]">Email</span>
                    <span id="account-email" class="text-[#1A1A2E] font-medium">-</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-[#6B7280]">Bergabung</span>
                    <span id="account-joined" class="text-[#1A1A2E] font-medium">-</span>
                </div>
            </div>
        </div>

        <!-- Logout -->
        <button onclick="logout()"
            class="w-full py-3.5 bg-[#FEF2F2] text-[#EF4444] rounded-[var(--radius-md)] font-semibold text-sm border border-[#EF4444]/20 hover:bg-[#FEE2E2] transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Keluar
        </button>
    </div>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

async function loadSettings() {
    try {
        const response = await fetch('/api/profile', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success) {
            const profileUser = data.data.user;
            const profile = profileUser.profile;

            document.getElementById('toggle-open').checked = !!profile?.is_open_to_join;
            document.getElementById('toggle-portfolio').checked = !!profile?.is_portfolio_public;
            document.getElementById('toggle-sosmed').checked = !!profile?.is_sosmed_public;
            document.getElementById('account-email').textContent = profileUser.email || '-';
            document.getElementById('account-joined').textContent =
                profileUser.created_at ? new Date(profileUser.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short' }) : '-';

            document.getElementById('loading').classList.add('hidden');
            document.getElementById('settings-content').classList.remove('hidden');
        } else {
            alert('Gagal memuat pengaturan');
            history.back();
        }
    } catch (error) {
        console.error('Error loading settings:', error);
        alert('Terjadi kesalahan');
        history.back();
    }
}

async function updateStatus(isOpen) {
    try {
        await fetch('/api/profile/status', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ is_open_to_join: isOpen }),
        });
    } catch (error) {
        console.error('Error updating status:', error);
    }
}

async function updatePrivacy(field, value) {
    try {
        await fetch('/api/profile/privacy', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ [field]: value }),
        });
    } catch (error) {
        console.error('Error updating privacy:', error);
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

loadSettings();
</script>
@endsection
