<x-layouts.dashboard title="Tahun Ajaran — IbraLMS">
    <div class="max-w-5xl mx-auto space-y-6">
        
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center">
                <img src="{{ asset('icons/calendar.svg') }}" alt="icon" class="w-6 h-6 object-contain">
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-foreground tracking-tight">Manajemen Tahun Ajaran</h1>
                <p class="text-sm text-foreground/60">Kelola kenaikan kelas dan kelulusan siswa secara massal.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-xl bg-green-50 text-green-700 text-sm font-medium border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 rounded-xl bg-red-50 text-red-700 text-sm font-medium border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Kartu Info Tahun Aktif --}}
            <div class="bg-card border border-border rounded-2xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-foreground/50 uppercase tracking-wider mb-2">Tahun Ajaran Aktif</p>
                    <p class="text-4xl font-extrabold text-primary">{{ $currentYear }}</p>
                </div>
                <div class="mt-6 pt-4 border-t border-border flex items-center justify-between text-sm">
                    <span class="text-foreground/60">Siswa Aktif</span>
                    <span class="font-bold text-foreground">{{ $activeStudents }} Siswa</span>
                </div>
            </div>

            {{-- Form Naik Kelas --}}
            <div class="md:col-span-2 bg-card border border-border rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('icons/rocket.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                    <h2 class="font-bold text-lg">Proses Kenaikan Kelas Massal</h2>
                </div>
                <p class="text-sm text-foreground/60 mb-4">
                    Pindahkan siswa dari tahun ajaran lama ke tahun ajaran baru. Siswa kelas X akan menjadi XI, dan kelas XI akan menjadi XII. (Kelas XII tidak diproses di sini).
                </p>
                <form method="POST" action="{{ route('admin.academic-year.promote') }}" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-xs font-medium mb-1 text-foreground/70">Dari Tahun</label>
                        <input type="text" name="from_year" value="{{ $currentYear }}" required class="w-full px-4 py-2.5 rounded-xl border border-border bg-background text-sm font-medium focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                    <div class="flex items-center justify-center pt-5 text-foreground/40">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium mb-1 text-foreground/70">Ke Tahun Baru</label>
                        <input type="text" name="to_year" placeholder="2027/2028" required class="w-full px-4 py-2.5 rounded-xl border border-border bg-background text-sm font-medium focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                    <div class="pt-5">
                        <button type="submit" onclick="return confirm('Proses naik kelas massal? Pastikan Anda sudah membackup database.')" class="w-full sm:w-auto px-6 py-2.5 bg-primary hover:opacity-90 text-primary-foreground font-bold text-sm rounded-xl transition-all shadow-md">
                            Proses Naik Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kartu Kelulusan --}}
        <div class="bg-card border border-border rounded-2xl p-6 shadow-sm mt-6">
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('icons/award.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                <h2 class="font-bold text-lg">Kelulusan Siswa Kelas XII</h2>
            </div>
            <p class="text-sm text-foreground/60 mb-4">
                Tandai semua siswa di kelas XII pada tahun ajaran terpilih sebagai <b>Lulus</b>. Data mereka akan diarsipkan dan tidak akan muncul lagi di daftar siswa aktif tahun berikutnya.
            </p>
            <form method="POST" action="{{ route('admin.academic-year.graduate') }}" class="flex flex-col sm:flex-row gap-3 max-w-lg">
                @csrf
                <div class="flex-1">
                    <label class="block text-xs font-medium mb-1 text-foreground/70">Tahun Ajaran Lulus</label>
                    <input type="text" name="graduation_year" value="{{ $currentYear }}" required class="w-full px-4 py-2.5 rounded-xl border border-border bg-background text-sm font-medium focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div class="pt-5">
                    <button type="submit" onclick="return confirm('Luluskan semua siswa kelas XII di tahun ini?')" class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition-all shadow-md">
                        Luluskan Kelas XII
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Riwayat Tahun Ajaran --}}
        <div class="bg-card border border-border rounded-2xl shadow-sm overflow-hidden mt-6">
            <div class="p-4 border-b border-border bg-muted/30">
                <h3 class="font-bold text-sm">Daftar Tahun Ajaran Tersedia</h3>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border text-xs uppercase text-foreground/50">
                    <tr>
                        <th class="px-6 py-3">Tahun Ajaran</th>
                        <th class="px-6 py-3">Jumlah Kelas</th>
                        <th class="px-6 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($academicYears as $year)
                        <tr class="hover:bg-muted/10">
                            <td class="px-6 py-4 font-bold">{{ $year->academic_year }}</td>
                            <td class="px-6 py-4 text-foreground/70">
                                {{ \App\Models\Classroom::where('academic_year', $year->academic_year)->count() }} Kelas
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($year->academic_year == $currentYear)
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[11px] font-bold rounded-full border border-dashed border-primary/30">SEDANG AKTIF</span>
                                @else
                                    <span class="text-foreground/40 text-xs">Arsip</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.dashboard>