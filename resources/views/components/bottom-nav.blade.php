@props(['active' => 'beranda'])

@php
    $navItems = [
        ['id' => 'beranda', 'icon' => 'home', 'label' => 'Beranda', 'href' => '/beranda'],
        ['id' => 'cari', 'icon' => 'search', 'label' => 'Cari', 'href' => '/cari'],
        ['id' => 'create', 'icon' => 'plus', 'label' => 'Buat', 'href' => '/posts/create'],
        ['id' => 'pengajuan', 'icon' => 'inbox', 'label' => 'Pengajuan', 'href' => '/pengajuan'],
        ['id' => 'profile', 'icon' => 'user', 'label' => 'Profil', 'href' => '/profile'],
    ];

    function getIcon(string $icon): string {
        return match($icon) {
            'home' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
            'search' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>',
            'inbox' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-1.832 1.995L11.5 19l-6.668.005A2 2 0 013 18v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4z"></path></svg>',
            'user' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
            'plus' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>',
            default => '',
        };
    }
@endphp

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-[#E5E7EB] max-w-md mx-auto z-50">
    <div class="flex items-center justify-around py-2">
        @foreach($navItems as $item)
            @if($item['id'] === 'create')
                <a href="{{ $item['href'] }}" class="-mt-7">
                    <div class="w-14 h-14 bg-[#FF6B35] rounded-full flex items-center justify-center shadow-lg hover:bg-[#E55A2B] transition focus:outline-none focus:ring-2 focus:ring-[#FF6B35]/30 focus:ring-offset-2">
                        {!! getIcon('plus') !!}
                    </div>
                </a>
            @else
                <a href="{{ $item['href'] }}"
                   class="flex flex-col items-center py-2 px-3 transition-all {{ $active === $item['id'] ? 'text-[#FF6B35]' : 'text-[#9CA3AF] hover:text-[#1A1A2E]' }}">
                    {!! getIcon($item['icon']) !!}
                    <span class="text-xs mt-1 font-medium">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </div>
</nav>
