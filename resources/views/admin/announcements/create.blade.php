<x-layouts.dashboard title="Buat Pengumuman — IbraLMS">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.announcements.index') }}" class="text-foreground/60 hover:text-primary transition-colors">← Kembali</a>
            <h1 class="text-2xl font-bold">Buat Pengumuman Baru</h1>
        </div>

        <div class="bg-card border border-border rounded-xl p-6 md:p-8 shadow-sm">
            <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Judul Pengumuman</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Libur Semester Ganjil" 
                           class="w-full px-4 py-2.5 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Isi Pengumuman</label>
                    <x-rich-editor name="content" :value="old('content')" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Ditujukan Kepada</label>
                    <select name="target_role" required class="w-full md:w-1/2 px-4 py-2.5 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                        <option value="semua" {{ old('target_role') == 'semua' ? 'selected' : '' }}>Semua Pengguna</option>
                        <option value="siswa" {{ old('target_role') == 'siswa' ? 'selected' : '' }}>Hanya Siswa</option>
                        <option value="guru" {{ old('target_role') == 'guru' ? 'selected' : '' }}>Hanya Guru</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-border flex justify-end">
                    <button type="submit" class="px-8 py-3 rounded-xl bg-primary text-primary-foreground font-bold hover:opacity-90 shadow-md">Publikasikan</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.dashboard>