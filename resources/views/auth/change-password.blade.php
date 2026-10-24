<x-layouts.public title="Ganti Password — IbraLMS">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-card border border-border rounded-xl p-6">
            <h1 class="text-xl font-bold mb-4">Ganti Password</h1>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @if ($errors->any())
                    <div class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div>
                    <label class="block text-sm mb-1">Password Baru</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                </div>
                <div>
                    <label class="block text-sm mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                </div>
                <button type="submit"
                        class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</x-layouts.public>