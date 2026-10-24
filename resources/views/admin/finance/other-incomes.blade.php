<x-layouts.dashboard title="Pemasukan Lain — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Pemasukan Lain (BOS, Hibah)</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.fee-types') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Jenis Tagihan</a>
            <a href="{{ route('admin.finance.invoices') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Tagihan Siswa</a>
            <a href="{{ route('admin.finance.summary') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Rekap Keuangan</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Form --}}
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Catat Pemasukan Baru</h2>
            <form method="POST" action="{{ route('admin.finance.other-incomes.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm mb-1">Sumber Dana</label>
                    <select name="source" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="Dana BOS">Dana BOS</option>
                        <option value="Hibah">Hibah</option>
                        <option value="Sponsor">Sponsor</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" required placeholder="5000000" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tanggal Diterima</label>
                    <input type="date" name="received_at" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Keterangan (Opsional)</label>
                    <input type="text" name="description" placeholder="Tahap 1 Tahun 2026" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">No. Referensi (Opsional)</label>
                    <input type="text" name="reference" placeholder="No. Surat / Bukti Transfer" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                </div>
                <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium hover:opacity-90">Simpan Pemasukan</button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Sumber</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($incomes as $inc)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $inc->received_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $inc->source }}</p>
                                @if($inc->description)
                                    <p class="text-xs text-foreground/50">{{ $inc->description }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-green-600 font-bold">Rp {{ number_format($inc->amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-xs text-foreground/60">{{ $inc->recordedBy->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada pemasukan dicatat.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-border">{{ $incomes->links() }}</div>
        </div>
    </div>
</x-layouts.dashboard>