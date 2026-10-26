<x-layouts.public title="IbraLMS — Sistem Manajemen Sekolah Masa Kini">
    <div class="min-h-screen flex flex-col font-poppins bg-background">
        
        {{-- ================= NAVBAR ================= --}}
        <nav class="sticky top-0 z-50 border-b border-border/50 bg-background/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-white">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2z" />
                        </svg>
                    </div>
                    <span class="font-extrabold text-xl text-foreground tracking-tight">IbraLMS</span>
                </a>

                {{-- Menu Desktop --}}
                <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-foreground/70">
                    <a href="#fitur" class="hover:text-primary transition-colors">Fitur</a>
                    <a href="#peran" class="hover:text-primary transition-colors">Untuk Siapa?</a>
                    <a href="#harga" class="hover:text-primary transition-colors">Harga</a>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 sm:gap-4">
                    <button @click="dark = !dark" class="p-2 rounded-lg hover:bg-muted transition-colors text-foreground">
                        <img x-show="!dark" src="{{ asset('icons/moon-stars.svg') }}" alt="Dark" class="w-5 h-5 opacity-70">
                        <img x-show="dark" src="{{ asset('icons/sun.svg') }}" alt="Light" class="w-5 h-5 opacity-70">
                    </button>

                    <a href="{{ route('login') }}" class="hidden sm:inline-block px-4 py-2 text-sm font-bold text-foreground hover:text-primary transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full bg-primary hover:bg-primary/90 text-primary-foreground text-sm font-bold shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                        Coba Gratis
                    </a>
                </div>
            </div>
        </nav>

        {{-- ================= HERO SECTION ================= --}}
        <section class="relative pt-20 pb-32 overflow-hidden">
            {{-- Background Glow --}}
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] opacity-50 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 text-primary text-xs font-bold mb-6">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Versi 2.0 Telah Rilis
                </div>

                <h1 class="text-4xl sm:text-6xl font-extrabold text-foreground tracking-tight leading-tight mb-6 max-w-4xl mx-auto">
                    Kelola Sekolah Anda <br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">Tanpa Ribet.</span>
                </h1>
                
                <p class="text-lg sm:text-xl text-foreground/60 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Platform manajemen sekolah masa kini untuk SMA & SMK. Kelola akademik, PKL, keuangan, dan absensi dalam satu sistem yang modern, aman, dan terintegrasi.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-primary text-primary-foreground font-bold shadow-xl shadow-primary/30 hover:scale-105 transition-transform flex items-center justify-center gap-2">
                        Mulai Sekarang
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="#fitur" class="w-full sm:w-auto px-8 py-4 rounded-full bg-card border border-border text-foreground font-bold hover:bg-muted transition-colors flex items-center justify-center gap-2">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            {{-- Mockup Dashboard --}}
            <div class="max-w-5xl mx-auto px-4 mt-20 relative z-10">
                <div class="rounded-2xl border border-border bg-card p-2 shadow-2xl shadow-primary/10">
                    <div class="rounded-xl bg-muted/30 border border-border/50 p-4 sm:p-8 aspect-video flex items-center justify-center overflow-hidden relative">
                        {{-- Elemen Dekoratif Mockup --}}
                        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <img src="{{ asset('icons/dashboard.svg') }}" class="w-10 h-10 object-contain" alt="Dashboard">
                            </div>
                            <h3 class="text-xl font-bold text-foreground">Dashboard Analitik</h3>
                            <p class="text-sm text-foreground/60">Pantau semua data sekolah secara real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= FITUR (BENTO GRID) ================= --}}
        <section id="fitur" class="py-24 bg-muted/30 border-y border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-foreground mb-4">Satu Sistem untuk Semua Kebutuhan</h2>
                    <p class="text-foreground/60 max-w-2xl mx-auto">Tidak perlu lagi aplikasi terpisah. IbraLMS menggabungkan seluruh operasional sekolah dalam satu platform cerdas.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Kartu Besar --}}
                    <div class="md:col-span-2 bg-card border border-border rounded-3xl p-8 relative overflow-hidden group hover:border-primary/50 transition-colors">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center mb-6">
                                <img src="{{ asset('icons/computer-camera.svg') }}" class="w-6 h-6 object-contain" alt="Icon">
                            </div>
                            <h3 class="text-2xl font-bold mb-3">Absensi QR & GPS Cerdas</h3>
                            <p class="text-foreground/60 leading-relaxed">Siswa cukup scan QR Code untuk absensi. Khusus PKL, sistem dilengkapi dengan validasi titik GPS dan foto selfie agar data kehadiran 100% akurat.</p>
                        </div>
                    </div>

                    {{-- Kartu Kecil 1 --}}
                    <div class="bg-card border border-border rounded-3xl p-8 relative overflow-hidden group hover:border-primary/50 transition-colors">
                        <div class="relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-green-500/10 flex items-center justify-center mb-6">
                                <img src="{{ asset('icons/bank.svg') }}" class="w-6 h-6 object-contain" alt="Icon">
                            </div>
                            <h3 class="text-xl font-bold mb-3">Keuangan Terpadu</h3>
                            <p class="text-sm text-foreground/60">Kelola SPP, dana BOS, dan tagihan lainnya secara transparan.</p>
                        </div>
                    </div>

                    {{-- Kartu Kecil 2 --}}
                    <div class="bg-card border border-border rounded-3xl p-8 relative overflow-hidden group hover:border-primary/50 transition-colors">
                        <div class="relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-orange-500/10 flex items-center justify-center mb-6">
                                <img src="{{ asset('icons/award.svg') }}" class="w-6 h-6 object-contain" alt="Icon">
                            </div>
                            <h3 class="text-xl font-bold mb-3">Rapor & Karakter</h3>
                            <p class="text-sm text-foreground/60">Nilai akademik dan penilaian sikap spiritual/sosial dalam satu klik.</p>
                        </div>
                    </div>

                    {{-- Kartu Besar --}}
                    <div class="md:col-span-2 bg-card border border-border rounded-3xl p-8 relative overflow-hidden group hover:border-primary/50 transition-colors">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center mb-6">
                                <img src="{{ asset('icons/calendar.svg') }}" class="w-6 h-6 object-contain" alt="Icon">
                            </div>
                            <h3 class="text-2xl font-bold mb-3">Multi-Tahun Ajaran</h3>
                            <p class="text-foreground/60 leading-relaxed">Data tahun ajaran lama otomatis terkunci (Read-Only). Naik kelas massal dan kelulusan siswa cukup dengan satu tombol. Tidak ada data yang tercampur.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= CTA SECTION ================= --}}
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-primary"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
                <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Siap Membawa Sekolah Anda ke Era Digital?</h2>
                <p class="text-primary-foreground/80 text-lg mb-10">Bergabunglah dengan sekolah-sekolah modern yang telah beralih ke IbraLMS.</p>
                <a href="{{ route('login') }}" class="inline-block px-10 py-4 rounded-full bg-white text-primary font-bold text-lg hover:scale-105 transition-transform shadow-2xl">
                    Mulai Sekarang
                </a>
            </div>
        </section>

        {{-- ================= FOOTER ================= --}}
        <footer class="bg-background border-t border-border pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-2">
                        <a href="/" class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-white">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2z" /></svg>
                            </div>
                            <span class="font-extrabold text-xl text-foreground tracking-tight">IbraLMS</span>
                        </a>
                        <p class="text-foreground/60 text-sm max-w-sm">Platform manajemen sekolah modular yang dirancang khusus untuk memenuhi kebutuhan SMA & SMK di Indonesia.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-foreground mb-4">Produk</h4>
                        <ul class="space-y-2 text-sm text-foreground/60">
                            <li><a href="#" class="hover:text-primary transition-colors">Fitur</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Harga</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Dokumentasi</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold text-foreground mb-4">Bantuan</h4>
                        <ul class="space-y-2 text-sm text-foreground/60">
                            <li><a href="#" class="hover:text-primary transition-colors">Pusat Bantuan</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Hubungi Kami</a></li>
                            <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-border pt-8 text-center text-sm text-foreground/50">
                    &copy; {{ date('Y') }} IbraLMS. Dibuat dengan ❤️ untuk Pendidikan Indonesia.
                </div>
            </div>
        </footer>

    </div>
</x-layouts.public>