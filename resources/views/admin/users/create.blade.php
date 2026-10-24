<x-layouts.dashboard title="Tambah Pengguna — IbraLMS">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Tambah Pengguna Baru</h1>

        <form method="POST" action="{{ route('admin.users.store') }}" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
            
            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
            </div>

            <div x-data="{ role: '{{ old('role') }}' }">
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" x-model="role" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>

                <!-- Field khusus Siswa -->
                <div x-show="role === 'siswa'" style="display: none;" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" value="{{ old('nis') }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <p class="text-xs text-foreground/50 mt-1">Email akan dibuat otomatis: NIS&#64;{{ config('school.student_domain') }}</p>
                    </div>
                </div>

                <!-- Field khusus Guru -->
                <div x-show="role === 'guru'" style="display: none;" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <p class="text-xs text-foreground/50 mt-1">Email akan dibuat otomatis: NIP@{{ config('school.teacher_domain') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password Awal</label>
                <input type="text" name="password" value="{{ old('password', 'IbraLMS123!') }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                <p class="text-xs text-foreground/50 mt-1">User akan dipaksa mengganti password ini saat login pertama.</p>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                Simpan Pengguna
            </button>
        </form>
    </div>
</x-layouts.dashboard>