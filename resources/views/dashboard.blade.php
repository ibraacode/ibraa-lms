<x-layouts.dashboard title="Dashboard — IbraLMS">
    <h1 class="text-2xl font-bold mb-2">
        Selamat datang, {{ auth()->user()->name }} 👋
    </h1>
    <p class="text-foreground/70 mb-8">
        Peran Anda: <span class="font-medium text-primary">{{ auth()->user()->roles->pluck('name')->join(', ') }}</span>
    </p>

    @php
        $userRole = auth()->user()->roles->pluck('name')->first();
        $roleFilter = in_array($userRole, ['super-admin', 'tu']) ? ['semua', 'siswa', 'guru'] : [$userRole, 'semua'];
        $latestAnnouncements = \App\Models\Announcement::whereIn('target_role', $roleFilter)->latest()->take(2)->get();
    @endphp

    @if($latestAnnouncements->count() > 0)
        <div class="mb-6 space-y-3">
            @foreach($latestAnnouncements as $ann)
                <div class="bg-primary/10 border border-primary/20 rounded-xl p-4">
                    <h3 class="font-bold text-primary mb-1">📢 {{ $ann->title }}</h3>
                    <p class="text-sm text-foreground/80">{{ Str::limit($ann->content, 150) }}</p>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ================= DASHBOARD ADMIN/TU ================= --}}
    @if(auth()->user()->hasAnyRole(['super-admin', 'tu']))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs text-foreground/60 mb-1">Total Siswa</p>
                <p class="text-3xl font-bold text-primary">{{ $totalStudents }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs text-foreground/60 mb-1">Total Guru</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalTeachers }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs text-foreground/60 mb-1">Hadir Hari Ini (QR)</p>
                <p class="text-3xl font-bold text-green-600">{{ $attendanceToday }}</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs text-foreground/60 mb-1">Pemasukan Bulan Ini</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-semibold mb-4">Grafik Keuangan (6 Bulan Terakhir)</h2>
                <canvas id="financeChart" height="200"></canvas>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-semibold mb-4">Kehadiran Harian (7 Hari Terakhir)</h2>
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Grafik Keuangan
                const financeCtx = document.getElementById('financeChart').getContext('2d');
                new Chart(financeCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_column($financeChart, 'label')) !!},
                        datasets: [{
                            label: 'Total Pemasukan (Rp)',
                            data: {!! json_encode(array_column($financeChart, 'total')) !!},
                            backgroundColor: '#8b5cf6',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
                    }
                });

                // Grafik Kehadiran
                const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                new Chart(attendanceCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_column($attendanceChart, 'label')) !!},
                        datasets: [{
                            label: 'Jumlah Hadir',
                            data: {!! json_encode(array_column($attendanceChart, 'count')) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });
            });
        </script>
    @endif

    {{-- ================= DASHBOARD GURU ================= --}}
    @if(auth()->user()->hasRole('guru'))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('teacher.assignments.index') }}" class="bg-card border border-border rounded-xl p-6 hover:border-primary transition">
                <p class="text-xs text-foreground/60 mb-1">Total Tugas Dibuat</p>
                <p class="text-3xl font-bold text-primary">{{ $totalAssignments }}</p>
            </a>
            <a href="{{ route('teacher.assignments.index') }}" class="bg-card border border-border rounded-xl p-6 hover:border-primary transition">
                <p class="text-xs text-foreground/60 mb-1">Tugas Belum Dinilai</p>
                <p class="text-3xl font-bold text-red-600">{{ $ungradedCount }}</p>
            </a>
        </div>
    @endif

    {{-- ================= DASHBOARD SISWA ================= --}}
    @if(auth()->user()->hasRole('siswa'))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <p class="text-xs text-foreground/60 mb-1">Tingkat Kehadiran</p>
                <p class="text-3xl font-bold text-green-600">{{ $attendanceRate }}%</p>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <p class="text-xs text-foreground/60 mb-1">Rata-rata Nilai</p>
                <p class="text-3xl font-bold text-primary">{{ $averageScore }}</p>
            </div>
            <a href="{{ route('student.assignments.index') }}" class="bg-card border border-border rounded-xl p-6 hover:border-primary transition">
                <p class="text-xs text-foreground/60 mb-1">Tugas Belum Dikerjakan</p>
                <p class="text-3xl font-bold text-red-600">{{ $pendingTasks }}</p>
            </a>
        </div>
    @endif

    {{-- ================= DASHBOARD LAINNYA ================= --}}
    @if(!auth()->user()->hasAnyRole(['siswa', 'guru', 'super-admin', 'tu']))
        <div class="bg-card border border-border rounded-xl p-6">
            <h2 class="font-semibold text-lg mb-2">Selamat Datang</h2>
            <p class="text-sm text-foreground/60">Gunakan menu di sebelah kiri untuk mengakses fitur yang tersedia untuk peran <b>{{ auth()->user()->roles->pluck('name')->join(', ') }}</b>.</p>
        </div>
    @endif
</x-layouts.dashboard>