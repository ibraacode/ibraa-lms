<x-layouts.dashboard title="Surat — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Arsip Surat</h1>
        <a href="{{ route('admin.letters.templates') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Kelola Template</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Terbitkan Surat</h2>
            <form method="POST" action="{{ route('admin.letters.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Template</label>
                    <select name="letter_template_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Template --</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Siswa</label>
                    <select name="student_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Nomor Surat</label>
                    <input type="text" name="letter_number" required placeholder="421/001/SMA/2026" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tanggal Surat</label>
                    <input type="date" name="issued_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Terbitkan Surat</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">No. Surat</th>
                        <th class="px-4 py-3">Template</th>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($letters as $l)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $l->letter_number }}</td>
                            <td class="px-4 py-3">{{ $l->template->name }}</td>
                            <td class="px-4 py-3">{{ $l->student->name }}</td>
                            <td class="px-4 py-3">{{ $l->issued_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.letters.print', $l->id) }}" target="_blank" class="text-primary hover:underline text-xs font-medium">🖨️ Cetak</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada surat terbit.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $letters->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>