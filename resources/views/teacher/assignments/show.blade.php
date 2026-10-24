<x-layouts.dashboard title="Beri Nilai — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('teacher.assignments.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $assignment->title }}</h1>
            <p class="text-sm text-foreground/60">{{ $assignment->schedule->classroom->name }} | {{ $assignment->schedule->subject->name }} | Maks: {{ $assignment->max_score }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.assignments.grade', $assignment->id) }}">
        @csrf
        <div class="bg-card border border-border rounded-xl overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3 w-12">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3">Jawaban Siswa</th>
                            <th class="px-4 py-3 w-32">Nilai (0-{{ $assignment->max_score }})</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($students as $index => $student)
                            @php
                                $sub = $student->submissions->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $student->name }}</td>
                                <td class="px-4 py-3 text-foreground/70 max-w-xs truncate">
                                    {{ $sub?->answer_text ?? 'Belum mengumpulkan' }}
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" name="scores[{{ $student->id }}]" 
                                           value="{{ $sub?->score }}" min="0" max="{{ $assignment->max_score }}" 
                                           class="w-full px-2 py-1 rounded-md border border-border bg-background">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="feedbacks[{{ $student->id }}]" 
                                           value="{{ $sub?->feedback }}" placeholder="Opsional"
                                           class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Tidak ada siswa di kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($students->isNotEmpty())
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    Simpan Semua Nilai
                </button>
            </div>
        @endif
    </form>
</x-layouts.dashboard>