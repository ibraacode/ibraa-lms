<x-layouts.dashboard title="Nilai Karakter — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.character.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <div>
                <h1 class="text-2xl font-bold">Nilai Karakter: {{ $classroom->name }}</h1>
                <p class="text-sm text-foreground/60">Semester {{ $semester }} - T.A {{ $academicYear }}</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.character.store', $classroom->id) }}">
        @csrf
        <input type="hidden" name="semester" value="{{ $semester }}">
        
        <div class="bg-card border border-border rounded-xl overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3 w-10">No</th>
                            <th class="px-4 py-3">Nama Siswa</th>
                            <th class="px-4 py-3 w-32">Sikap Spiritual</th>
                            <th class="px-4 py-3">Catatan Spiritual</th>
                            <th class="px-4 py-3 w-32">Sikap Sosial</th>
                            <th class="px-4 py-3">Catatan Sosial</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($students as $index => $student)
                            @php
                                $grade = $student->characterGrades->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-3">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 font-medium">{{ $student->name }}</td>
                                
                                {{-- Spiritual --}}
                                <td class="px-4 py-3">
                                    <select name="spiritual_grade[{{ $student->id }}]" class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $predikat)
                                            <option value="{{ $predikat }}" {{ $grade?->spiritual_grade == $predikat ? 'selected' : '' }}>{{ $predikat }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="spiritual_notes[{{ $student->id }}]" value="{{ $grade?->spiritual_notes }}" placeholder="Catatan..." class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                </td>

                                {{-- Sosial --}}
                                <td class="px-4 py-3">
                                    <select name="social_grade[{{ $student->id }}]" class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $predikat)
                                            <option value="{{ $predikat }}" {{ $grade?->social_grade == $predikat ? 'selected' : '' }}>{{ $predikat }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="social_notes[{{ $student->id }}]" value="{{ $grade?->social_notes }}" placeholder="Catatan..." class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa di kelas ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->isNotEmpty())
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    Simpan Nilai Karakter
                </button>
            </div>
        @endif
    </form>
</x-layouts.dashboard>