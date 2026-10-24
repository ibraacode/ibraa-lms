<x-layouts.dashboard title="Audit Log — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Audit Log</h1>
            <p class="text-sm text-foreground/60">Rekam jejak aktivitas sistem. Data ini tidak dapat dihapus.</p>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border bg-muted/30">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aksi, deskripsi, atau nama user..." 
                       class="flex-1 px-4 py-2 rounded-lg border border-border bg-background text-sm">
                <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Aksi</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($logs as $log)
                        <tr class="hover:bg-muted/20">
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-foreground/70">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $log->user ? $log->user->name : 'Sistem / Guest' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $colors = [
                                        'LOGIN' => 'bg-green-100 text-green-700',
                                        'LOGOUT' => 'bg-gray-100 text-gray-700',
                                        'UPDATE_GRADE' => 'bg-yellow-100 text-yellow-700',
                                        'CREATE_USER' => 'bg-blue-100 text-blue-700',
                                        'MOVE_STUDENT' => 'bg-purple-100 text-purple-700',
                                    ];
                                    $color = $colors[$log->action] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-bold {{ $color }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-foreground/80 max-w-md truncate">
                                {{ $log->description }}
                            </td>
                            <td class="px-4 py-3 text-xs text-foreground/50 font-mono">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-foreground/50">Belum ada aktivitas tercatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">{{ $logs->links() }}</div>
    </div>
</x-layouts.dashboard>