<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — IbraLMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Body Full Screen --}}
<body style="margin: 0; padding: 0; height: 100dvh; display: flex; font-family: 'Poppins', sans-serif; overflow: hidden;">

    {{-- KIRI: Area Hitam (Hanya muncul di Desktop/Tablet) --}}
    <div class="hidden md:flex" style="width: 50%; background-color: #000000; position: relative; flex-direction: column; justify-content: flex-end; padding: 64px; overflow: hidden;">
        
        {{-- Efek Garis Biru --}}
        <div style="position: absolute; inset: 0; z-index: 0; pointer-events: none;">
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80%; background: linear-gradient(to top, rgba(37,99,235,0.9), rgba(59,130,246,0.4), transparent); filter: blur(12px);"></div>
            <div style="position: absolute; inset: 0; background: repeating-linear-gradient(90deg, transparent, transparent 20px, rgba(37,99,235,0.25) 20px, rgba(37,99,235,0.25) 40px); mix-blend-mode: screen; opacity: 0.7;"></div>
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 50%; background: linear-gradient(to bottom, #000000, transparent);"></div>
        </div>

        {{-- Teks --}}
        <div style="position: relative; z-index: 10; color: white; max-width: 500px;">
            <h1 style="font-size: 42px; font-weight: 600; line-height: 1.2; margin: 0 0 16px 0; letter-spacing: -0.025em;">
                Simplify The Process.<br />
                Supercharge The Results.
            </h1>
            <p style="color: #9ca3af; font-size: 16px; margin: 0;">
                Focus On The Big Picture While We Automate The Daily Details.
            </p>
        </div>
    </div>

    {{-- KANAN: Area Form Putih --}}
    <div style="width: 100%; md:width: 50%; background-color: white; display: flex; align-items: center; justify-content: center; padding: 32px; overflow-y: auto;">
        
        <div style="width: 100%; max-width: 400px;">
            
            {{-- Header & Logo --}}
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 32px;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px;">
                    @if(\App\Models\Setting::get('school_logo'))
                        <img src="{{ Storage::url(\App\Models\Setting::get('school_logo')) }}" alt="Logo" style="height: 48px; width: auto; object-fit: contain;">
                    @else
                        <div style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid black; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 10px; height: 10px; background-color: black; border-radius: 50%;"></div>
                        </div>
                    @endif
                    <span style="font-weight: 800; font-size: 20px; letter-spacing: -0.025em; color: black; text-transform: uppercase;">
                        {{ \App\Models\Setting::get('school_name', 'IbraLMS') }}
                    </span>
                </div>
                <h2 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0 0 8px 0;">
                    Masuk ke akun Anda
                </h2>
                <p style="font-size: 14px; color: #9ca3af; margin: 0;">
                    Selamat datang kembali. Silakan isi detail Anda.
                </p>
            </div>

            {{-- Error Message --}}
            @if ($errors->any())
                <div style="margin-bottom: 24px; padding: 12px; border-radius: 8px; background-color: #fef2f2; color: #b91c1c; font-size: 13px; text-align: center;">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.attempt') }}" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                {{-- Tahun Ajaran --}}
                <div>
                    <label style="font-size: 13px; color: #6b7280; font-weight: 500; margin-left: 4px; display: block; margin-bottom: 6px;">Tahun Ajaran</label>
                    <select name="academic_year" required style="width: 100%; padding: 14px 16px; font-size: 14px; color: #1f2937; border-radius: 9999px; border: 1px solid #e5e7eb; outline: none; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); cursor: pointer;">
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Email --}}
                <div>
                    <label style="font-size: 13px; color: #6b7280; font-weight: 500; margin-left: 4px; display: block; margin-bottom: 6px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           style="width: 100%; padding: 14px 16px; font-size: 14px; color: #1f2937; border-radius: 9999px; border: 1px solid #e5e7eb; outline: none; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); box-sizing: border-box;"
                           placeholder="Masukkan email Anda">
                </div>

                {{-- Password --}}
                <div>
                    <label style="font-size: 13px; color: #6b7280; font-weight: 500; margin-left: 4px; display: block; margin-bottom: 6px;">Password</label>
                    <input type="password" name="password" required
                           style="width: 100%; padding: 14px 16px; font-size: 14px; color: #1f2937; border-radius: 9999px; border: 1px solid #e5e7eb; outline: none; background-color: white; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); box-sizing: border-box; letter-spacing: 0.1em;"
                           placeholder="••••••••">
                </div>

                {{-- Remember Me --}}
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 13px; margin-top: -4px;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #6b7280; cursor: pointer; user-select: none;">
                        <input type="checkbox" name="remember" style="border-radius: 4px; border: 1px solid #d1d5db; width: 16px; height: 16px; cursor: pointer;">
                        <span>Ingat saya</span>
                    </label>
                </div>

                {{-- Button --}}
                <button type="submit"
                        style="width: 100%; padding: 16px; background-color: #1d4ed8; color: white; font-weight: 600; font-size: 15px; border-radius: 9999px; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: background-color 0.2s; margin-top: 8px;">
                    <span>Masuk</span>
                    <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>

            {{-- Footer --}}
            <div style="text-align: center; font-size: 13px; color: #9ca3af; margin-top: 32px;">
                Kembali ke <a href="{{ url('/') }}" style="color: #1d4ed8; font-weight: 600; text-decoration: none;">Beranda</a>
            </div>

        </div>
    </div>

</body>
</html>