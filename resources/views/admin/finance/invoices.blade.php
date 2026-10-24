<x-layouts.dashboard title="Tagihan Siswa — IbraLMS">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tagihan Siswa</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.fee-types') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Jenis Tagihan</a>
            <a href="{{ route('admin.finance.summary') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Rekap Keuangan</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Buat Tagihan Baru</h2>
            <form method="POST" action="{{ route('admin.finance.invoices.store') }}" class="space-y-3" x-data="{ status: 'unpaid' }">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Siswa</label>
                    <select name="student_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach(\App\Models\User::role('siswa')->get() as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Jenis Tagihan</label>
                    <select name="fee_type_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">-- Pilih --</option>
                        @foreach($feeTypes as $ft)
                            <option value="{{ $ft->id }}">{{ $ft->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tenggat</label>
                    <input type="date" name="due_date" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Status</label>
                    <select name="status" x-model="status" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="unpaid">Perlu Dibayar</option>
                        <option value="exempt">Bebas Biaya (Sekolah Gratis)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Tagihan</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <div class="p-4 border-b border-border bg-muted/30">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    <select name="status" class="px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Sebagian</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                        <option value="exempt" {{ request('status') == 'exempt' ? 'selected' : '' }}>Bebas Biaya</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm">Cari</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-muted/50 border-b border-border">
                        <tr>
                            <th class="px-4 py-3">Siswa</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Nominal</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($invoices as $inv)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $inv->student->name }}</td>
                                <td class="px-4 py-3">{{ $inv->feeType->name }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($inv->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $badge = [
                                            'unpaid' => 'bg-red-100 text-red-700',
                                            'partial' => 'bg-yellow-100 text-yellow-700',
                                            'paid' => 'bg-green-100 text-green-700',
                                            'exempt' => 'bg-blue-100 text-blue-700',
                                        ][$inv->status] ?? '';
                                    @endphp
                                    <span class="px-2 py-1 rounded-md text-xs font-bold {{ $badge }}">
                                        {{ strtoupper($inv->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('admin.finance.invoices.show', $inv->id) }}" class="text-primary hover:underline text-xs font-medium">Detail →</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-6 text-center text-foreground/50">Belum ada tagihan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-border">{{ $invoices->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>