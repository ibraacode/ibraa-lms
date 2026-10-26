@props(['type' => 'success', 'message' => ''])

<div x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 50); setTimeout(() => show = false, 4000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-8 scale-95"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0 scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed top-24 right-4 sm:right-6 z-[999999] flex items-center gap-3 p-4 w-full max-w-[360px] bg-card/95 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-border"
     x-cloak>
    
    {{-- Ikon --}}
    @if($type === 'success')
        <div class="flex items-center justify-center shrink-0 w-9 h-9 text-green-600 bg-green-100 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
    @else
        <div class="flex items-center justify-center shrink-0 w-9 h-9 text-red-600 bg-red-100 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
    @endif

    {{-- Pesan --}}
    <div class="flex-1 min-w-0">
        <p class="text-sm font-bold text-foreground leading-tight">{{ $message }}</p>
    </div>
    
    {{-- Tombol Tutup --}}
    <button @click="show = false" class="shrink-0 p-1.5 text-foreground/40 hover:text-foreground bg-transparent hover:bg-muted rounded-full transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>