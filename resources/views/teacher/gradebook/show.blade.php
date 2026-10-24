<x-layouts.dashboard title="Rekap Nilai — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('teacher.gradebook.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">Rekap Nilai: {{ $schedule->subject->name }}</h1>
            <p class="text-sm text-foreground/60">Kelas {{ $schedule->classroom->name }}</p>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3 w-12 sticky left-0 bg-muted/50">No</th>
                        <th class="px-4 py-3 font-medium sticky left-12 bg-muted/50 min-w-[150px]">Nama Siswa</th>
                        @foreach($assignments as $a)
                            <th class="px-4 py-3 text-center min-w-[100px]">
                                <div class="font-semibold">{{ $a->title }}</div>
                                <div class="text-xs text-foreground/50 font-normal">Max: {{ $a->max_score }}</div>
                            </th>
                        @endforeach
                        <th class="px-4 py-3 text-center bg-primary/10 font-bold text-primary">Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($students as $index => $student)
                        @php
                            $totalScore = 0;
                            $countGraded = 0;
                        @endphp
                        <tr class="hover:bg-muted/20">
                            <td class="px-4 py-3 sticky left-0 bg-card">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 font-medium sticky left-12 bg-card">{{ $student->name }}</td>
                            
                            @foreach($assignments as $a)
                                @php
                                    $sub = $student->submissions->where('assignment_id', $a->id)->first();
                                    $score = $sub ? $sub->score : null;
                                    if ($score !== null) {
                                        $totalScore += $score;
                                        $countGraded++;
                                    }
                                @endphp
                                <td class="px-4 py-3 text-center">
                                    @if($score !== null)
                                        <span class="font-medium {{ $score < 70 ? 'text-red-500' : 'text-green-600' }}">{{ $score }}</span>
                                    @else
                                        <span class="text-foreground/30">-</span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="px-4 py-3 text-center bg-primary/5 font-bold text-primary">
                                {{ $countGraded > 0 ? round($totalScore / $countGraded, 1) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ $assignments->count() + 3 }}" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa di kelas ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assignments->isEmpty())
            <div class="p-4 border-t border-border text-center text-sm text-foreground/60">
                Belum ada tugas yang dibuat untuk kelas ini.
            </div>
        @endif
    </div>
</x-layouts.dashboard>