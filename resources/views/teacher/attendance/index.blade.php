<x-layouts.dashboard title="Presensi Hari Ini — IbraLMS">
    <h1 class="text-2xl font-bold mb-2">Presensi Hari Ini</h1>
    <p class="text-foreground/60 mb-6">{{ $today }}, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>

    @php
    $isReadOnly = !\App\Helpers\AcademicYear::isActive();
@endphp

    @if($isReadOnly)
        <div class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm">
            <b>Mode Arsip:</b> Anda sedang melihat data tahun ajaran lama. Anda tidak dapat mengubah data presensi.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($schedules as $sch)
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-primary text-lg">{{ $sch->subject->name }}</h3>
                        <p class="text-sm font-medium">{{ $sch->classroom->name }}</p>
                    </div>
                    <span class="text-xs bg-muted px-2 py-1 rounded-md">{{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}</span>
                </div>

                @if(isset($sessions[$sch->id]))
                    <a href="{{ route('teacher.attendance.show', $sessions[$sch->id]->id) }}" 
                       class="block text-center w-full py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200">
                        {{ $isReadOnly ? 'Lihat Presensi' : 'Lihat / Edit Presensi' }}
                    </a>
                @elseif($isReadOnly)
                    <div class="text-center w-full py-2 bg-gray-100 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed">
                        Terkunci (Tahun Lalu)
                    </div>
                @else
                    <form method="POST" action="{{ route('teacher.attendance.open', $sch->id) }}">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:opacity-90">
                            Buka Sesi Presensi
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="col-span-full bg-card border border-border rounded-xl p-8 text-center text-foreground/50">
                Tidak ada jadwal mengajar untuk hari ini.
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>