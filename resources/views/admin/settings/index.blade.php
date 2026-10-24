<x-layouts.dashboard title="Pengaturan Sistem — IbraLMS">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Pengaturan Identitas Sekolah</h1>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
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

            <div>
                <label class="block text-sm font-medium mb-1">Nama Sekolah</label>
                <input type="text" name="school_name" value="{{ $settings['school_name'] }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Alamat Sekolah</label>
                <textarea name="school_address" rows="3" class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ $settings['school_address'] }}</textarea>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                Simpan Pengaturan
            </button>
        </form>
    </div>
</x-layouts.dashboard>