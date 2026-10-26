<x-layouts.dashboard title="Dashboard — IbraLMS">
    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- ================= HEADER: SAPAAN & PENGUMUMAN ================= --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-foreground tracking-tight">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-sm text-foreground/60 mt-1">
                    Anda masuk sebagai <span class="font-bold text-primary capitalize">{{ auth()->user()->roles->pluck('name')->join(', ') }}</span>
                </p>
            </div>
            <div class="text-sm text-foreground/60 bg-card px-4 py-2 rounded-xl border border-border shadow-sm">
                📅 {{ now()->isoFormat('dddd, D MMMM Y') }}
            </div>
        </div>

        @php
            $userRole = auth()->user()->roles->pluck('name')->first();
            $roleFilter = in_array($userRole, ['super-admin', 'tu']) ? ['semua', 'siswa', 'guru'] : [$userRole, 'semua'];
            $latestAnnouncements = \App\Models\Announcement::whereIn('target_role', $roleFilter)->latest()->take(2)->get();
        @endphp

        @if($latestAnnouncements->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($latestAnnouncements as $ann)
                    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-4 flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                            <span class="text-xl">📢</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary text-sm mb-1">{{ $ann->title }}</h3>
                            <p class="text-xs text-foreground/70 line-clamp-2">{{ Str::limit($ann->content, 100) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ================= DASHBOARD ADMIN/TU ================= --}}
        @if(auth()->user()->hasAnyRole(['super-admin', 'tu']))
            
            {{-- KARTU STATISTIK UTAMA --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Kartu Siswa --}}
                <div class="bg-card border border-border rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-[100px] transition-transform group-hover:scale-110"></div>
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center mb-3">
                        <img src="{{ asset('icons/users.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Total Siswa</p>
                    <h3 class="text-3xl font-extrabold text-foreground">{{ number_format($totalStudents) }}</h3>
                </div>

                {{-- Kartu Guru --}}
                <div class="bg-card border border-border rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-bl-[100px] transition-transform group-hover:scale-110"></div>
                    <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center mb-3">
                        <img src="{{ asset('icons/user-card.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Total Guru</p>
                    <h3 class="text-3xl font-extrabold text-foreground">{{ number_format($totalTeachers) }}</h3>
                </div>

                {{-- Kartu Kehadiran --}}
                <div class="bg-card border border-border rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-green-500/5 rounded-bl-[100px] transition-transform group-hover:scale-110"></div>
                    <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center mb-3">
                        <img src="{{ asset('icons/check-circle.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Hadir Hari Ini</p>
                    <h3 class="text-3xl font-extrabold text-foreground">{{ number_format($attendanceToday) }}</h3>
                </div>

                {{-- Kartu Keuangan --}}
                <div class="bg-card border border-border rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-orange-500/5 rounded-bl-[100px] transition-transform group-hover:scale-110"></div>
                    <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center mb-3">
                        <img src="{{ asset('icons/bank.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Pemasukan Bulan Ini</p>
                    <h3 class="text-xl font-extrabold text-foreground">Rp {{ number_format($incomeThisMonth / 1000, 0, ',', '.') }}k</h3>
                </div>
            </div>

            {{-- GRAFIK --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Grafik Keuangan --}}
                <div class="lg:col-span-2 bg-card border border-border rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-lg text-foreground">Tren Keuangan (6 Bulan)</h2>
                        <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full">Pemasukan</span>
                    </div>
                    <div class="relative h-64">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                {{-- Grafik Kehadiran --}}
                <div class="lg:col-span-1 bg-card border border-border rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-bold text-lg text-foreground">Kehadiran (7 Hari)</h2>
                    </div>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        {{-- ================= DASHBOARD GURU ================= --}}
        @if(auth()->user()->hasRole('guru'))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('teacher.assignments.index') }}" class="bg-card border border-border rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                        <img src="{{ asset('icons/book.svg') }}" class="w-6 h-6 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Total Tugas Dibuat</p>
                    <h3 class="text-4xl font-extrabold text-foreground">{{ $totalAssignments }}</h3>
                </a>

                <a href="{{ route('teacher.assignments.index') }}" class="bg-card border border-border rounded-2xl p-6 shadow-sm hover:shadow-md transition-all group">
                    <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:text-white transition-colors">
                        <img src="{{ asset('icons/file.svg') }}" class="w-6 h-6 object-contain" alt="icon">
                    </div>
                    <p class="text-sm font-medium text-foreground/60 mb-1">Tugas Belum Dinilai</p>
                    <h3 class="text-4xl font-extrabold text-foreground">{{ $ungradedCount }}</h3>
                </a>
            </div>
        @endif

        {{-- ================= DASHBOARD SISWA ================= --}}
        @if(auth()->user()->hasRole('siswa'))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-card border border-border rounded-2xl p-6 shadow-sm">
                    <p class="text-sm font-medium text-foreground/60 mb-1">Tingkat Kehadiran</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-4xl font-extrabold text-green-600">{{ $attendanceRate }}%</h3>
                    </div>
                </div>
                <div class="bg-card border border-border rounded-2xl p-6 shadow-sm">
                    <p class="text-sm font-medium text-foreground/60 mb-1">Rata-rata Nilai</p>
                    <h3 class="text-4xl font-extrabold text-primary">{{ $averageScore }}</h3>
                </div>
                <a href="{{ route('student.assignments.index') }}" class="bg-card border border-border rounded-2xl p-6 shadow-sm hover:border-primary transition-colors block">
                    <p class="text-sm font-medium text-foreground/60 mb-1">Tugas Belum Selesai</p>
                    <h3 class="text-4xl font-extrabold text-red-500">{{ $pendingTasks }}</h3>
                </a>
            </div>
        @endif

    </div>

    {{-- SCRIPT CHART.JS --}}
    @if(auth()->user()->hasAnyRole(['super-admin', 'tu']))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Konfigurasi Global Chart (Font & Warna)
                Chart.defaults.font.family = "'Poppins', sans-serif";
                Chart.defaults.color = '#9ca3af';

                // Grafik Keuangan (Area Chart)
                const financeCtx = document.getElementById('financeChart').getContext('2d');
                const gradientFinance = financeCtx.createLinearGradient(0, 0, 0, 400);
                gradientFinance.addColorStop(0, 'rgba(47, 128, 237, 0.2)');
                gradientFinance.addColorStop(1, 'rgba(47, 128, 237, 0)');

                new Chart(financeCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode(array_column($financeChart, 'label')) !!},
                        datasets: [{
                            label: 'Pemasukan',
                            data: {!! json_encode(array_column($financeChart, 'total')) !!},
                            borderColor: '#2F80ED',
                            backgroundColor: gradientFinance,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#2F80ED',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' },
                                ticks: { callback: v => 'Rp ' + (v / 1000) + 'k' } 
                            },
                            x: { grid: { display: false } }
                        }
                    }
                });

                // Grafik Kehadiran (Doughnut Chart)
                const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                new Chart(attendanceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(array_column($attendanceChart, 'label')) !!},
                        datasets: [{
                            data: {!! json_encode(array_column($attendanceChart, 'count')) !!},
                            backgroundColor: [
                                '#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e'
                            ],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 20 } } }
                    }
                });
            });
        </script>
    @endif
</x-layouts.dashboard>