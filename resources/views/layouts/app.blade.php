@php
    $menus = \App\Support\MenuBuilder::forUser();
@endphp

<aside 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-50 w-[270px] shrink-0 flex flex-col justify-between p-5 md:p-[30px] border-r border-border bg-card transition-transform duration-300 md:translate-x-0 md:static md:h-screen overflow-y-auto"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="w-full flex flex-col gap-[30px]">
        
        {{-- 1. LOGO AREA --}}
        <div class="flex items-center justify-between h-10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                @if(\App\Models\Setting::get('school_logo'))
                    <img src="{{ Storage::url(\App\Models\Setting::get('school_logo')) }}" alt="logo" class="h-8 object-contain">
                @else
                    <span class="w-8 h-8 bg-primary text-white rounded-lg flex items-center justify-center font-bold text-lg">I</span>
                    <span class="font-bold text-lg text-foreground">SMA IbraLMS</span>
                @endif
            </a>
            
            <button @click="sidebarOpen = false" class="md:hidden p-1 text-muted-foreground hover:text-foreground">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- 2. MENU UTAMA --}}
        <ul class="flex flex-col gap-3">
            <li>
                <h3 class="font-bold text-xs text-muted-foreground uppercase tracking-wider">DAILY USE</h3>
            </li>
            
            @foreach ($menus as $menu)
                @if (! empty($menu['children']))
                    {{-- Menu Punya Submenu --}}
                    <li x-data="{ open: {{ \App\Support\MenuBuilder::isActive($menu) ? 'true' : 'false' }} }">
                        <button @click="open = !open" class="w-full p-[10px_16px] flex items-center justify-between rounded-full min-h-[44px] transition-all duration-300 hover:bg-primary group {{ \App\Support\MenuBuilder::isActive($menu) ? 'bg-primary' : '' }}">
                            <div class="flex items-center gap-[14px] overflow-hidden">
                                <img src="{{ asset('icons/' . ($menu['icon'] ?? 'circle') . '.svg') }}" alt="icon" class="w-5 h-5 shrink-0 object-contain" onerror="this.style.display='none'">
                                <span class="font-semibold text-sm whitespace-nowrap overflow-hidden text-ellipsis transition-all duration-300 group-hover:text-primary-foreground {{ \App\Support\MenuBuilder::isActive($menu) ? 'text-primary-foreground' : 'text-foreground' }}">
                                    {{ $menu['label'] }}
                                </span>
                            </div>
                            <span x-show="!open" class="text-xs shrink-0 transition-all duration-300 group-hover:text-primary-foreground {{ \App\Support\MenuBuilder::isActive($menu) ? 'text-primary-foreground' : 'text-foreground' }}">▸</span>
                            <span x-show="open" class="text-xs shrink-0 transition-all duration-300 group-hover:text-primary-foreground {{ \App\Support\MenuBuilder::isActive($menu) ? 'text-primary-foreground' : 'text-foreground' }}">▾</span>
                        </button>

                        <div x-show="open" x-collapse class="flex flex-col gap-1 mt-2 pl-4">
                            @foreach ($menu['children'] as $child)
                                @php $childActive = request()->routeIs($child['route']); @endphp
                                <a href="{{ $child['url'] }}" class="p-[8px_14px] flex items-center gap-[12px] rounded-full min-h-[36px] transition-all duration-300 hover:bg-primary group {{ $childActive ? 'bg-primary' : '' }}">
                                    <img src="{{ asset('icons/' . ($child['icon'] ?? 'circle') . '.svg') }}" alt="icon" class="w-4 h-4 shrink-0 object-contain" onerror="this.style.display='none'">
                                    <span class="text-sm font-semibold whitespace-nowrap overflow-hidden text-ellipsis transition-all duration-300 group-hover:text-primary-foreground {{ $childActive ? 'text-primary-foreground' : 'text-foreground' }}">
                                        {{ $child['label'] }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </li>
                @else
                    {{-- Menu Tanpa Submenu --}}
                    @php $active = request()->routeIs($menu['route']); @endphp
                    <li>
                        <a href="{{ $menu['url'] }}" class="p-[10px_16px] flex items-center justify-between rounded-full min-h-[44px] transition-all duration-300 hover:bg-primary group {{ $active ? 'bg-primary' : '' }}">
                            <div class="flex items-center gap-[14px] overflow-hidden w-full">
                                <img src="{{ asset('icons/' . ($menu['icon'] ?? 'circle') . '.svg') }}" alt="icon" class="w-5 h-5 shrink-0 object-contain" onerror="this.style.display='none'">
                                <span class="font-semibold text-sm whitespace-nowrap overflow-hidden text-ellipsis transition-all duration-300 group-hover:text-primary-foreground {{ $active ? 'text-primary-foreground' : 'text-foreground' }}">
                                    {{ $menu['label'] }}
                                </span>
                                
                                @if($menu['label'] == 'Pesan' || $menu['label'] == 'Messages')
                                <div class="ml-auto w-5 h-5 shrink-0 flex rounded-full items-center justify-center bg-orange-500">
                                    <span class="font-bold text-[10px] text-white">12</span>
                                </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- 3. GET PRO CARD --}}
        <div class="mt-auto pt-6">
            <a href="#">
                <div class="w-full flex gap-3 items-center p-4 rounded-[14px] bg-foreground text-background">
                    <div class="shrink-0">
                        <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5L8.5 10L12 4L15.5 10L21 5L19 16H5M19 19C19 19.6 18.6 20 18 20H6C5.4 20 5 19.6 5 19V18H19V19Z" /></svg>
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <p class="font-semibold text-sm text-background whitespace-nowrap">Get Pro</p>
                        <p class="text-xs leading-[21px] opacity-70 text-background whitespace-nowrap">Unlock features</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden" x-cloak></div>