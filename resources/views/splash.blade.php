@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#F4F6F9]">
    <div class="flex flex-col items-center">
        <div class="w-16 h-16 mb-4 flex items-center justify-center">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.5a2.5 2.5 0 11-5 0V4a2.5 2.5 0 012.5-2.5h5a2.5 2.5 0 012.5 2.5v1.62l.42.22a2 2 0 002.94-.5h4.32A2 2 0 0022 7.5v13a2 2 0 002-2h-5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-.52l.42-.22v1a4.5 4.5 0 11-9 0"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-3 font-heading tracking-tight">SkillMatch</h1>
        <p class="text-white text-sm opacity-80">Temukan Tim Lomba yang Tepat</p>
        <div class="animate-pulse mt-6 w-8 h-4 mx-auto" aria-hidden="true"></div>
    </div>
</div>

<script>
    setTimeout(() => {
        const token = localStorage.getItem('token');
        if (token) {
            window.location.href = '/beranda';
        } else {
            window.location.href = '/login';
        }
    }, 1800);
</script>
@endsection