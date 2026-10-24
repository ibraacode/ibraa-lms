<x-layouts.dashboard title="Import Pengguna — IbraLMS">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Import Pengguna Massal</h1>

        <form method="POST" action="{{ route('admin.users.process-import') }}" enctype="multipart/form-data" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
            @if ($errors->any())
                <div class="p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
            @endif

            <div>
                <label class="block text-sm font-medium mb-1">Pilih Role</label>
                <select name="role" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">File Data (CSV atau Excel)</label>
                {{-- Tambahkan accept untuk xlsx, xls, dan csv --}}
                <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required 
                       class="w-full px-4 py-2 rounded-lg border border-border bg-background text-sm">
                <p class="text-xs text-foreground/50 mt-1">Disarankan menggunakan file Excel (.xlsx) jika menggunakan HP.</p>
            </div>

            <div class="p-4 bg-primary/5 rounded-lg text-sm text-foreground/80 space-y-2">
                <p class="font-semibold text-primary">Format Kolom yang dibutuhkan:</p>
                <p>Jika <b>Siswa</b>: <code>nama, nis, jenis_kelamin, kelas</code></p>
                <p>Jika <b>Guru</b>: <code>nama, nip, jenis_kelamin</code></p>
                <p class="text-xs mt-2">*Pastikan baris pertama adalah judul kolom (header).</p>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                Mulai Import
            </button>
        </form>
    </div>
</x-layouts.dashboard>