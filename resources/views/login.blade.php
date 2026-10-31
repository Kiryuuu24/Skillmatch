@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center bg-[#F4F6F9] px-[var(--space-sm)]">
    <div class="max-w-md w-full">
        <div class="text-center mb-[var(--space-xl)]">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center rounded-xl">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.5a2.5 2.5 0 11-5 0V4a2.5 2.5 0 012.5-2.5h5a2.5 2.5 0 012.5 2.5v1.62l.42.22a2 2 0 002.94-.5h4.32A2 2 0 0022 7.5v13a2 2 0 002-2h-5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-.52l.42-.22v1a4.5 4.5 0 11-9 0"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#1A1A2E] mb-2 font-heading">Masuk</h1>
            <p class="text-sm text-[#6B7280]">Temukan tim lomba yang tepat untukmu</p>
        </div>

        <div class="bg-[#FFFFFF] rounded-[var(--radius-lg)] p-[var(--space-lg)] border border-[#E5E7EB] shadow-sm">
            <div id="error-alert" class="hidden mb-4 p-3 bg-[#FFF1F2] border border-[#FECACA] rounded-[var(--radius-md)]">
                <p class="text-xs text-[#B91C1C] font-medium">{{ $errors->first() ?? 'Login gagal' }}</p>
            </div>

            <form id="login-form" class="space-y-3">
                <div>
                    <label for="login" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                        Email atau Nama Pengguna
                    </label>
                    <input
                        type="text"
                        id="login"
                        name="login"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] text-base bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                        placeholder="Masukkan email atau username"
                    />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#1A1A2E] mb-1.5">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-[#D1D5DB] text-base bg-white focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:border-[#FF6B35] transition-all"
                        placeholder="Masukkan kata sandi"
                    />
                </div>

                <button type="submit" id="login-btn" class="w-full mt-2 flex items-center justify-center gap-2 bg-[#FF6B35] text-white px-4 py-3 rounded-xl font-semibold hover:bg-[#E55A2B] focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 active:scale-[0.98] transition-all">
                    <span>Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </button>
            </form>

            <div class="mt-[var(--space-md)] text-center">
                <p class="text-sm text-[#6B7280]">
                    Belum punya akun?
                    <a href="/register" class="text-[#FF6B35] font-semibold hover:text-[#E55A2B] transition-colors">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = document.getElementById('login-btn');
    const errorAlert = document.getElementById('error-alert');
    const errorText = errorAlert.querySelector('p');

    btn.disabled = true;
    errorAlert.classList.add('hidden');

    try {
        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                login: document.getElementById('login').value,
                password: document.getElementById('password').value
            })
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.setItem('token', data.data.token);
            localStorage.setItem('user', JSON.stringify(data.data.user));
            window.location.href = data.data.redirect || '/beranda';
        } else {
            errorText.textContent = data.message || 'Login gagal';
            errorAlert.classList.remove('hidden');
        }
    } catch (error) {
        errorText.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
        errorAlert.classList.remove('hidden');
    } finally {
        btn.disabled = false;
    }
});
</script>
@endsection