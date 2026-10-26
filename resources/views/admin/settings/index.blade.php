<x-layouts.dashboard title="Pengaturan Sistem — IbraLMS">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Pengaturan Sistem</h1>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- ================= 1. IDENTITAS SEKOLAH ================= --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 shadow-sm">
                <h2 class="font-bold text-lg border-b border-border pb-2">1. Identitas Sekolah</h2>
                
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-20 h-20 bg-muted rounded-lg flex items-center justify-center overflow-hidden border border-border">
                        @if($settings['school_logo'])
                            <img src="{{ Storage::url($settings['school_logo']) }}" class="w-full h-full object-contain" alt="Logo">
                        @else
                            <span class="text-xs text-foreground/40">No Logo</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Logo Sekolah</label>
                        <input type="file" name="school_logo" accept="image/png,image/jpeg" class="w-full text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary/10 file:text-primary file:font-medium">
                        <p class="text-xs text-foreground/50 mt-1">Format: JPG/PNG, Maks 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Nama Sekolah</label>
                        <input type="text" name="school_name" value="{{ $settings['school_name'] }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
                        <input type="text" name="school_phone" value="{{ $settings['school_phone'] }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tahun Ajaran Aktif</label>
                        <input type="text" name="active_academic_year" value="{{ $settings['active_academic_year'] }}" required placeholder="2026/2027" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipe Sekolah</label>
                        <select name="school_type" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="negeri" {{ $settings['school_type'] == 'negeri' ? 'selected' : '' }}>Negeri (Gratis / BOS)</option>
                            <option value="swasta" {{ $settings['school_type'] == 'swasta' ? 'selected' : '' }}>Swasta (SPP Mandiri)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Alamat Sekolah</label>
                        <textarea name="school_address" rows="2" class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ $settings['school_address'] }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ================= 2. PENGATURAN PPDB ================= --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 shadow-sm">
                <div class="border-b border-border pb-2">
                    <h2 class="font-bold text-lg">2. Pengaturan PPDB (Penerimaan Siswa Baru)</h2>
                    <p class="text-xs text-foreground/50 mt-1">Atur alur dan kuota pendaftaran siswa baru.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Mode PPDB</label>
                        <select name="ppdb_mode" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="swasta" {{ $settings['ppdb_mode'] == 'swasta' ? 'selected' : '' }}>Swasta (Buka Pendaftaran Mandiri)</option>
                            <option value="negeri" {{ $settings['ppdb_mode'] == 'negeri' ? 'selected' : '' }}>Negeri (Hanya Daftar Ulang / Import)</option>
                        </select>
                        <p class="text-xs text-foreground/50 mt-1">
                            Mode Swasta: Pendaftar mengisi form dari web sekolah.<br>
                            Mode Negeri: Siswa hanya daftar ulang, data diinput Admin.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Kuota Siswa Diterima</label>
                        <input type="number" name="ppdb_quota" value="{{ $settings['ppdb_quota'] }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <p class="text-xs text-foreground/50 mt-1">Jumlah maksimal siswa yang akan diterima.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Biaya Pendaftaran (Rp)</label>
                        <input type="number" name="ppdb_fee" value="{{ $settings['ppdb_fee'] }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <p class="text-xs text-foreground/50 mt-1">Isi 0 jika gratis (sekolah negeri).</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Buka Pendaftaran</label>
                        <input type="date" name="ppdb_registration_start" value="{{ $settings['ppdb_registration_start'] }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Tutup Pendaftaran</label>
                        <input type="date" name="ppdb_registration_end" value="{{ $settings['ppdb_registration_end'] }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Pengumuman</label>
                        <input type="date" name="ppdb_announcement_date" value="{{ $settings['ppdb_announcement_date'] }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-bold hover:opacity-90 transition-all shadow-md">
                Simpan Pengaturan
            </button>
        </form>
    </div>
</x-layouts.dashboard>