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
        <h1 class="text-[#1A1A2E] font-semibold text-lg">Detail Tim Lomba</h1>
    </div>

    <!-- Loading skeleton -->
    <div id="loading" class="px-4 py-6 space-y-4">
        <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-10 border border-[#E5E7EB]"></div>
        <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-20 border border-[#E5E7EB]"></div>
        <div class="animate-pulse bg-white rounded-[var(--radius-lg)] h-64 border border-[#E5E7EB]"></div>
    </div>

    <!-- Detailed content -->
    <div id="post-content" class="hidden">
        <!-- Deadline Banner -->
        <div id="deadline-banner" class="mx-4 mt-4 px-4 py-3 rounded-[var(--radius-md)] flex items-center gap-2 border">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm font-medium">Tenggat: <span id="deadline-text"></span></span>
        </div>

        <!-- Post Card -->
        <div class="mx-4 mt-4 bg-white rounded-[var(--radius-lg)] p-5 border border-[#E5E7EB] shadow-sm">
            <!-- Category badge -->
            <span id="category-badge" class="inline-block px-3 py-1 text-xs font-semibold rounded-full mb-3">
                Kategori
            </span>

            <!-- Team name -->
            <h2 id="nama-lomba" class="text-[#1A1A2E] font-bold text-xl mb-4 font-heading">
                Nama Lomba
            </h2>

            <!-- Captain info -->
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-[#E5E7EB]">
                <div id="ketua-avatar" class="w-12 h-12 bg-[#FF6B35]/10 rounded-full flex items-center justify-center text-[#FF6B35] font-bold">
                    ?
                </div>
                <div>
                    <p class="text-[#9CA3AF] text-xs font-medium">Ketua Tim</p>
                    <p id="ketua-name" class="text-[#1A1A2E] font-semibold text-sm">-</p>
                </div>
            </div>

            <!-- Quota Progress -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-[#1A1A2E]">Kuota Anggota</span>
                    <span id="kuota-text" class="text-sm font-bold text-[#1A1A2E]">0 / 0</span>
                </div>
                <div class="w-full h-3 bg-[#E5E7EB] rounded-full overflow-hidden">
                    <div id="kuota-bar" class="h-full bg-[#FF6B35] rounded-full transition-all duration-300"></div>
                </div>
            </div>

            <!-- Skills Required -->
            <div class="mb-6">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Posisi / Skill yang Dibutuhkan</h3>
                <div id="skills-list" class="space-y-2"></div>
            </div>

            <!-- Description (if available) -->
            <div id="description-section" class="mb-6 hidden">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Deskripsi Posisi</h3>
                <p id="post-description" class="text-[#6B7280] text-sm leading-relaxed"></p>
            </div>

            <!-- Requirements -->
            <div class="mb-6">
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Syarat & Ketentuan</h3>
                <p id="syarat" class="text-[#6B7280] text-sm leading-relaxed whitespace-pre-line">
                    Tidak ada syarat khusus
                </p>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="font-bold text-[#1A1A2E] mb-3 text-sm">Kontak</h3>
                <a id="kontak-link" href="#" target="_blank"
                    class="flex items-center gap-2 text-[#FF6B35] text-sm font-medium hover:text-[#E55A2B] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <span id="kontak-text">Link Kontak</span>
                </a>
            </div>
        </div>

        <!-- Apply Button -->
        <div class="mx-4">
            <div id="apply-section">
                <button id="apply-btn" onclick="applyToPost()"
                    class="w-full py-4 bg-[#FF6B35] text-white rounded-[var(--radius-lg)] font-bold text-base hover:bg-[#E55A2B] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>Daftar ke Tim Ini</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Full-screen error overlay -->
    <div id="error-overlay" class="fixed inset-0 bg-black/40 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-[var(--radius-lg)] mx-4 p-6 max-w-sm text-center">
            <svg class="w-12 h-12 text-[#EF4444] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3.75h.007v.007H12v-.007zm.125-3.75a.375.375 0 10-.75 0 .375.375 0 01.75 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.293 3.345a9 9 0 1013.097 0 9 9 0 01-13.097 0z"></path>
            </svg>
            <h3 class="font-bold text-[#1A1A2E] mb-2">Gagal Memuat Detail</h3>
            <p class="text-sm text-[#6B7280] mb-4">Tidak dapat memuat detail postingan ini.</p>
            <button onclick="location.reload()" class="px-4 py-2 bg-[#FF6B35] text-white rounded-[var(--radius-sm)] font-medium text-sm">
                Coba Lagi
            </button>
        </div>
    </div>
</div>

@include('components.bottom-nav', ['active' => 'cari'])

<script>
const token = localStorage.getItem('token');
const user = JSON.parse(localStorage.getItem('user') || '{}');
const postId = {{ $postId ?? 'null' }};

if (!token) {
    window.location.href = '/login';
}

async function loadPostDetail() {
    if (!postId) {
        showError();
        return;
    }

    try {
        const response = await fetch(`/api/posts/${postId}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            const post = data.data.post;

            // Deadline banner
            const deadline = new Date(post.deadline_daftar);
            document.getElementById('deadline-text').textContent =
                deadline.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

            const now = new Date();
            const diffDays = Math.ceil((deadline - now) / (1000 * 60 * 60 * 24));
            const banner = document.getElementById('deadline-banner');

            if (diffDays < 0) {
                banner.className = 'mx-4 mt-4 px-4 py-3 rounded-[var(--radius-md)] flex items-center gap-2 border bg-[#FEF2F2] border-[#FECACA] text-[#B91C1C]';
                document.getElementById('deadline-text').textContent = 'Tenggat telah lewat';
            } else if (diffDays <= 3) {
                banner.className = 'mx-4 mt-4 px-4 py-3 rounded-[var(--radius-md)] flex items-center gap-2 border bg-[#FFFBEB] border-[#FED7AA] text-[#92400E]';
            } else {
                banner.className = 'mx-4 mt-4 px-4 py-3 rounded-[var(--radius-md)] flex items-center gap-2 border bg-[#F0FDF4] border-[#BBF7D0] text-[#16A34A]';
            }

            // Category badge
            const categoryColors = {
                'Pemrograman': 'bg-[#3B82F6]/10 text-[#3B82F6]',
                'Desain': 'bg-[#8B5CF6]/10 text-[#8B5CF6]',
                'Data & AI': 'bg-[#10B981]/10 text-[#10B981]',
                'Hardware & IoT': 'bg-[#F59E0B]/10 text-[#F59E0B]',
                'Bisnis & Manajemen': 'bg-[#EC4899]/10 text-[#EC4899]',
                'Keamanan': 'bg-[#EF4444]/10 text-[#EF4444]',
                'Jaringan': 'bg-[#06B6D4]/10 text-[#06B6D4]',
            };

            const kategori = post.kategori_lomba || 'Lomba';
            document.getElementById('category-badge').className =
                `inline-block px-3 py-1 text-xs font-semibold rounded-full mb-3 ${categoryColors[kategori] || 'bg-[#F1F5F9] text-[#6B7280]'}`;
            document.getElementById('category-badge').textContent = kategori;

            // Nama lomba
            document.getElementById('nama-lomba').textContent = post.nama_lomba;

            // Ketua
            document.getElementById('ketua-name').textContent = post.ketua?.name || 'Unknown';
            document.getElementById('ketua-avatar').textContent =
                (post.ketua?.name || '?').charAt(0).toUpperCase();

            // Kuota
            const percentage = (post.jumlah_terisi / post.jumlah_dibutuhkan) * 100;
            document.getElementById('kuota-text').textContent = `${post.jumlah_terisi} / ${post.jumlah_dibutuhkan}`;
            document.getElementById('kuota-bar').style.width = `${Math.min(percentage, 100)}%`;

            // Skills
            if (post.skill_requirements && post.skill_requirements.length > 0) {
                document.getElementById('skills-list').innerHTML = post.skill_requirements.map(sr => `
                    <div class="flex items-center justify-between p-3 bg-[#F8FAFC] rounded-[var(--radius-md)] border border-[#F1F5F9]">
                        <span class="font-medium text-[#1A1A2E] text-sm">
                            ${sr.skill?.nama_skill || 'Skill'}
                        </span>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                            ${sr.prioritas === 'wajib' ? 'bg-[#F1F5F9] text-[#10B981]' : 'bg-[#EEF2FF] text-[#6B7280]'}">
                            ${sr.prioritas === 'wajib' ? 'Wajib' : 'Opsional'}
                        </span>
                    </div>
                `).join('');
            }

            // Syarat
            document.getElementById('syarat').textContent =
                post.deskripsi_syarat || 'Tidak ada syarat khusus';

            // Kontak
            document.getElementById('kontak-link').href = post.link_sosmed || '#';
            document.getElementById('kontak-text').textContent =
                post.link_sosmed || 'Belum ada kontak';

            // Apply section
            const applySection = document.getElementById('apply-section');
            const applyBtn = document.getElementById('apply-btn');

            if (post.ketua_id === user.id) {
                applySection.innerHTML = '<p class="text-center text-[#6B7280] text-sm">Ini adalah postingan Anda</p>';
            } else if (post.status_postingan !== 'aktif' || post.jumlah_terisi >= post.jumlah_dibutuhkan) {
                applyBtn.disabled = true;
                applyBtn.className = 'w-full py-4 bg-[#E5E7EB] text-white rounded-[var(--radius-lg)] font-bold text-base cursor-not-allowed';
                applyBtn.textContent = 'Pendaftaran Ditutup';
            } else {
                applyBtn.onclick = applyToPost;
            }

            // Hide loading, show content
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('post-content').classList.remove('hidden');
        } else {
            showError();
        }
    } catch (error) {
        console.error('Error:', error);
        showError();
    }
}

function showError() {
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('error-overlay').classList.remove('hidden');
}

async function applyToPost() {
    if (!confirm('Yakin ingin mendaftar ke tim ini?')) return;

    const btn = document.getElementById('apply-btn');
    btn.disabled = true;
    btn.innerHTML = '<span>Memproses...</span>';

    try {
        const response = await fetch('/api/applications', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ post_id: postId })
        });

        const data = await response.json();

        if (response.ok) {
            alert('Pengajuan berhasil dikirim!');
            window.location.href = '/pengajuan';
        } else {
            alert(data.message || 'Gagal mengirim pengajuan');
            btn.disabled = false;
            btn.innerHTML = '<span>Daftar ke Tim Ini</span>';
        }
    } catch (error) {
        alert('Terjadi kesalahan');
        btn.disabled = false;
        btn.innerHTML = '<span>Daftar ke Tim Ini</span>';
    }
}

loadPostDetail();
</script>
@endsection

