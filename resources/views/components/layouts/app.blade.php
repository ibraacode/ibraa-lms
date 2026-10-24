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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-background text-foreground h-screen overflow-hidden antialiased">

    <div class="flex h-screen w-full relative">
        
        {{-- Overlay gelap saat sidebar terbuka di HP --}}
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-30 md:hidden"
             x-cloak></div>

        {{-- SIDEBAR --}}
        <div class="fixed inset-y-0 left-0 z-40 transform transition-transform duration-300 md:relative md:translate-x-0 md:z-auto"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-sidebar />
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="flex-1 flex flex-col h-full overflow-hidden min-w-0">
            
            {{-- TOPBAR --}}
            <header class="border-b border-border bg-card px-4 py-3 flex items-center justify-between shrink-0">
                
                {{-- SISI KIRI: Tombol Burger & Dark Mode --}}
                <div class="flex items-center gap-1 sm:gap-2">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg hover:bg-muted md:hidden text-foreground transition-colors"
                            title="Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <button @click="dark = !dark"
                            class="p-2 rounded-lg hover:bg-muted text-foreground transition-colors"
                            title="Ganti tema">
                        <img x-show="!dark" src="{{ asset('icons/moon-stars.svg') }}" alt="Dark Mode" class="w-5 h-5 opacity-70">
                        <img x-show="dark" src="{{ asset('icons/sun.svg') }}" alt="Light Mode" class="w-5 h-5 opacity-70">
                    </button>
                </div>

                {{-- SISI KANAN: Tahun Ajaran (PAKSA MUNCUL TANPA IF), Bahasa, Profil --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    {{-- ==================================================== --}}
                    {{-- DROPDOWN TAHUN AJARAN (DEBUG MODE) --}}
                    {{-- ==================================================== --}}
                    <form method="POST" action="{{ route('admin.academic-year.switch') }}" class="border-r border-border pr-2 mr-1 sm:pr-3 sm:mr-2 flex items-center gap-1">
                        @csrf
                        <span class="text-[10px] text-red-500 font-bold">CEK T.A:</span>
                        <select name="academic_year" onchange="this.form.submit()" class="px-2 py-1.5 rounded-lg border border-border bg-background text-xs font-bold text-primary focus:outline-none cursor-pointer">
                            @php
                                // Ambil semua tahun ajaran, jika kosong beri teks default
                                $years = \App\Models\Classroom::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
                                $activeYear = \App\Helpers\AcademicYear::current();
                            @endphp
                            
                            @if($years->isEmpty())
                                <option value="">DB Kelas Kosong</option>
                            @else
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ $activeYear == $y ? 'selected' : '' }}>T.A {{ $y }}</option>
                                @endforeach
                            @endif
                        </select>
                    </form>

                    {{-- Tombol Bahasa --}}
                    <div class="flex items-center bg-muted rounded-lg p-0.5 border border-border">
                        <a href="{{ route('locale.switch', 'id') }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors
                                  {{ app()->getLocale() === 'id' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                            ID
                        </a>
                        <a href="{{ route('locale.switch', 'en') }}"
                           class="px-2.5 py-1 rounded-md text-xs font-semibold transition-colors
                                  {{ app()->getLocale() === 'en' ? 'bg-card text-primary shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                            EN
                        </a>
                    </div>

                    {{-- Profil & Logout --}}
                    <div class="flex items-center gap-2 border-l border-border pl-3 ml-1">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-muted flex items-center justify-center shrink-0 border border-border">
                            <img src="{{ asset('icons/profile-default.svg') }}" alt="User" class="w-full h-full object-cover opacity-80">
                        </div>
                        <span class="font-semibold text-sm hidden sm:inline-block truncate max-w-[120px] text-foreground">
                            {{ auth()->user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="ml-1 sm:ml-2">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-bold transition-colors px-2 py-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20">
                                {{ app()->getLocale() === 'id' ? 'Keluar' : 'Logout' }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- ISI HALAMAN --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-background">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

        </div>
    </div>

</body>
</html>