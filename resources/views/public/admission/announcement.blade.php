<x-layouts.public title="Pengumuman PPDB">
    <div class="min-h-screen bg-background py-10 px-4 flex items-center justify-center">
        <div class="max-w-lg w-full">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <img src="{{ asset('icons/megaphone.svg') }}" class="w-10 h-10 object-contain" alt="Icon">
                </div>
                <h1 class="text-3xl font-extrabold text-foreground">Pengumuman PPDB</h1>
                <p class="text-foreground/60 mt-2">{{ \App\Models\Setting::get('school_name', 'IbraLMS') }}</p>
                <p class="text-sm text-foreground/60">Tahun Ajaran {{ \App\Models\Setting::get('active_academic_year') }}</p>
            </div>

            {{-- Konten --}}
            <div class="bg-card border border-border rounded-2xl p-6 md:p-8 shadow-lg">
                
                @if(!$isOpen)
                    {{-- Belum dibuka --}}
                    <div class="text-center">
                        <div class="text-6xl mb-4">⏳</div>
                        <h2 class="text-xl font-bold text-foreground mb-2">Pengumuman Belum Dibuka</h2>
                        <p class="text-sm text-foreground/60 mb-6">
                            Hasil seleksi akan diumumkan pada:
                        </p>
                        <p class="text-lg font-bold text-primary mb-6">
                            {{ \Carbon\Carbon::parse($announcementDate)->isoFormat('dddd, D MMMM Y') }}
                        </p>
                        <a href="{{ url('/') }}" class="inline-block px-6 py-2 bg-primary text-primary-foreground rounded-lg font-medium">
                            Kembali ke Beranda
                        </a>
                    </div>
                @else
                    {{-- Sudah dibuka --}}
                    <h2 class="text-xl font-bold text-center mb-6">Cek Hasil Seleksi Anda</h2>
                    
                    @if ($errors->any())
                        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admission.check') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">NISN</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" required placeholder="Masukkan NISN Anda" class="w-full px-4 py-3 rounded-xl border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Masukkan Nama Lengkap sesuai pendaftaran" class="w-full px-4 py-3 rounded-xl border border-border bg-background text-sm">
                        </div>
                        <button type="submit" class="w-full py-3 bg-primary text-primary-foreground rounded-xl font-bold hover:opacity-90">
                            Cek Hasil Kelulusan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.public>