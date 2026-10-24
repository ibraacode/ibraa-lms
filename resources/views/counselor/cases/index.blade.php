<x-layouts.dashboard title="Kasus BK — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Kasus Bimbingan Konseling</h1>
        <a href="{{ route('counselor.cases.create') }}" class="px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">➕ Buka Kasus Baru</a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Judul Kasus</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($cases as $c)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $c->student->name }}</td>
                            <td class="px-4 py-3">{{ $c->title }}</td>
                            <td class="px-4 py-3 uppercase text-xs">{{ $c->category }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'open' => 'bg-yellow-100 text-yellow-700',
                                        'in_progress' => 'bg-blue-100 text-blue-700',
                                        'closed' => 'bg-green-100 text-green-700',
                                    ][$c->status] ?? '';
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-bold {{ $badge }}">{{ strtoupper(str_replace('_', ' ', $c->status)) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('counselor.cases.show', $c->id) }}" class="text-primary hover:underline text-xs font-medium">Buka →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada kasus.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">{{ $cases->links() }}</div>
    </div>
</x-layouts.dashboard>