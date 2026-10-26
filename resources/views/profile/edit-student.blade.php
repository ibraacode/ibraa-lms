<x-layouts.dashboard title="Profil Siswa — IbraLMS">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Profil & Biodata Siswa</h1>
            @if($user->studentProfile?->is_verified)
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">✓ Data Terverifikasi</span>
            @endif
        </div>

        @if (session('success'))
            <div class="p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
        @endif

        @if($user->studentProfile?->is_verified)
            <div class="p-4 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 text-sm mb-6">
                <b>Perhatian:</b> Data Anda sudah difinalisasi oleh Admin. Jika ada kekeliruan, silakan hubungi bagian Tata Usaha.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-student') }}">
            @csrf
            @php $readonly = $user->studentProfile?->is_verified ? 'readonly disabled bg-gray-100 cursor-not-allowed' : ''; @endphp

            {{-- DATA AKUN --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">1. Data Akun Login</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Email (Tidak dapat diubah)</label>
                        <input type="email" value="{{ $user->email }}" readonly class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No. HP Siswa</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">NIS</label>
                        <input type="text" value="{{ $user->nis }}" readonly class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- BIODATA SISWA --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">2. Biodata Diri</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->studentProfile->nik ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="kk_number" value="{{ old('kk_number', $user->studentProfile->kk_number ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Agama</label>
                        <select name="religion" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agama)
                                <option value="{{ $agama }}" {{ ($user->studentProfile->religion ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jenis Kelamin</label>
                        <select name="gender" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="L" {{ ($user->studentProfile->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ ($user->studentProfile->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $user->studentProfile->birth_place ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $user->studentProfile && $user->studentProfile->birth_date ? $user->studentProfile->birth_date->format('Y-m-d') : '' }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Anak Ke-</label>
                        <input type="number" name="child_order" value="{{ old('child_order', $user->studentProfile->child_order ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jumlah Saudara</label>
                        <input type="number" name="siblings_count" value="{{ old('siblings_count', $user->studentProfile->siblings_count ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Tinggal Bersama</label>
                        <select name="living_with" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Orang Tua', 'Wali', 'Kerabat', 'Asrama', 'Panti Asuhan', 'Sendiri'] as $opsi)
                                <option value="{{ $opsi }}" {{ ($user->studentProfile->living_with ?? '') == $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="2" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ old('address', $user->studentProfile->address ?? '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Kebutuhan Khusus / Disabilitas (Opsional)</label>
                        <input type="text" name="special_needs" value="{{ old('special_needs', $user->studentProfile->special_needs ?? '') }}" {{ $readonly }} placeholder="Kosongkan jika tidak ada" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>
            </div>

            {{-- DATA ORANG TUA --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">3. Data Orang Tua</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Ayah --}}
                    <div class="md:col-span-2"><h3 class="font-bold text-primary text-sm">Data Ayah Kandung</h3></div>
                    <div>
                        <label class="block text-sm mb-1">Nama Ayah</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $user->studentProfile->father_name ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Status Ayah</label>
                        <select name="father_status" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Hidup', 'Meninggal'] as $st)
                                <option value="{{ $st }}" {{ ($user->studentProfile->father_status ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Pekerjaan Ayah</label>
                        <input type="text" name="father_job" value="{{ old('father_job', $user->studentProfile->father_job ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No HP Ayah</label>
                        <input type="text" name="father_phone" value="{{ old('father_phone', $user->studentProfile->father_phone ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>

                    {{-- Ibu --}}
                    <div class="md:col-span-2 mt-2"><h3 class="font-bold text-primary text-sm">Data Ibu Kandung</h3></div>
                    <div>
                        <label class="block text-sm mb-1">Nama Ibu</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $user->studentProfile->mother_name ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Status Ibu</label>
                        <select name="mother_status" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Hidup', 'Meninggal'] as $st)
                                <option value="{{ $st }}" {{ ($user->studentProfile->mother_status ?? '') == $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Pekerjaan Ibu</label>
                        <input type="text" name="mother_job" value="{{ old('mother_job', $user->studentProfile->mother_job ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No HP Ibu</label>
                        <input type="text" name="mother_phone" value="{{ old('mother_phone', $user->studentProfile->mother_phone ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>
            </div>

            {{-- DATA WALI --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">4. Data Wali (Opsional)</h2>
                <p class="text-xs text-foreground/60 mb-2">Kosongkan jika siswa tinggal bersama orang tua kandung.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Nama Wali</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $user->studentProfile->guardian_name ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Hubungan dengan Siswa</label>
                        <input type="text" name="guardian_relation" value="{{ old('guardian_relation', $user->studentProfile->guardian_relation ?? '') }}" placeholder="Paman / Bibi / Kakak" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Pekerjaan Wali</label>
                        <input type="text" name="guardian_job" value="{{ old('guardian_job', $user->studentProfile->guardian_job ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">No HP Wali</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $user->studentProfile->guardian_phone ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Alamat Wali</label>
                        <textarea name="guardian_address" rows="2" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ old('guardian_address', $user->studentProfile->guardian_address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            @if(!$user->studentProfile?->is_verified)
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-bold hover:opacity-90 transition-all shadow-md mb-6">
                    Simpan Biodata
                </button>
            @endif
        </form>

        {{-- GANTI PASSWORD --}}
        @if(!$user->studentProfile?->is_verified)
            <div class="bg-card border border-border rounded-xl p-6 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2 mb-4">Ganti Password</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <p class="text-sm text-foreground/60 mb-4">Kosongkan jika tidak ingin mengubah password.</p>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm mb-1">Password Lama</label>
                            <input type="password" name="current_password" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Password Baru</label>
                            <input type="password" name="new_password" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <button type="submit" class="w-full py-2 rounded-lg bg-foreground text-background font-bold text-sm">Perbarui Password</button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</x-layouts.dashboard>