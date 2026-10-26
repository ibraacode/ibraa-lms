<x-layouts.dashboard title="Manajemen Pengguna — IbraLMS">
    <div class="min-h-screen w-full bg-background text-foreground font-sans p-6 md:p-10 max-w-6xl mx-auto rounded-3xl" x-data="{ tab: 'siswa' }">
        
        {{-- 1. Breadcrumbs Header --}}
        <nav class="flex items-center gap-2 text-xs text-foreground/50 font-medium mb-8">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <span>/</span>
            <span class="text-foreground font-semibold">Manajemen Pengguna</span>
        </nav>

        {{-- 2. Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-5">
                {{-- Thumbnail --}}
                <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex flex-col items-center justify-center p-3 shrink-0 overflow-hidden shadow-sm">
                    <div class="w-10 h-12 bg-card rounded-xl border-2 border-primary/20 flex flex-col items-center justify-center p-1 shadow-inner">
                        <img src="{{ asset('icons/user-card.svg') }}" alt="icon" class="w-6 h-6 object-contain">
                    </div>
                </div>

                {{-- Details --}}
                <div class="space-y-1">
                    <h1 class="text-2xl font-extrabold text-foreground tracking-tight">
                        Data Pengguna IbraLMS
                    </h1>
                    <div class="flex items-center gap-4 text-xs font-semibold text-foreground/60">
                        <span>Total: {{ $classrooms->sum('students_count') + $staff->total() }} Akun</span>
                        <span>T.A {{ \App\Helpers\AcademicYear::current() }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.users.import') }}" class="bg-card border border-border text-foreground/80 hover:bg-muted font-bold text-xs px-6 py-3 rounded-full transition-all shadow-sm flex items-center gap-2">
                    <img src="{{ asset('icons/upload-file.svg') }}" class="w-4 h-4 object-contain" alt="icon"> Import
                </a>
                <a href="{{ route('admin.users.create') }}" class="bg-primary hover:opacity-90 text-primary-foreground font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2">
                    <img src="{{ asset('icons/add-circle.svg') }}" class="w-4 h-4 object-contain invert" alt="icon"> Tambah Akun
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        {{-- 3. TAB NAVIGATION --}}
        <div class="flex border-b border-border mb-6">
            <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'border-primary text-primary' : 'border-transparent text-foreground/50 hover:text-foreground'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Data Siswa (Per Kelas)
            </button>
            <button @click="tab = 'staf'" :class="tab === 'staf' ? 'border-primary text-primary' : 'border-transparent text-foreground/50 hover:text-foreground'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Guru & Staf
            </button>
        </div>

        {{-- ==================== TAB 1: SISWA (Desain Course) ==================== --}}
        <section x-show="tab === 'siswa'" class="space-y-4">
            <div class="space-y-3">
                @forelse($classrooms as $classroom)
                    <div class="w-full bg-card rounded-2xl border border-border p-4 flex flex-col md:flex-row md:items-center justify-between shadow-sm hover:border-primary transition-all gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <img src="{{ asset('icons/building.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-foreground leading-snug">
                                    Kelas {{ $classroom->name }}
                                </h3>
                                <p class="text-xs text-foreground/50 font-normal">
                                    Tingkat {{ $classroom->grade_level }} • Jurusan {{ $classroom->major_code ?? 'Umum' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-primary/10 text-primary text-[11px] font-bold rounded-full border border-dashed border-primary/30">
                                {{ $classroom->students_count }} Siswa
                            </span>
                            {{-- Tombol Lihat Siswa (Mengarah ke Detail Kelas jika ada) --}}
                            <a href="{{ route('admin.students.assign') }}" class="px-3 py-1 bg-muted text-foreground/70 text-[11px] font-bold rounded-full hover:bg-primary hover:text-white transition-colors">
                                Lihat / Atur →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-foreground/50 text-sm">Belum ada kelas di tahun ajaran ini.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $classrooms->links() }}</div>
        </section>

        {{-- ==================== TAB 2: GURU & STAF ==================== --}}
        <section x-show="tab === 'staf'" style="display: none;" class="space-y-4">
            <div class="space-y-3">
                @forelse($staff as $user)
                    <div class="w-full bg-card rounded-2xl border border-border p-4 flex flex-col md:flex-row md:items-center justify-between shadow-sm gap-4 hover:border-primary transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-muted text-foreground/50 flex items-center justify-center shrink-0">
                                <img src="{{ asset('icons/user.svg') }}" class="w-5 h-5 object-contain opacity-60" alt="icon">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-foreground leading-snug flex items-center gap-2">
                                    {{ $user->name }}
                                    
                                    {{-- WARNING JIKA DATA BELUM LENGKAP / BELUM VERIFIKASI --}}
                                    @if($user->teacherProfile)
                                        @if($user->teacherProfile->is_verified)
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-md" title="Data sudah difinalisasi">✓ Terverifikasi</span>
                                        @elseif(!$user->teacherProfile->isComplete())
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded-md" title="Data profil belum lengkap">⚠ Belum Lengkap</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-md" title="Lengkap, menunggu verifikasi">Menunggu Verifikasi</span>
                                        @endif
                                    @endif
                                </h3>
                                <p class="text-xs text-foreground/50 font-normal">
                                    {{ $user->email }} {!! $user->nis ? '• NIS: '.$user->nis : '' !!} {!! $user->nip ? '• NIP: '.$user->nip : '' !!}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-3 py-1 bg-muted text-foreground/60 text-[11px] font-bold rounded-full border border-dashed border-border uppercase">
                                {{ $user->roles->first()?->name ?? 'Staff' }}
                            </span>
                            
                            {{-- Status Aktif/Nonaktif --}}
                            @if($user->is_active)
                                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded">AKTIF</span>
                            @else
                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded">NONAKTIF</span>
                            @endif

                            {{-- TOMBOL FINALISASI BIODATA (Hanya muncul jika belum diverifikasi & profil ada) --}}
                            @if($user->teacherProfile && !$user->teacherProfile->is_verified)
                                <form method="POST" action="{{ route('admin.verify-profile', ['type' => 'teacher', 'user' => $user->id]) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-primary text-primary-foreground rounded-full text-[10px] font-bold hover:opacity-90 transition-colors" onclick="return confirm('Finalisasi biodata {{ $user->name }}? Data tidak dapat diubah setelah ini.')">
                                        Finalisasi
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol Nonaktifkan Akun --}}
                            <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-foreground/40 hover:text-red-500 ml-2 underline">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-foreground/50 text-sm">Belum ada staf.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $staff->links() }}</div>
        </section>

    </div>
</x-layouts.dashboard>