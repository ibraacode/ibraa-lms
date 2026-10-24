<x-layouts.public title="Pendaftaran Berhasil">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="max-w-md w-full bg-card border border-border rounded-xl p-8 text-center shadow-lg">
            <div class="text-6xl mb-4">✅</div>
            <h1 class="text-2xl font-bold text-primary mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-foreground/70 mb-6">Terima kasih, <b>{{ $admission->full_name }}</b> telah mendaftar.</p>

            <div class="bg-primary/10 border border-primary/20 rounded-xl p-4 mb-6">
                <p class="text-xs text-foreground/60 mb-1">Nomor Pendaftaran Anda</p>
                <p class="text-2xl font-mono font-bold text-primary">{{ $admission->registration_number }}</p>
            </div>

            <p class="text-sm text-foreground/60 mb-6">Simpan nomor ini. Anda akan dihubungi oleh pihak sekolah untuk proses selanjutnya.</p>

            <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-primary text-primary-foreground rounded-lg font-medium hover:opacity-90 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-layouts.public>