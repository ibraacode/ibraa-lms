<x-layouts.dashboard title="Manajemen Pengguna — IbraLMS">
    <div class="min-h-screen w-full bg-white text-gray-900 font-sans p-6 md:p-10 max-w-5xl mx-auto rounded-3xl" x-data="{ tab: 'siswa' }">
        
        {{-- 1. Breadcrumbs Header --}}
        <nav class="flex items-center gap-2 text-xs text-gray-400 font-medium mb-8">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
            <span>/</span>
            <span class="text-black font-semibold">Manajemen Pengguna</span>
        </nav>

        {{-- 2. Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div class="flex items-center gap-5">
                {{-- Thumbnail --}}
                <div class="relative w-20 h-20 rounded-3xl bg-primary/10 flex flex-col items-center justify-center p-3 shrink-0 overflow-hidden shadow-sm">
                    <div class="w-10 h-12 bg-white rounded-xl border-2 border-primary/20 flex flex-col items-center justify-center p-1 shadow-inner">
                        {{-- Ikon user-card --}}
                        <img src="{{ asset('icons/user-card.svg') }}" alt="icon" class="w-6 h-6 object-contain">
                    </div>
                </div>

                {{-- Details --}}
                <div class="space-y-1">
                    <h1 class="text-2xl font-extrabold text-black tracking-tight">
                        Data Pengguna IbraLMS
                    </h1>
                    <div class="flex items-center gap-4 text-xs font-semibold text-gray-500">
                        <span>Total: {{ $classrooms->sum('students_count') + $staff->total() }} Akun</span>
                        <span>T.A {{ \App\Models\Setting::get('active_academic_year', '2026/2027') }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-2">
                <a href="{{ route('admin.users.import') }}" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-bold text-xs px-6 py-3 rounded-full transition-all shadow-sm flex items-center gap-2">
                    <img src="{{ asset('icons/upload-file.svg') }}" class="w-4 h-4 object-contain" alt="icon"> Import
                </a>
                <a href="{{ route('admin.users.create') }}" class="bg-primary hover:opacity-90 text-primary-foreground font-bold text-xs px-6 py-3 rounded-full transition-all shadow-md flex items-center gap-2">
                    <img src="{{ asset('icons/add-circle.svg') }}" class="w-4 h-4 object-contain invert" alt="icon"> Tambah Akun
                </a>
            </div>
        </div>

        {{-- 3. TAB NAVIGATION --}}
        <div class="flex border-b border-gray-200 mb-6">
            <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Data Siswa (Per Kelas)
            </button>
            <button @click="tab = 'staf'" :class="tab === 'staf' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Guru & Staf
            </button>
        </div>

        {{-- ==================== TAB 1: SISWA (Desain Course) ==================== --}}
        <section x-show="tab === 'siswa'" class="space-y-4">
            <div class="space-y-3">
                @forelse($classrooms as $classroom)
                    <a href="#" class="w-full bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between shadow-sm hover:border-primary transition-all block group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                {{-- Ikon gedung/kelas --}}
                                <img src="{{ asset('icons/building.svg') }}" class="w-5 h-5 object-contain" alt="icon">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-900 leading-snug group-hover:text-primary transition-colors">
                                    Kelas {{ $classroom->name }}
                                </h3>
                                <p class="text-xs text-gray-400 font-normal">
                                    Tingkat {{ $classroom->grade_level }} • Jurusan {{ $classroom->major_code ?? 'Umum' }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 bg-primary/10 text-primary text-[11px] font-bold rounded-full border border-dashed border-primary/30">
                                {{ $classroom->students_count }} Siswa
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm">Belum ada kelas.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $classrooms->links() }}</div>
        </section>

        {{-- ==================== TAB 2: GURU & STAF ==================== --}}
        <section x-show="tab === 'staf'" style="display: none;" class="space-y-4">
            
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
            @endif

            <div class="space-y-3">
                @forelse($staff as $user)
                    <div class="w-full bg-white rounded-2xl border border-gray-100 p-4 flex flex-col md:flex-row md:items-center justify-between shadow-sm gap-4 hover:border-primary transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                                {{-- Ikon user --}}
                                <img src="{{ asset('icons/user.svg') }}" class="w-5 h-5 object-contain opacity-60" alt="icon">
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-gray-900 leading-snug">
                                    {{ $user->name }}
                                </h3>
                                <p class="text-xs text-gray-400 font-normal">
                                    {{ $user->email }} {!! $user->nis ? '• NIS: '.$user->nis : '' !!} {!! $user->nip ? '• NIP: '.$user->nip : '' !!}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-gray-50 text-gray-600 text-[11px] font-bold rounded-full border border-dashed border-gray-300 uppercase">
                                {{ $user->roles->first()?->name ?? 'Staff' }}
                            </span>
                            
                            {{-- Status Aktif/Nonaktif --}}
                            @if($user->is_active)
                                <span class="text-[10px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded">AKTIF</span>
                            @else
                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded">NONAKTIF</span>
                            @endif

                            <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                                @csrf
                                <button type="submit" class="text-xs font-medium text-gray-400 hover:text-primary ml-2 underline">
                                    Ubah
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-400 text-sm">Belum ada staf.</div>
                @endforelse
            </div>
            <div class="mt-4">{{ $staff->links() }}</div>
        </section>

    </div>
</x-layouts.dashboard>