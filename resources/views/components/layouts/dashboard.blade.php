@props(['title' => 'IbraLMS'])
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      x-data="{
          dark: localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
          sidebarOpen: false
      }"
      x-init="$watch('dark', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-background text-foreground h-[100dvh] overflow-hidden antialiased font-poppins">

    {{-- TOAST (Panggil di paling atas) --}}
    @if (session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if (session('error'))
        <x-toast type="error" :message="session('error')" />
    @endif
    @if ($errors->any())
        <x-toast type="error" :message="$errors->first()" />
    @endif

    <div class="flex h-full w-full relative">
        
        <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 md:hidden" x-cloak></div>

        {{-- SIDEBAR --}}
        <div class="fixed inset-y-0 left-0 z-40 transform transition-transform duration-300 md:relative md:translate-x-0 md:z-auto"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-sidebar />
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="flex-1 flex flex-col h-full overflow-hidden min-w-0">
            
            {{-- TOPBAR --}}
            <header class="border-b border-border bg-card px-4 py-3 flex items-center justify-between shrink-0">
                
                <div class="flex items-center gap-1 sm:gap-2">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-muted md:hidden text-foreground transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <button @click="dark = !dark" class="p-2 rounded-lg hover:bg-muted text-foreground transition-colors">
                        <img x-show="!dark" src="{{ asset('icons/moon-stars.svg') }}" alt="Dark Mode" class="w-5 h-5 opacity-70">
                        <img x-show="dark" src="{{ asset('icons/sun.svg') }}" alt="Light Mode" class="w-5 h-5 opacity-70">
                    </button>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    
                    {{-- DROPDOWN TAHUN AJARAN --}}
                    @if(!request()->routeIs('profile.*') && (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('tu')))
                        <form method="POST" action="{{ route('admin.academic-year.switch') }}" class="border-r border-border pr-2 mr-1 sm:pr-3 sm:mr-2 flex items-center gap-1">
                            @csrf
                            <select name="academic_year" onchange="this.form.submit()" class="px-2 py-1.5 rounded-lg border border-border bg-background text-xs font-bold text-primary focus:outline-none cursor-pointer max-w-[100px] sm:max-w-none truncate">
                                @php
                                    $years = \App\Models\Classroom::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
                                    $activeYear = \App\Helpers\AcademicYear::current();
                                @endphp
                                @forelse($years as $y)
                                    <option value="{{ $y }}" {{ $activeYear == $y ? 'selected' : '' }}>T.A {{ $y }}</option>
                                @empty
                                    <option value="">Belum ada T.A</option>
                                @endforelse
                            </select>
                        </form>
                    @endif

                    {{-- BAHASA --}}
                    <div class="flex items-center bg-muted rounded-lg p-0.5 border border-border">
                        <a href="{{ route('locale.switch', 'id') }}" class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'id' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">ID</a>
                        <a href="{{ route('locale.switch', 'en') }}" class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">EN</a>
                    </div>

                    {{-- PROFIL DROPDOWN --}}
                    <div class="relative border-l border-border pl-3 ml-1" x-data="{ profileOpen: false }">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-2 focus:outline-none">
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-muted flex items-center justify-center shrink-0 border border-border">
                                <img src="{{ asset('icons/profile-default.svg') }}" alt="User" class="w-full h-full object-cover opacity-80">
                            </div>
                            <span class="font-semibold text-sm hidden sm:inline-block truncate max-w-[120px] text-foreground">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-muted-foreground hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="profileOpen" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-card rounded-xl shadow-lg border border-border py-2 z-50" x-cloak>
                            <div class="px-4 py-2 border-b border-border mb-1">
                                <p class="text-xs text-foreground/50">Masuk sebagai</p>
                                <p class="font-bold text-sm truncate">{{ auth()->user()->name }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-muted transition-colors text-foreground">
                                <img src="{{ asset('icons/user.svg') }}" class="w-4 h-4 opacity-70" alt="icon"> Profil Saya
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-background pb-24 md:pb-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    {{-- JANGAN LUPA STACK SCRIPT AGAR TINYMCE MUNCUL --}}
    @stack('scripts')
</body>
</html>