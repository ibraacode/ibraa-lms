@php
    $menus = \App\Support\MenuBuilder::forUser();
    
    $isMenuActive = function($menu) {
        if (isset($menu['route']) && request()->routeIs($menu['route'])) return true;
        if (isset($menu['url']) && request()->url() == url($menu['url'])) return true;
        if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if ((isset($child['route']) && request()->routeIs($child['route'])) || 
                    (isset($child['url']) && request()->url() == url($child['url']))) {
                    return true;
                }
            }
        }
        return false;
    };
@endphp

{{-- OVERLAY GELAP (Hanya muncul di mobile saat sidebar terbuka) --}}
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     class="fixed inset-0 bg-black/60 z-[60] md:hidden" 
     x-cloak></div>

{{-- SIDEBAR --}}
<aside 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-[70] w-[280px] bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out md:static md:translate-x-0 flex flex-col h-screen overflow-hidden"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    {{-- Bagian Atas (Logo & Menu yang bisa di-scroll) --}}
    <div class="flex-1 overflow-y-auto p-6 w-full">
        
        {{-- LOGO HEADER --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                @if(\App\Models\Setting::get('school_logo'))
                    <img src="{{ Storage::url(\App\Models\Setting::get('school_logo')) }}" alt="logo" class="h-10 object-contain">
                @else
                    <div class="w-10 h-10 rounded-2xl bg-blue-500 flex items-center justify-center text-white shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2z" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-xl text-gray-900 tracking-tight truncate">
                        {{ \App\Models\Setting::get('school_name', 'IbraLMS') }}
                    </span>
                @endif
            </a>

            {{-- Tombol Close Mobile --}}
            <button @click="sidebarOpen = false" class="md:hidden p-1 text-gray-500 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- MENU LIST --}}
        <ul class="flex flex-col gap-1 pb-4">
            @foreach ($menus as $menu)
                @if ($loop->first)
                    <li><h3 class="text-[11px] font-bold text-gray-400 tracking-wider mb-3 px-3 uppercase mt-2">DAILY USE</h3></li>
                @endif

                @if ($loop->index == 5)
                    <li><h3 class="text-[11px] font-bold text-gray-400 tracking-wider mb-3 px-3 uppercase mt-6">OTHERS</h3></li>
                @endif

                @php $activeState = $isMenuActive($menu); @endphp

                @if (!empty($menu['children']))
                    <li x-data="{ open: {{ $activeState ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-full text-sm font-semibold transition-all {{ $activeState ? 'bg-[#2F80ED] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center gap-3.5 w-full min-w-0">
                                <img src="{{ asset('icons/' . ($menu['icon'] ?? 'circle') . '.svg') }}" alt="icon" class="w-5 h-5 shrink-0 object-contain {{ $activeState ? 'brightness-0 invert' : 'opacity-60' }}" onerror="this.style.display='none'">
                                <span class="whitespace-nowrap overflow-hidden text-ellipsis text-left truncate">{{ $menu['label'] }}</span>
                            </div>
                            <span x-show="!open" class="text-[10px] shrink-0">▶</span>
                            <span x-show="open" class="text-[10px] shrink-0">▼</span>
                        </button>

                        <div x-show="open" x-collapse class="flex flex-col gap-1 mt-1 pl-[2.5rem] pr-2">
                            @foreach ($menu['children'] as $child)
                                @php 
                                    $childActive = (isset($child['route']) && request()->routeIs($child['route'])) || (isset($child['url']) && request()->url() == url($child['url']));
                                @endphp
                                <a href="{{ $child['url'] }}" class="py-2 text-sm font-medium transition-colors hover:text-[#2F80ED] truncate {{ $childActive ? 'text-[#2F80ED] font-bold' : 'text-gray-500' }}">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                @else
                    <li>
                        <a href="{{ $menu['url'] }}" class="w-full flex items-center justify-between px-4 py-3 rounded-full text-sm font-semibold transition-all {{ $activeState ? 'bg-[#2F80ED] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <div class="flex items-center gap-3.5 w-full min-w-0">
                                <img src="{{ asset('icons/' . ($menu['icon'] ?? 'circle') . '.svg') }}" alt="icon" class="w-5 h-5 shrink-0 object-contain {{ $activeState ? 'brightness-0 invert' : 'opacity-60' }}" onerror="this.style.display='none'">
                                <span class="whitespace-nowrap overflow-hidden text-ellipsis truncate">{{ $menu['label'] }}</span>
                            </div>

                            @if($menu['label'] == 'Messages' || $menu['label'] == 'Pesan')
                                <span class="ml-auto px-2 py-0.5 text-[11px] font-bold rounded-full shrink-0 {{ $activeState ? 'bg-white text-[#2F80ED]' : 'bg-orange-500 text-white' }}">
                                    12
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>

    {{-- Bagian Bawah: Get Pro Card --}}
    <div class="p-6 pt-0 shrink-0 w-full">
        <a href="#" class="bg-black text-white p-4 rounded-3xl flex items-center gap-3 hover:bg-gray-800 transition-all cursor-pointer">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-purple-600 fill-current" viewBox="0 0 24 24">
                    <path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.6 18.6 20 18 20H6C5.4 20 5 19.6 5 19V18H19V19Z" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <h4 class="font-bold text-sm text-white whitespace-nowrap">Get Pro</h4>
                <p class="text-xs text-gray-400 whitespace-nowrap">Unlock features</p>
            </div>
        </a>
    </div>
</aside>