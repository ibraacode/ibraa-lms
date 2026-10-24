<x-layouts.public title="Pendaftaran Siswa Baru">
    <div class="min-h-screen bg-background py-10 px-4">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary">{{ \App\Models\Setting::get('school_name', 'IbraLMS') }}</h1>
                <p class="text-lg font-semibold text-foreground/80 mt-2">Formulir Pendaftaran Siswa Baru</p>
                <p class="text-sm text-foreground/60">Tahun Ajaran {{ \App\Models\Setting::get('active_academic_year', '2026/2027') }}</p>
            </div>

            <form method="POST" action="{{ route('admission.store') }}" class="bg-card border border-border rounded-xl p-6 md:p-8 space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
                @endif

                <h2 class="font-bold text-lg text-primary border-b border-border pb-2">Data Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Nama Lengkap *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jenis Kelamin *</label>
                        <select name="gender" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Asal Sekolah</label>
                        <input type="text" name="previous_school" value="{{ old('previous_school') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>

                <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mt-6">Kontak</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">No. HP Siswa/Orang Tua *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background">{{ old('address') }}</textarea>
                    </div>
                </div>

                <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mt-6">Data Orang Tua & Pilihan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Nama Orang Tua/Wali</label>
                        <input type="text" name="parent_name" value="{{ old('parent_name') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No. HP Orang Tua</label>
                        <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Jurusan Pilihan</label>
                        <input type="text" name="chosen_major" value="{{ old('chosen_major') }}" placeholder="Contoh: IPA, IPS, TKJ" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>

                <div class="pt-4 border-t border-border">
                    <button type="submit" class="w-full py-3 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.public>