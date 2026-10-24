<x-layouts.public title="Masuk — IbraLMS">
    <div class="min-h-screen flex items-center justify-center px-4 bg-background">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-bold text-primary">{{ \App\Models\Setting::get('school_name', 'IbraLMS') }}</a>
                <p class="text-sm text-foreground/60 mt-2">Masuk ke akun Anda</p>
            </div>

            <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf

                    {{-- Dropdown Tahun Ajaran --}}
                    <div>
                        <label for="academic_year" class="block text-sm font-medium mb-1">Tahun Ajaran</label>
                        <select name="academic_year" id="academic_year" required class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remember" class="rounded border-border">
                        Ingat saya
                    </label>

                    <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90 transition">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>