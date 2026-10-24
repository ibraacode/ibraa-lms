<x-layouts.public title="IbraLMS — Beranda">
    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        <nav class="border-b border-border bg-card">
            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
                <a href="/" class="font-bold text-xl text-primary">IbraLMS</a>

                <div class="flex items-center gap-2">
                    <button @click="dark = !dark"
                            class="p-2 rounded-lg hover:bg-muted"
                            title="Ganti tema">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>

                    <a href="{{ route('locale.switch', 'id') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium
                              {{ app()->getLocale() === 'id' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted' }}">
                        ID
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="px-3 py-1.5 rounded-lg text-sm font-medium
                              {{ app()->getLocale() === 'en' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted' }}">
                        EN
                    </a>
                </div>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center px-4">
            <div class="text-center max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4">
                    {{ __('messages.welcome') }}
                </h1>
                <p class="text-lg text-foreground/70 mb-8">
                    {{ app()->getLocale() === 'id' 
                        ? 'Platform manajemen sekolah modular untuk SMA & SMK.' 
                        : 'Modular school management platform for high schools.' }}
                </p>
                <a href="#"
                   class="inline-block px-6 py-3 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    {{ __('messages.login') }}
                </a>
            </div>
        </main>

        <footer class="border-t border-border py-4 text-center text-sm text-foreground/60">
            &copy; {{ date('Y') }} IbraLMS
        </footer>

    </div>
</x-layouts.public>