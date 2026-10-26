<x-layouts.public title="Hasil Kelulusan">
    <div class="min-h-screen bg-background py-10 px-4 flex items-center justify-center">
        <div class="max-w-lg w-full">
            
            @if($admission->status === 'accepted')
                {{-- LULUS --}}
                <div class="bg-card border border-border rounded-3xl p-8 shadow-2xl text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-400 to-green-600"></div>
                    
                    <div class="text-7xl mb-4">🎉</div>
                    <h1 class="text-3xl font-extrabold text-green-600 mb-2">SELAMAT!</h1>
                    <p class="text-lg font-bold text-foreground">Anda Dinyatakan <span class="text-green-600">LULUS</span></p>
                    <p class="text-sm text-foreground/60 mt-1">Seleksi PPDB {{ \App\Models\Setting::get('active_academic_year') }}</p>

                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 my-6 text-left">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-700/60">Nama Lengkap</span>
                            <span class="font-bold text-green-700">{{ $admission->full_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-700/60">NISN</span>
                            <span class="font-bold text-green-700">{{ $admission->nisn }}</span>
                        </div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-green-700/60">No. Pendaftaran</span>
                            <span class="font-bold text-green-700">{{ $admission->registration_number }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-green-700/60">Jurusan Diterima</span>
                            <span class="font-bold text-green-700">{{ $admission->chosen_major }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admission.skl-print', $admission->id) }}" target="_blank" class="block w-full py-3 bg-primary text-primary-foreground rounded-xl font-bold hover:opacity-90">
                            🖨️ Cetak Surat Keterangan Lulus (SKL)
                        </a>
                        <a href="{{ route('admission.form') }}" class="block w-full py-2 text-foreground/60 text-sm hover:text-primary">
                            Kembali ke Halaman Pendaftaran
                        </a>
                    </div>
                </div>

            @elseif($admission->status === 'rejected')
                {{-- TIDAK LULUS --}}
                <div class="bg-card border border-border rounded-3xl p-8 shadow-2xl text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-red-500"></div>
                    
                    <div class="text-7xl mb-4">😔</div>
                    <h1 class="text-3xl font-extrabold text-red-500 mb-2">MOHON MAAF</h1>
                    <p class="text-lg font-bold text-foreground">Anda Dinyatakan <span class="text-red-500">TIDAK LULUS</span></p>
                    <p class="text-sm text-foreground/60 mt-1">Seleksi PPDB {{ \App\Models\Setting::get('active_academic_year') }}</p>

                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 my-6 text-left">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-red-700/60">Nama Lengkap</span>
                            <span class="font-bold text-red-700">{{ $admission->full_name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-red-700/60">NISN</span>
                            <span class="font-bold text-red-700">{{ $admission->nisn }}</span>
                        </div>
                    </div>

                    <p class="text-sm text-foreground/60 mb-6">
                        Jangan menyerah! Silakan mencoba mendaftar lagi di tahun ajaran berikutnya atau sekolah lain. Semangat!
                    </p>

                    <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-muted text-foreground rounded-lg font-medium hover:bg-border">
                        Kembali ke Beranda
                    </a>
                </div>

            @else
                {{-- MASIH PROSES --}}
                <div class="bg-card border border-border rounded-3xl p-8 shadow-2xl text-center">
                    <div class="text-7xl mb-4">⏳</div>
                    <h1 class="text-2xl font-bold mb-2">Masih Dalam Proses</h1>
                    <p class="text-sm text-foreground/60 mb-6">
                        Pendaftaran Anda dengan nomor <b>{{ $admission->registration_number }}</b> masih dalam proses seleksi oleh panitia.
                        Silakan cek kembali nanti.
                    </p>
                    <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-muted text-foreground rounded-lg font-medium hover:bg-border">
                        Kembali ke Beranda
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>