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
        <h1 class="text-[#1A1A2E] font-semibold text-lg">Buat Postingan Tim</h1>
    </div>

    <div class="px-4 mt-4">
        <!-- Error Alert -->
        <div id="error-alert" class="hidden mb-4 p-3 bg-[#FFF1F2] border border-[#FECACA] rounded-[var(--radius-md)]">
            <ul class="text-xs text-[#B91C1C] list-disc list-inside" id="error-list"></ul>
        </div>

        <form id="create-post-form" class="space-y-5">
            <!-- Nama Lomba -->
            <div>
                <label for="nama_lomba" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Nama Lomba *
                </label>
                <input
                    type="text"
                    id="nama_lomba"
                    name="nama_lomba"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                    placeholder="Contoh: Hackathon Unila 2026"
                />
            </div>

            <!-- Kategori -->
            <div>
                <label for="kategori_lomba" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Kategori Lomba *
                </label>
                <select
                    id="kategori_lomba"
                    name="kategori_lomba"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                >
                    <option value="">Pilih Kategori</option>
                    <option value="Pemrograman">Pemrograman</option>
                    <option value="Desain">Desain</option>
                    <option value="Data & AI">Data & AI</option>
                    <option value="Hardware & IoT">Hardware & IoT</option>
                    <option value="Bisnis & Manajemen">Bisnis & Manajemen</option>
                    <option value="Keamanan">Keamanan</option>
                    <option value="Jaringan">Jaringan</option>
                </select>
            </div>

            <!-- Jumlah Dibutuhkan -->
            <div>
                <label for="jumlah_dibutuhkan" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Jumlah Anggota yang Dibutuhkan *
                </label>
                <input
                    type="number"
                    id="jumlah_dibutuhkan"
                    name="jumlah_dibutuhkan"
                    min="1"
                    max="10"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                    placeholder="Misal: 3 (termasuk ketua)"
                />
                <p class="text-xs text-[#9CA3AF] mt-1">
                    Masukkan jumlah total anggota tim, termasuk kamu sebagai ketua
                </p>
            </div>

            <!-- Deadline -->
            <div>
                <label for="deadline_daftar" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Tenggat Pendaftaran *
                </label>
                <input
                    type="date"
                    id="deadline_daftar"
                    name="deadline_daftar"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                />
            </div>

            <!-- Link Kontak -->
            <div>
                <label for="link_sosmed" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Link Kontak / Media Sosial *
                </label>
                <input
                    type="url"
                    id="link_sosmed"
                    name="link_sosmed"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                    placeholder="https://wa.me/628123456789"
                />
                <p class="text-xs text-[#9CA3AF] mt-1">
                    WhatsApp, Telegram, Instagram, dll
                </p>
            </div>

            <!-- Skills Required -->
            <div>
                <label class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Skill yang Dibutuhkan *
                </label>
                <div id="skills-container" class="space-y-2.5"></div>
                <button type="button" onclick="addSkillField()"
                    class="mt-2 text-[#FF6B35] text-sm font-semibold flex items-center gap-1 hover:text-[#E55A2B] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Skill
                </button>
                <p class="text-xs text-[#9CA3AF] mt-1">
                    Minimal 1 skill wajib
                </p>
            </div>

            <!-- Syarat & Ketentuan -->
            <div>
                <label for="deskripsi_syarat" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                    Syarat & Ketentuan
                </label>
                <textarea
                    id="deskripsi_syarat"
                    name="deskripsi_syarat"
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                    placeholder="Misal: Mahasiswa aktif Teknik Informatika, berpengalaman di bidang terkait, komitmen tinggi"
                ></textarea>
            </div>

            <!-- Submit -->
            <button type="submit" id="submit-btn"
                class="w-full py-4 bg-[#FF6B35] text-white rounded-[var(--radius-lg)] font-bold text-base hover:bg-[#E55A2B] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span id="submit-text">Publikasikan Tim</span>
                <svg id="submit-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>
    </div>
