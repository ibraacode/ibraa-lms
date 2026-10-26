<x-layouts.dashboard title="Kelas — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Manajemen Kelas (T.A {{ $currentYear }})</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- FORM TAMBAH KELAS --}}
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Kelas</h2>
            <form method="POST" action="{{ route('admin.academic.classrooms.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Tingkat</label>
                    <select name="grade_level" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="X">X (Sepuluh)</option>
                        <option value="XI">XI (Sebelas)</option>
                        <option value="XII">XII (Dua Belas)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Jurusan *</label>
                    <select name="major_code" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach(\App\Models\Major::orderBy('code')->get() as $m)
                            <option value="{{ $m->code }}" {{ old('major_code') == $m->code ? 'selected' : '' }}>{{ $m->name }} ({{ $m->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1">Tahun Ajaran</label>
                    <input type="text" name="academic_year" value="{{ $currentYear }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>

                <div>
                    <label class="block text-sm mb-1">Wali Kelas (Opsional)</label>
                    <select name="homeroom_teacher_id" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="p-3 bg-primary/10 rounded-lg text-xs text-foreground/60">
                    <b>Info:</b> Nama kelas akan dibuat otomatis dengan format <b>[Tingkat] [Jurusan] [Urutan]</b>.<br>
                    Contoh: <b>X TKJ 1</b>, <b>X TKJ 2</b>, <b>XI RPL 1</b>.
                </div>

                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-bold">Buat Kelas</button>
            </form>
        </div>

        {{-- TABEL KELAS --}}
        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Kelas</th>
                        <th class="px-4 py-3">Tingkat</th>
                        <th class="px-4 py-3">Wali Kelas</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($classrooms as $c)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                            <td class="px-4 py-3">{{ $c->grade_level }} {{ $c->major_code }}</td>
                            <td class="px-4 py-3">
                                {{-- FORM UPDATE WALI KELAS --}}
                                <form method="POST" action="{{ route('admin.academic.classrooms.update-homeroom', $c->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="homeroom_teacher_id" onchange="this.form.submit()" class="px-2 py-1 rounded-md border border-border bg-background text-sm w-full max-w-[200px]">
                                        <option value="">-- Belum diset --</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ $c->homeroom_teacher_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.academic.classrooms.destroy', $c->id) }}" onsubmit="return confirm('Hapus kelas {{ $c->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-xs font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada data kelas di tahun ajaran ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $classrooms->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>