<x-layouts.dashboard title="Edit Role — IbraLMS">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.roles.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
            <div>
                <h1 class="text-2xl font-bold">Atur Izin Role</h1>
                <p class="text-sm text-primary font-medium">{{ $role->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="bg-card border border-border rounded-xl p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="space-y-4 bg-muted/30 p-4 rounded-lg">
                @foreach($permissions as $group => $items)
                    <div>
                        <h4 class="text-xs font-bold text-primary uppercase tracking-wide mb-2">{{ $group }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($items as $perm)
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                           {{ in_array($perm->name, $rolePermissions) ? 'checked' : '' }}
                                           class="rounded border-border">
                                    {{ $perm->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium hover:opacity-90">Simpan Perubahan</button>
        </form>
    </div>
</x-layouts.dashboard>