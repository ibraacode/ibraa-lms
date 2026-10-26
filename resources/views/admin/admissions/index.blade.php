<x-layouts.dashboard title="PPDB — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold">Panel Seleksi PPDB</h1>
            <p class="text-sm text-foreground/60">Kelola pendaftar & seleksi otomatis berdasarkan kuota.</p>
        </div>
        <form method="POST" action="{{ route('admin.admissions.auto-select') }}" onsubmit="return confirm('Jalankan auto-seleksi? Status pendaftar akan diubah berdasarkan nilai tertinggi hingga kuota.')">
            @csrf
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-primary-foreground font-bold text-sm shadow-md hover:opacity-90 flex items-center gap-2">
                ⚡ Auto-Seleksi Top {{ $stats['quota'] }}
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Total Pendaftar</p>
            <p class="text-3xl font-bold text-foreground">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Menunggu</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Diterima</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['accepted'] }} / {{ $stats['quota'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Tidak Lulus</p>
            <p class="text-3xl font-bold text-red-500">{{ $stats['rejected'] }}</p>
        </div>
    </div>

    {{-- Filter & Pencarian --}}
    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="p-4 border-b border-border bg-muted/30">
            <form method="GET" class="flex flex-col md:flex-row gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. pendaftaran..." class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                <select name="status" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">No. Pendaftaran</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Nilai Akhir</th>
                        <th class="px-4 py-3">Dokumen</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($admissions as $adm)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs">{{ $adm->registration_number }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $adm->full_name }}</div>
                                <div class="text-xs text-foreground/50">{{ $adm->chosen_major }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($adm->final_score)
                                    <span class="font-bold text-primary">{{ number_format($adm->final_score, 2) }}</span>
                                @else
                                    <span class="text-foreground/40">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($adm->is_documents_verified)
                                    <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">✓ Verified</span>
                                @else
                                    <span class="text-[10px] font-bold text-yellow-600 bg-yellow-50 px-2 py-0.5 rounded">Belum</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = [
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'accepted' => 'bg-green-100 text-green-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                    ][$adm->status] ?? '';
                                @endphp
                                <span class="px-2 py-1 rounded-md text-xs font-bold {{ $badge }}">{{ strtoupper($adm->status) }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.admissions.show', $adm->id) }}" class="text-primary hover:underline text-xs font-bold">Detail →</a>
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