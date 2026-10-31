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
        <h1 class="text-[#1A1A2E] font-semibold text-lg font-heading">Edit Profil</h1>
    </div>

    <!-- Loading -->
    <div id="loading" class="px-4 pt-8 text-center">
        <div class="animate-pulse bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] h-96 p-5"></div>
    </div>

    <!-- Form -->
    <form id="edit-form" class="hidden px-4 pt-4 space-y-4">
        <!-- Nama -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5">
            <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Nama Lengkap</label>
            <input id="input-name" type="text"
                class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                placeholder="Nama kamu">
        </div>

        <!-- Akademik -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5 space-y-3">
            <h3 class="font-bold text-[#1A1A2E] text-sm">Info Akademik</h3>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">NPM</label>
                <input id="input-npm" type="text"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                    placeholder="Contoh: 2215010001">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Program Studi</label>
                <input id="input-prodi" type="text"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                    placeholder="Contoh: Teknik Informatika">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Angkatan</label>
                <input id="input-angkatan" type="text"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                    placeholder="Contoh: 2022">
            </div>
        </div>

        <!-- Pengalaman -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5 space-y-3">
            <h3 class="font-bold text-[#1A1A2E] text-sm">Pengalaman & Minat</h3>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Pengalaman Lomba</label>
                <textarea id="input-pengalaman" rows="3"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] resize-none"
                    placeholder="Ceritakan pengalaman lomba kamu..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Minat Bidang Lomba</label>
                <textarea id="input-minat" rows="2"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35] resize-none"
                    placeholder="Contoh: Web dev, UI/UX, Data Science..."></textarea>
            </div>
        </div>

        <!-- Link -->
        <div class="bg-white rounded-[var(--radius-lg)] border border-[#E5E7EB] shadow-sm p-5 space-y-3">
            <h3 class="font-bold text-[#1A1A2E] text-sm">Link & Portfolio</h3>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Link Portfolio</label>
                <input id="input-portofolio" type="url"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                    placeholder="https://github.com/username">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">Link Sosial Media</label>
                <input id="input-sosmed" type="url"
                    class="w-full px-3 py-2.5 border border-[#E5E7EB] rounded-[var(--radius-sm)] text-sm text-[#1A1A2E] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/20 focus:border-[#FF6B35]"
                    placeholder="https://instagram.com/username">
            </div>
        </div>

        <!-- Error message -->
        <div id="error-msg" class="hidden bg-[#FEF2F2] border border-[#EF4444]/20 text-[#EF4444] text-sm rounded-[var(--radius-sm)] px-4 py-3"></div>

        <!-- Submit -->
        <button type="submit"
            class="w-full py-3.5 bg-[#FF6B35] text-white rounded-[var(--radius-md)] font-semibold text-sm hover:bg-[#E55A2B] transition flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Simpan Perubahan
        </button>
    </form>
</div>

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

async function loadProfile() {
    try {
        const response = await fetch('/api/profile', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();

        if (data.success) {
            const profileUser = data.data.user;
            const profile = profileUser.profile;

            document.getElementById('input-name').value = profileUser.name || '';
            document.getElementById('input-npm').value = profile?.npm || '';
            document.getElementById('input-prodi').value = profile?.prodi || '';
            document.getElementById('input-angkatan').value = profile?.angkatan || '';
            document.getElementById('input-pengalaman').value = profile?.pengalaman_lomba || '';
            document.getElementById('input-minat').value = profile?.minat_bidang_lomba || '';
            document.getElementById('input-portofolio').value = profile?.link_portofolio || '';
            document.getElementById('input-sosmed').value = profile?.link_sosmed || '';

            document.getElementById('loading').classList.add('hidden');
            document.getElementById('edit-form').classList.remove('hidden');
        } else {
            alert('Gagal memuat profil');
            history.back();
        }
    } catch (error) {
        console.error('Error loading profile:', error);
        alert('Terjadi kesalahan');
        history.back();
    }
}

document.getElementById('edit-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const errorMsg = document.getElementById('error-msg');
    errorMsg.classList.add('hidden');

    const payload = {
        name: document.getElementById('input-name').value,
        npm: document.getElementById('input-npm').value,
        prodi: document.getElementById('input-prodi').value,
        angkatan: document.getElementById('input-angkatan').value,
        pengalaman_lomba: document.getElementById('input-pengalaman').value,
        minat_bidang_lomba: document.getElementById('input-minat').value,
        link_portofolio: document.getElementById('input-portofolio').value || null,
        link_sosmed: document.getElementById('input-sosmed').value || null,
    };

    try {
        const response = await fetch('/api/profile', {
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json();

        if (data.success) {
            // Update localStorage user name
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            user.name = payload.name;
            localStorage.setItem('user', JSON.stringify(user));

            alert('Profil berhasil diperbarui!');
            window.location.href = '/profile';
        } else {
            errorMsg.textContent = data.message || 'Gagal menyimpan perubahan';
            errorMsg.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        errorMsg.textContent = 'Terjadi kesalahan. Coba lagi.';
        errorMsg.classList.remove('hidden');
    }
});

loadProfile();
</script>
@endsection
