<x-layouts.dashboard title="Rapor Saya — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Rapor Saya</h1>
        <a href="{{ route('student.gradebook.print') }}" target="_blank" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-medium hover:opacity-90 flex items-center gap-2 w-fit">
            🖨️ Cetak Rapor
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Mata Pelajaran</th>
                        <th class="px-4 py-3">Guru Pengampu</th>
                        <th class="px-4 py-3 text-center">Tugas Dinilai</th>
                        <th class="px-4 py-3 text-center font-bold text-primary">Nilai Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($grades as $g)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $g['subject'] }}</td>
                            <td class="px-4 py-3 text-foreground/70">{{ $g['teacher'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $g['assignments_count'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($g['average'] !== null)
                                    <span class="px-3 py-1 rounded-md font-bold text-white
                                        {{ $g['average'] >= 80 ? 'bg-green-500' : ($g['average'] >= 70 ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        {{ $g['average'] }}
                                    </span>
                                @else
                                    <span class="text-foreground/40">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-foreground/50">Belum ada data nilai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

      {{-- KOTAK NILAI KARAKTER --}}
    @if($characterGrade)
        <div class="mt-6 bg-card border border-border rounded-xl p-6">
            <h2 class="font-bold text-lg mb-4">Penilaian Karakter (Sikap)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-bold mb-1">SIKAP SPIRITUAL</p>
                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300 mb-2">{{ $characterGrade->spiritual_grade ?? '-' }}</p>
                    <p class="text-sm text-blue-600/80">{{ $characterGrade->spiritual_notes ?? 'Tidak ada catatan.' }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800">
                    <p class="text-xs text-green-600 dark:text-green-400 font-bold mb-1">SIKAP SOSIAL</p>
                    <p class="text-3xl font-bold text-green-700 dark:text-green-300 mb-2">{{ $characterGrade->social_grade ?? '-' }}</p>
                    <p class="text-sm text-green-600/80">{{ $characterGrade->social_notes ?? 'Tidak ada catatan.' }}</p>
                </div>
            </div>
        </div>
    @endif
</x-layouts.dashboard>