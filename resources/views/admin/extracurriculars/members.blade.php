<x-layouts.dashboard title="Anggota Ekskul — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.extracurriculars.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $extracurricular->name }}</h1>
            <p class="text-sm text-foreground/60">Pembina: {{ $extracurricular->advisor->name ?? '-' }}</p>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Nilai</th>
                        <th class="px-4 py-3">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($extracurricular->members as $member)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $member->student->name }}</td>
                            <td class="px-4 py-3">{{ $member->student->studentProfile->classroom->name ?? '-' }}</td>
                            <td class="px-4 py-3 font-bold">{{ $member->score ?? '-' }}</td>
                            <td class="px-4 py-3 text-foreground/60">{{ $member->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada anggota.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>