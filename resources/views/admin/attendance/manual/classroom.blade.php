<x-layouts.dashboard title="Presensi Manual — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.attendance.manual.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $classroom->name }}</h1>
            <p class="text-sm text-foreground/60">Hari: {{ $today }}, {{ date('d F Y') }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    @if($schedules->isEmpty())
        <div class="bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
            Tidak ada jadwal untuk kelas ini hari ini.
        </div>
    @else
        @foreach($schedules as $sch)
            @php
                $session = $existingSessions[$sch->id] ?? null;
            @endphp
            <div class="bg-card border border-border rounded-xl p-5 mb-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-lg">{{ $sch->subject->name }}</h3>
                        <p class="text-sm text-foreground/60">
                            Jam {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                            | Guru: {{ $sch->teacher->name ?? '-' }}
                        </p>
                    </div>
                    @if($session)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-md font-medium">Sudah Terisi</span>
                    @else
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md font-medium">Belum Terisi</span>
                    @endif
                </div>

                <form method="POST" action="{{ route('admin.attendance.manual.store', ['classroom' => $classroom->id, 'schedule' => $sch->id]) }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-muted/50 border-b border-border">
                                <tr>
                                    <th class="px-2 py-2 text-left">Nama Siswa</th>
                                    <th class="px-2 py-2">Hadir</th>
                                    <th class="px-2 py-2">Izin</th>
                                    <th class="px-2 py-2">Sakit</th>
                                    <th class="px-2 py-2">Alpa</th>
                                    <th class="px-2 py-2 text-left">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @php
                                    $existingRecords = $session ? $session->records->keyBy('student_id') : collect();
                                @endphp
                                @foreach($classroom->students as $student)
                                    @php
                                        $record = $existingRecords[$student->user_id] ?? null;
                                        $currentStatus = $record->status ?? 'alpa';
                                    @endphp
                                    <tr>
                                        <td class="px-2 py-2">{{ $student->full_name }}</td>
                                        @foreach(['hadir', 'izin', 'sakit', 'alpa'] as $status)
                                            <td class="px-2 py-2 text-center">
                                                <input type="radio" name="status[{{ $student->user_id }}]" value="{{ $status }}" {{ $currentStatus === $status ? 'checked' : '' }}>
                                            </td>
                                        @endforeach
                                        <td class="px-2 py-2">
                                            <input type="text" name="note[{{ $student->user_id }}]" value="{{ $record->note ?? '' }}" placeholder="Opsional" class="w-full px-2 py-1 rounded-md border border-border bg-background text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium">
                            {{ $session ? 'Perbarui Presensi' : 'Simpan Presensi' }}
                        </button>
                    </div>
                </form>
            </div>
        @endforeach
    @endif
</x-layouts.dashboard>