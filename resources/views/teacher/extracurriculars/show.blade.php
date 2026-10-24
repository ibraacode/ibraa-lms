<x-layouts.dashboard title="Nilai Ekskul — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('teacher.extracurriculars.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <h1 class="text-2xl font-bold">{{ $extracurricular->name }}</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.extracurriculars.grade', $extracurricular->id) }}">
        @csrf
        <div class="bg-card border border-border rounded-xl overflow-hidden mb-4">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Nilai (0-100)</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($extracurricular->members as $member)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $member->student->name }}</td>
                            <td class="px-4 py-3">
                                <input type="number" name="scores[{{ $member->id }}]" value="{{ $member->score }}" min="0" max="100"
                                       class="w-24 px-2 py-1 rounded-md border border-border bg-background">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" name="notes[{{ $member->id }}]" value="{{ $member->notes }}" placeholder="Opsional"
                                       class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($extracurricular->members->isNotEmpty())
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    Simpan Nilai
                </button>
            </div>
        @endif
    </form>
</x-layouts.dashboard>