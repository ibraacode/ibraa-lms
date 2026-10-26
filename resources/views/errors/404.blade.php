<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
      x-data="{
          dark: localStorage.getItem('theme') === 'dark' ||
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
      }"
      x-init="$watch('dark', val => {
          localStorage.setItem('theme', val ? 'dark' : 'light');
          document.documentElement.classList.toggle('dark', val);
      })"
      :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground min-h-screen flex flex-col font-poppins">

    {{-- HEADER / LOGO --}}
    <div class="w-full flex items-center justify-center md:justify-start px-6 md:px-8 pt-8">
        <div class="flex items-center gap-3">
            @if(\App\Models\Setting::get('school_logo'))
                <img src="{{ Storage::url(\App\Models\Setting::get('school_logo')) }}" alt="Logo" class="w-10 h-10 object-contain">
            @else
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-white shrink-0">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2z" /></svg>
                </div>
            @endif
            <span class="font-extrabold text-lg text-foreground tracking-tight">{{ \App\Models\Setting::get('school_name', 'IbraLMS') }}</span>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    <div class="flex-1 flex flex-col items-center justify-center w-full max-w-lg mx-auto px-6 text-center mt-8">
        
        {{-- Gambar --}}
        <div class="mb-8 flex justify-center w-full">
            <img src="{{ asset('assets/404.svg') }}" alt="Page Not Found" class="w-[85%] md:w-[350px] h-auto drop-shadow-xl">
        </div>

        {{-- Judul --}}
        <h1 class="text-3xl md:text-4xl font-extrabold text-foreground mb-4">
            Halaman Tidak Ditemukan
        </h1>

        {{-- Deskripsi --}}
        <p class="text-foreground/60 text-sm md:text-base leading-relaxed mb-6">
            Maaf, kami tidak dapat menemukan halaman yang Anda cari. Mungkin halaman telah dipindahkan atau dihapus. Silakan klik <a href="{{ url('/') }}" class="text-primary font-bold hover:underline">di sini</a> untuk kembali ke beranda.
        </p>

    </div>

    {{-- FOOTER --}}
    <div class="py-8 text-center text-xs text-foreground/40">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::get('school_name', 'IbraLMS') }}
    </div>

</body>
</html>