</div>

@include('components.bottom-nav', ['active' => 'create'])

<script>
const token = localStorage.getItem('token');
if (!token) window.location.href = '/login';

let skills = [];
let skillCounter = 0;

// Load skills data
async function loadSkills() {
    try {
        const response = await fetch('/api/skills', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const data = await response.json();
        if (data.success) {
            skills = data.data.skills;
            addSkillField(); // Add first field
        }
    } catch (e) {
        console.error('Error loading skills:', e);
    }
}

function addSkillField() {
    const container = document.getElementById('skills-container');
    const id = skillCounter++;

    const div = document.createElement('div');
    div.className = 'flex gap-2 items-end';
    div.innerHTML = `
        <div class="flex-1">
            <select class="skill-select w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all" data-id="${id}">
                <option value="">Pilih Skill</option>
                ${skills.map(s => `<option value="${s.id}">${s.nama_skill}</option>`).join('')}
            </select>
        </div>
        <div class="w-32">
            <select class="skill-priority w-full px-4 py-3 rounded-xl border border-[#D1D5DB] bg-white text-base focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all" data-id="${id}">
                <option value="wajib">Wajib</option>
                <option value="opsional">Opsional</option>
            </select>
        </div>
        <button type="button" onclick="removeSkillField(this)" class="pb-2 text-[#EF4444] px-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeSkillField(btn) {
    btn.closest('div').remove();
}

// Set min date to tomorrow
document.addEventListener('DOMContentLoaded', () => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('deadline_daftar').min = tomorrow.toISOString().split('T')[0];
});

document.getElementById('create-post-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    const errorAlert = document.getElementById('error-alert');
    const errorList = document.getElementById('error-list');

    btn.disabled = true;
    submitText.textContent = 'Memproses...';
    submitSpinner.classList.remove('hidden');
    errorAlert.classList.add('hidden');
    errorList.innerHTML = '';

    // Collect skills
    const skillSelects = document.querySelectorAll('.skill-select');
    const prioritySelects = document.querySelectorAll('.skill-priority');
    const skillsData = [];

    skillSelects.forEach((select, idx) => {
        if (select.value) {
            skillsData.push({
                skill_id: parseInt(select.value),
                prioritas: prioritySelects[idx].value
            });
        }
    });

    if (skillsData.length === 0) {
        errorList.innerHTML = '<li>Minimal 1 skill harus dipilih</li>';
        errorAlert.classList.remove('hidden');
        btn.disabled = false;
        submitText.textContent = 'Publikasikan Tim';
        submitSpinner.classList.add('hidden');
        return;
    }

    try {
        const response = await fetch('/api/posts', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama_lomba: document.getElementById('nama_lomba').value,
                kategori_lomba: document.getElementById('kategori_lomba').value,
                jumlah_dibutuhkan: parseInt(document.getElementById('jumlah_dibutuhkan').value),
                deadline_daftar: document.getElementById('deadline_daftar').value,
                link_sosmed: document.getElementById('link_sosmed').value,
                deskripsi_syarat: document.getElementById('deskripsi_syarat').value,
                skills: skillsData
            })
        });

        const data = await response.json();

        if (response.ok) {
            alert('Postingan berhasil dipublikasikan!');
            window.location.href = '/beranda';
        } else {
            if (data.errors) {
                Object.values(data.errors).flat().forEach(error => {
                    const li = document.createElement('li');
                    li.textContent = error;
                    errorList.appendChild(li);
                });
            } else {
                errorList.innerHTML = `<li>${data.message || 'Gagal membuat postingan'}</li>`;
            }
            errorAlert.classList.remove('hidden');
        }
    } catch (error) {
        errorList.innerHTML = '<li>Terjadi kesalahan. Silakan coba lagi.</li>';
        errorAlert.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        submitText.textContent = 'Publikasikan Tim';
        submitSpinner.classList.add('hidden');
    }
});

loadSkills();
</script>
@endsection
