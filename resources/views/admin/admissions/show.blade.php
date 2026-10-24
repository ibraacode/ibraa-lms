<x-layouts.dashboard title="Detail Pendaftar — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.admissions.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $admission->full_name }}</h1>
            <p class="text-sm font-mono text-foreground/60">{{ $admission->registration_number }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-card border border-border rounded-xl p-6">
            <h2 class="font-semibold text-lg mb-4">Data Pendaftar</h2>
            <dl class="grid grid-cols-2 gap-y-3 text-sm">
                <dt class="text-foreground/60">NISN</dt><dd>{{ $admission->nisn ?? '-' }}</dd>
                <dt class="text-foreground/60">Jenis Kelamin</dt><dd>{{ $admission->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                <dt class="text-foreground/60">Tempat, Tanggal Lahir</dt><dd>{{ $admission->birth_place ?? '-' }}, {{ $admission->birth_date?->format('d F Y') ?? '-' }}</dd>
                <dt class="text-foreground/60">Asal Sekolah</dt><dd>{{ $admission->previous_school ?? '-' }}</dd>
                <dt class="text-foreground/60">No. HP</dt><dd>{{ $admission->phone }}</dd>
                <dt class="text-foreground/60">Email</dt><dd>{{ $admission->email ?? '-' }}</dd>
                <dt class="text-foreground/60">Alamat</dt><dd>{{ $admission->address ?? '-' }}</dd>
                <dt class="text-foreground/60">Nama Orang Tua</dt><dd>{{ $admission->parent_name ?? '-' }}</dd>
                <dt class="text-foreground/60">HP Orang Tua</dt><dd>{{ $admission->parent_phone ?? '-' }}</dd>
                <dt class="text-foreground/60">Jurusan Pilihan</dt><dd>{{ $admission->chosen_major ?? '-' }}</dd>
            </dl>
        </div>

        <div class="space-y-4">
            {{-- Form Verifikasi --}}
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-semibold text-lg mb-4">Verifikasi Pendaftar</h2>
                <form method="POST" action="{{ route('admin.admissions.verify', $admission->id) }}" class="space-y-3">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="pending" {{ $admission->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="accepted" {{ $admission->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ $admission->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <textarea name="notes" rows="2" placeholder="Catatan verifikasi (opsional)" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">{{ $admission->notes }}</textarea>
                    <button type="submit" class="w-full py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium">Simpan Verifikasi</button>
                </form>
            </div>

            {{-- Form Konversi ke Siswa --}}
            @if($admission->status == 'accepted' && !$admission->converted_user_id)
                <div class="bg-card border border-primary/30 rounded-xl p-6">
                    <h2 class="font-semibold text-lg mb-2 text-primary">Konversi Jadi Siswa</h2>
                    <p class="text-xs text-foreground/60 mb-3">Pendaftar akan dibuatkan akun siswa otomatis.</p>
                    <form method="POST" action="{{ route('admin.admissions.convert', $admission->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm mb-1">NIS Baru</label>
                            <input type="text" name="nis" required placeholder="20260001" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Kelas</label>
                            <select name="classroom_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(\App\Models\Classroom::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium" onclick="return confirm('Konversi pendaftar menjadi siswa?')">
                            🎓 Konversi Jadi Siswa
                        </button>
                    </form>
                </div>
            @elseif($admission->converted_user_id)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                    <p class="text-green-700 font-medium">✓ Sudah dikonversi menjadi siswa</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>