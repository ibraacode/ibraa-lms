<x-layouts.dashboard title="PPDB — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Pendaftaran Siswa Baru (PPDB)</h1>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border bg-muted/30">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. pendaftaran..." class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                <select name="status" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">No. Pendaftaran</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">No. HP</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($admissions as $adm)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">{{ $adm->registration_number }}</td>
                            <td class="px-4 py-3 font-medium">{{ $adm->full_name }}</td>
                            <td class="px-4 py-3">{{ $adm->phone }}</td>
                            <td class="px-4 py-3">{{ $adm->chosen_major ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'accepted' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ][$adm->status];
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-bold {{ $badge }}">{{ strtoupper($adm->status) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.admissions.show', $adm->id) }}" class="text-primary hover:underline text-xs font-medium">Detail →</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-foreground/50">Belum ada pendaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">{{ $admissions->links() }}</div>
    </div>
</x-layouts.dashboard>