<x-layouts.dashboard title="Profil Guru — IbraLMS">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Profil & Biodata Guru</h1>
            @if($user->teacherProfile?->is_verified)
                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">✓ Data Terverifikasi</span>
            @endif
        </div>

        @if (session('success'))
            <div class="p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
        @endif

        @if($user->teacherProfile?->is_verified)
            <div class="p-4 bg-blue-50 text-blue-700 rounded-xl border border-blue-200 text-sm mb-6">
                <b>Perhatian:</b> Data Anda sudah difinalisasi oleh Admin. Jika ada kekeliruan, silakan hubungi bagian Tata Usaha.
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-teacher') }}">
            @csrf
            @php $readonly = $user->teacherProfile?->is_verified ? 'readonly disabled bg-gray-100 cursor-not-allowed' : ''; @endphp

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
                        <label class="block text-sm mb-1">No. HP Aktif</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">NIP</label>
                        <input type="text" value="{{ $user->nip }}" readonly class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- BIODATA GURU --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">2. Biodata Lengkap</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->teacherProfile->nik ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Agama</label>
                        <select name="religion" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agama)
                                <option value="{{ $agama }}" {{ ($user->teacherProfile->religion ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jenis Kelamin</label>
                        <select name="gender" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="L" {{ ($user->teacherProfile->gender ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ ($user->teacherProfile->gender ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Status Perkawinan</label>
                        <select name="marital_status" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                <option value="{{ $status }}" {{ ($user->teacherProfile->marital_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $user->teacherProfile->birth_place ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ $user->teacherProfile && $user->teacherProfile->birth_date ? $user->teacherProfile->birth_date->format('Y-m-d') : '' }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="2" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ old('address', $user->teacherProfile->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- DATA PENDIDIKAN & KEPEGAWAIAN --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">3. Pendidikan & Kepegawaian</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Pendidikan Terakhir</label>
                        <select name="last_education" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['SMA/SMK', 'D3', 'D4/S1', 'S2', 'S3'] as $pend)
                                <option value="{{ $pend }}" {{ ($user->teacherProfile->last_education ?? '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Universitas / Institut</label>
                        <input type="text" name="university" value="{{ old('university', $user->teacherProfile->university ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Jurusan / Prodi</label>
                        <input type="text" name="major" value="{{ old('major', $user->teacherProfile->major ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tahun Lulus</label>
                        <input type="number" name="graduation_year" value="{{ old('graduation_year', $user->teacherProfile->graduation_year ?? '') }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Status Kepegawaian</label>
                        <select name="employment_status" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['PNS', 'PPPK', 'GTT', 'GTY', 'Honorer'] as $status)
                                <option value="{{ $status }}" {{ ($user->teacherProfile->employment_status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tanggal Mulai Bertugas</label>
                        <input type="date" name="joined_at" value="{{ $user->teacherProfile && $user->teacherProfile->joined_at ? $user->teacherProfile->joined_at->format('Y-m-d') : '' }}" {{ $readonly }} class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>
            </div>

            @if(!$user->teacherProfile?->is_verified)
                <button type="submit" class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-bold hover:opacity-90 transition-all shadow-md mb-6">
                    Simpan Biodata
                </button>
            @endif
        </form>

        {{-- GANTI PASSWORD --}}
        @if(!$user->teacherProfile?->is_verified)
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