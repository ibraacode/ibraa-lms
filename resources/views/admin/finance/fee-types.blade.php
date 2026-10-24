<x-layouts.dashboard title="Jenis Tagihan — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Jenis Tagihan</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.invoices') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Tagihan Siswa</a>
            <a href="{{ route('admin.finance.summary') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Rekap Keuangan</a>
            <a href="{{ route('admin.finance.other-incomes') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Pemasukan Lain</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Tambah Jenis Tagihan</h2>
            <form method="POST" action="{{ route('admin.finance.fee-types.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Nama Tagihan</label>
                    <input type="text" name="name" required placeholder="SPP / Seragam / Buku" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Nominal Default</label>
                    <input type="number" name="default_amount" value="0" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Periode Penagihan</label>
                    <select name="period" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="one_time">Sekali Bayar</option>
                        <option value="monthly">Bulanan (SPP)</option>
                        <option value="weekly">Mingguan</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_mandatory" value="1" checked class="rounded border-border">
                        Wajib untuk semua siswa
                    </label>
                </div>
                <div>
                    <label class="block text-sm mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan</button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Periode</th>
                        <th class="px-4 py-3">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($feeTypes as $ft)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $ft->name }}</td>
                            <td class="px-4 py-3 uppercase text-xs font-bold text-primary">{{ str_replace('_', ' ', $ft->period) }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($ft->default_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-6 text-center text-foreground/50">Belum ada jenis tagihan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $feeTypes->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>