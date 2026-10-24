<x-layouts.dashboard title="Profil Saya — IbraLMS">
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold mb-2">
            {{ app()->getLocale() === 'id' ? 'Profil Saya' : 'My Profile' }}
        </h1>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Data Akun --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">Data Akun</h2>

                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" disabled
                           class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    <p class="text-xs text-foreground/50 mt-1">Email tidak dapat diubah.</p>
                </div>

                <div>
                    <label class="block text-sm mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>

                <div>
                    <label class="block text-sm mb-1">Nomor HP</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>

                @if (auth()->user()->nis)
                    <div>
                        <label class="block text-sm mb-1">NIS</label>
                        <input type="text" value="{{ auth()->user()->nis }}" disabled
                               class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                @endif

                @if (auth()->user()->nip)
                    <div>
                        <label class="block text-sm mb-1">NIP</label>
                        <input type="text" value="{{ auth()->user()->nip }}" disabled
                               class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                @endif
            </div>

            {{-- Ganti Password --}}
            <div class="bg-card border border-border rounded-xl p-6 space-y-4 mb-6">
                <h2 class="font-semibold text-lg border-b border-border pb-2">Ganti Password</h2>
                <p class="text-sm text-foreground/60">Kosongkan jika tidak ingin mengubah password.</p>

                <div>
                    <label class="block text-sm mb-1">Password Lama</label>
                    <input type="password" name="current_password"
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm mb-1">Password Baru</label>
                    <input type="password" name="new_password"
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>
                <div>
                    <label class="block text-sm mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation"
                           class="w-full px-4 py-2 rounded-lg border border-border bg-background focus:ring-2 focus:ring-primary outline-none">
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3 rounded-xl bg-primary text-primary-foreground font-bold hover:opacity-90 transition-all shadow-md">
                Simpan Perubahan
            </button>
        </form>
    </div>
</x-layouts.dashboard>