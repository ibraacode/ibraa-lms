<x-layouts.dashboard title="Manajemen Pengumuman — IbraLMS">
    <div class="w-full max-w-7xl mx-auto space-y-6 pt-4" x-data="{ 
        open: false, 
        detail: { title: '', content: '', date: '', author: '' } 
    }">
        
        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-foreground tracking-tight">Manajemen Pengumuman</h1>
                <p class="text-sm text-foreground/60 mt-1">Kelola informasi dan pengumuman sekolah.</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="px-6 py-3 rounded-full bg-[#2F80ED] text-white text-sm font-bold shadow-lg shadow-[#2F80ED]/30 hover:bg-[#1C6DD0] transition-all flex items-center gap-2">
                + Buat Pengumuman
            </a>
        </div>

        {{-- TABEL --}}
        <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-4 px-6 text-sm font-medium text-foreground/60 w-[45%]">Pengumuman</th>
                            <th class="py-4 px-6 text-sm font-medium text-foreground/60 w-[20%]">Tanggal Dibuat</th>
                            <th class="py-4 px-6 text-sm font-medium text-foreground/60 w-[20%] text-center">Target</th>
                            <th class="py-4 px-6 text-sm font-medium text-foreground/60 w-[15%] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($announcements as $a)
                            <tr class="hover:bg-muted/30 transition-colors">
                                
                                {{-- Kolom 1: Ikon & Judul --}}
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        {{-- Ikon Pengumuman Kustom --}}
                                        <div class="w-12 h-12 rounded-full bg-[#2F80ED]/10 flex items-center justify-center shrink-0 p-2.5">
                                            <img src="{{ asset('assets/pengumuman.png') }}" alt="Pengumuman" class="w-full h-full object-contain">
                                        </div>
                                        
                                        {{-- Judul & Preview --}}
                                        <div class="min-w-0 cursor-pointer" 
                                             data-title="{{ $a->title }}"
                                             data-content="{{ $a->content }}"
                                             data-date="{{ $a->created_at->format('d F Y') }}"
                                             data-author="{{ $a->author->name }}"
                                             @click="
                                                 detail.title = $el.dataset.title;
                                                 detail.content = $el.dataset.content;
                                                 detail.date = $el.dataset.date;
                                                 detail.author = $el.dataset.author;
                                                 open = true;
                                             ">
                                            <p class="font-bold text-base text-foreground hover:text-[#2F80ED] transition-colors truncate">{{ $a->title }}</p>
                                            <p class="text-sm text-foreground/50 truncate">{{ Str::limit(strip_tags($a->content), 50) }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Kolom 2: Tanggal --}}
                                <td class="py-4 px-6">
                                    <p class="font-semibold text-sm text-foreground">{{ $a->created_at->format('d F Y') }}</p>
                                </td>

                                {{-- Kolom 3: Badge Target --}}
                                <td class="py-4 px-6 text-center">
                                    @php
                                        $badgeColors = [
                                            'semua' => 'bg-orange-100 text-orange-600',
                                            'guru' => 'bg-blue-100 text-blue-600',
                                            'siswa' => 'bg-green-100 text-green-600',
                                        ];
                                        $badgeClass = $badgeColors[$a->target_role] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full {{ $badgeClass }} font-bold text-xs uppercase tracking-wide">
                                        {{ $a->target_role }}
                                    </span>
                                </td>

                                {{-- Kolom 4: Dropdown Aksi --}}
                                <td class="py-4 px-6 text-right">
                                    <div class="relative inline-block text-left" x-data="{ menuOpen: false }">
                                        <button @click="menuOpen = !menuOpen" @click.away="menuOpen = false" 
                                                class="inline-flex items-center justify-between gap-2 px-4 py-2 bg-background border border-border rounded-full text-xs font-bold text-foreground hover:bg-muted transition-all w-[90px]">
                                            menu
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>

                                        {{-- Dropdown Menu --}}
                                        <div x-show="menuOpen" 
                                             x-transition.opacity
                                             class="absolute right-0 mt-2 w-48 bg-card rounded-xl shadow-xl border border-border py-2 z-50 text-left"
                                             x-cloak>
                                            
                                            <a href="{{ route('admin.announcements.edit', $a->id) }}" class="block px-4 py-2 text-sm font-medium text-foreground hover:bg-muted hover:text-[#2F80ED] transition-colors">
                                                Edit Pengumuman
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.announcements.destroy', $a->id) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center text-foreground/50">
                                    <div class="flex flex-col items-center justify-center">
                                        <img src="{{ asset('assets/pengumuman.png') }}" alt="Empty" class="w-20 h-20 mb-4 opacity-30 grayscale">
                                        <p>Belum ada pengumuman yang dipublikasikan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINASI --}}
        @if($announcements->hasPages())
            <div class="flex gap-3 items-center pt-2 pb-8">
                {{-- Previous --}}
                @if ($announcements->onFirstPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-full border border-border text-foreground/30 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </span>
                @else
                    <a href="{{ $announcements->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-border text-foreground/60 hover:bg-foreground hover:text-background transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                @endif

                {{-- Number --}}
                @foreach ($announcements->links()->elements[0] as $page => $url)
                    @if ($page == $announcements->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-full bg-foreground text-background font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-border text-foreground/60 font-semibold hover:bg-foreground hover:text-background transition-all">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($announcements->hasMorePages())
                    <a href="{{ $announcements->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-full border border-border text-foreground/60 hover:bg-foreground hover:text-background transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="w-10 h-10 flex items-center justify-center rounded-full border border-border text-foreground/30 cursor-not-allowed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
        @endif

        {{-- MODAL DETAIL --}}
        <template x-teleport="body">
            <div x-show="open" x-cloak class="fixed inset-0 z-[9999] overflow-y-auto" @keydown.escape.window="open = false">
                <div class="flex min-h-full items-center justify-center p-4 sm:p-6 text-center">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" x-show="open" x-transition.opacity @click="open = false"></div>
                    <div class="relative bg-card w-full max-w-3xl max-h-[85vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden text-left" 
                         x-show="open" 
                         x-transition:enter="transition ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-8 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                        
                        <div class="p-6 border-b border-border flex justify-between items-start bg-muted/30 shrink-0">
                            <div>
                                <h2 class="text-2xl font-bold text-primary" x-text="detail.title"></h2>
                                <p class="text-sm text-foreground/60 mt-1">
                                    <span x-text="detail.date"></span> • Oleh: <span class="font-bold" x-text="detail.author"></span>
                                </p>
                            </div>
                            <button @click="open = false" class="p-2 bg-card rounded-full border border-border hover:bg-muted text-foreground transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-6 overflow-y-auto prose prose-sm md:prose-base max-w-none text-foreground/80" x-html="detail.content"></div>
                        <div class="p-4 border-t border-border flex justify-end bg-muted/10 shrink-0">
                            <button @click="open = false" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg font-bold hover:opacity-90 transition-opacity">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.dashboard>