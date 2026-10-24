<x-layouts.dashboard title="Manajemen Modul — IbraLMS">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.settings.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <h1 class="text-2xl font-bold">Manajemen Modul (Saklar Fitur)</h1>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.settings.features.update') }}" class="bg-card border border-border rounded-xl p-6 space-y-6">
            @csrf
            
            @foreach($features as $key => $feature)
                <div class="flex items-start justify-between gap-4 pb-6 border-b border-border last:border-0 last:pb-0">
                    <div>
                        <h3 class="font-bold text-lg">{{ $feature['label'] }}</h3>
                        <p class="text-sm text-foreground/60 mt-1">{{ $feature['description'] }}</p>
                    </div>
                    <div>
                        {{-- Input Hidden untuk nilai 0 jika checkbox tidak dicentang --}}
                        <input type="hidden" name="{{ $key }}" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="{{ $key }}" value="1" class="sr-only peer" {{ $feature['active'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                </div>
            @endforeach

            <div class="pt-4 border-t border-border mt-6">
                <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">
                    Simpan Pengaturan Modul
                </button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>