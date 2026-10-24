<x-layouts.dashboard title="Role & Permission — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold">Role & Permission</h1>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium text-center">
            ➕ Tambah Role Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($roles as $role)
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-lg text-primary">{{ $role->name }}</h3>
                    <span class="text-xs bg-muted px-2 py-1 rounded-md">{{ $role->permissions_count }} Izin</span>
                </div>
                
                <div class="mb-4 max-h-24 overflow-y-auto">
                    @forelse($role->permissions as $perm)
                        <span class="inline-block text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-md mr-1 mb-1">{{ $perm->name }}</span>
                    @empty
                        <span class="text-xs text-foreground/50">Belum ada izin.</span>
                    @endforelse
                </div>

                @if($role->name !== 'super-admin')
                    <a href="{{ route('admin.roles.edit', $role->id) }}" class="block text-center w-full py-1.5 bg-muted hover:bg-primary hover:text-white rounded-md text-sm font-medium transition">
                        Atur Izin
                    </a>
                @else
                    <div class="text-center w-full py-1.5 bg-muted/50 text-foreground/50 rounded-md text-sm font-medium">
                        Sistem Terkunci
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</x-layouts.dashboard>