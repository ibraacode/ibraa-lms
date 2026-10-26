<x-layouts.dashboard title="Tugas Saya — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Tugas Saya</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    @php
    $isReadOnly = !\App\Helpers\AcademicYear::isActive();
@endphp

    <div class="space-y-4">
        @forelse($assignments as $a)
            @php
                $sub = $a->submissions->first();
                $isPast = now()->greaterThan($a->due_date);
            @endphp
            <div class="bg-card border border-border rounded-xl p-5" x-data="{ open: false }">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg">{{ $a->title }}</h3>
                        <p class="text-sm text-foreground/60">{{ $a->schedule->subject->name }} | {{ $a->schedule->teacher->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-foreground/60">Tenggat</p>
                        <p class="text-sm font-medium {{ $isPast ? 'text-red-600' : 'text-primary' }}">{{ $a->due_date->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                <p class="text-sm text-foreground/80 mb-4 bg-muted/30 p-3 rounded-lg">{{ $a->description ?? 'Tidak ada deskripsi.' }}</p>

                @if($sub && $sub->score !== null)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                        <p class="text-sm font-bold text-green-700">Nilai: {{ $sub->score }} / {{ $a->max_score }}</p>
                        <p class="text-xs text-green-600 mt-1">Catatan Guru: {{ $sub->feedback ?? '-' }}</p>
                    </div>
                @endif

                @if($sub)
                    <div class="text-sm">
                        <p class="font-medium text-green-600">✓ Sudah dikumpulkan</p>
                        <p class="text-foreground/60 text-xs mt-1">{{ $sub->answer_text }}</p>
                    </div>
                @elseif($isReadOnly)
                    <p class="text-sm text-gray-500 italic bg-gray-50 p-2 rounded-lg inline-block">Arsip tahun ajaran lalu (Tidak dapat dikumpulkan)</p>
                @elseif(!$isPast)
                    <button @click="open = !open" class="text-sm text-primary font-medium hover:underline">Kumpulkan Tugas</button>
                    <div x-show="open" style="display: none;" class="mt-3">
                        <form method="POST" action="{{ route('student.assignments.submit', $a->id) }}">
                            @csrf
                            <textarea name="answer_text" rows="3" required placeholder="Ketik jawaban Anda..." class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm mb-2"></textarea>
                            <button type="submit" class="px-4 py-1.5 bg-primary text-primary-foreground rounded-md text-sm font-medium">Kirim</button>
                        </form>
                    </div>
                @else
                    <p class="text-sm font-medium text-red-600">Tenggat sudah lewat.</p>
                @endif
            </div>
        @empty
            <div class="bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Belum ada tugas untuk kelas Anda.
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $assignments->links() }}</div>
</x-layouts.dashboard>