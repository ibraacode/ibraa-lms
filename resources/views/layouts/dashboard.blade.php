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
<body class="bg-background text-foreground min-h-screen antialiased">

    <div class="flex min-h-screen relative">

        {{-- Overlay gelap saat sidebar terbuka di HP --}}
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-30 md:hidden"
             x-cloak></div>

        {{-- Sidebar: drawer di HP, permanent di desktop --}}
        <div class="fixed inset-y-0 left-0 z-40 transform transition-transform duration-200
                    md:relative md:translate-x-0 md:z-auto"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-sidebar />
        </div>

        {{-- Konten utama --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <header class="border-b border-border bg-card px-4 py-3 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-2">
                    {{-- Tombol burger: hanya tampil di HP --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg hover:bg-muted md:hidden"
                            title="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <button @click="dark = !dark"
                            class="p-2 rounded-lg hover:bg-muted"
                            title="Ganti tema">
                        <span x-show="!dark">🌙</span>
                        <span x-show="dark">☀️</span>
                    </button>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('locale.switch', 'id') }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-medium
                              {{ app()->getLocale() === 'id' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted' }}">
                        ID
                    </a>
                    <a href="{{ route('locale.switch', 'en') }}"
                       class="px-2.5 py-1 rounded-lg text-xs font-medium
                              {{ app()->getLocale() === 'en' ? 'bg-primary text-primary-foreground' : 'hover:bg-muted' }}">
                        EN
                    </a>

                    <div class="flex items-center gap-2 text-sm border-l border-border pl-3 ml-1">
                        <span class="font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline text-xs">
                                {{ app()->getLocale() === 'id' ? 'Keluar' : 'Logout' }}
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Isi halaman --}}
            <main class="flex-1 p-4 md:p-6 overflow-auto">
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

        </div>

    </div>

          </body>
</html>