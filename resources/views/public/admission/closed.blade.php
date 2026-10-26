<x-layouts.public title="Pendaftaran Ditutup">
    <div class="min-h-screen flex items-center justify-center px-4 bg-background">
        <div class="max-w-md w-full bg-card border border-border rounded-xl p-8 text-center shadow-lg">
            <div class="text-6xl mb-4">🔒</div>
            <h1 class="text-2xl font-bold text-foreground mb-2">Pendaftaran Tidak Tersedia</h1>
            <p class="text-foreground/70 mb-6">{{ $message }}</p>
            <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:opacity-90">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.public>