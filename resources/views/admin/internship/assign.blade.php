<x-layouts.dashboard title="Penempatan PKL — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.internship.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <h1 class="text-2xl font-bold">Penempatan Siswa PKL</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Tempat PKL</th>
                        <th class="px-4 py-3">Guru Pembimbing</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($students as $student)
                        @php
                            $placement = \App\Models\InternshipPlacement::where('student_id', $student->id)->first();
                        @endphp
                        <tr>
                            <form method="POST" action="{{ route('admin.internship.assign.store') }}">
                                @csrf
                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                <td class="px-4 py-3 font-medium">
                                    {{ $student->name }}<br>
                                    <span class="text-xs text-foreground/50">{{ $student->nis }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $student->studentProfile->classroom->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <select name="industry_placement_id" required class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach($placements as $p)
                                            <option value="{{ $p->id }}" {{ $placement?->industry_placement_id == $p->id ? 'selected' : '' }}>{{ $p->company_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3">
                                    <select name="teacher_id" class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                        <option value="">-- Pilih --</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ $placement?->teacher_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3 space-y-1">
                                    <input type="date" name="start_date" value="{{ $placement?->start_date?->format('Y-m-d') }}" class="w-full px-1 py-1 rounded border text-xs">
                                    <input type="date" name="end_date" value="{{ $placement?->end_date?->format('Y-m-d') }}" class="w-full px-1 py-1 rounded border text-xs">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="submit" class="px-3 py-1 bg-primary text-white rounded-md text-xs">Simpan</button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>