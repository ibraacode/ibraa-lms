<x-layouts.dashboard title="Detail Tagihan — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.finance.invoices') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $invoice->invoice_number }}</h1>
            <p class="text-sm text-foreground/60">{{ $invoice->student->name }} | {{ $invoice->feeType->name }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Total Tagihan</p>
            <p class="text-2xl font-bold">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Sudah Dibayar</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($invoice->totalPaid(), 0, ',', '.') }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-4">
            <p class="text-xs text-foreground/60">Sisa</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($invoice->remaining(), 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-card border border-border rounded-xl p-6 h-fit">
            <h2 class="font-semibold text-lg mb-4">Catat Pembayaran</h2>
            @if($invoice->status === 'exempt')
                <div class="p-3 rounded-lg bg-blue-50 text-blue-700 text-sm mb-3">Tagihan ini ditandai Bebas Biaya.</div>
            @elseif($invoice->remaining() <= 0)
                <div class="p-3 rounded-lg bg-green-50 text-green-700 text-sm mb-3">Tagihan sudah lunas.</div>
            @else
                <form method="POST" action="{{ route('admin.finance.payments.store', $invoice->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm mb-1">Jumlah (Rp)</label>
                        <input type="number" name="amount_paid" required max="{{ $invoice->remaining() }}" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Tanggal</label>
                        <input type="date" name="paid_at" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Metode</label>
                        <select name="method" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Referensi (Opsional)</label>
                        <input type="text" name="reference" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <button type="submit" class="w-full py-2 rounded-lg bg-primary text-primary-foreground text-sm font-medium">Simpan Pembayaran</button>
                </form>
            @endif
        </div>

        <div class="lg:col-span-2 bg-card border border-border rounded-xl overflow-hidden h-fit">
            <div class="p-4 border-b border-border"><h2 class="font-semibold">Riwayat Pembayaran</h2></div>
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Metode</th>
                        <th class="px-4 py-3">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($invoice->payments as $pay)
                        <tr>
                            <td class="px-4 py-3">{{ $pay->paid_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 uppercase text-xs">{{ $pay->method }}</td>
                            <td class="px-4 py-3 text-foreground/70">{{ $pay->recordedBy->name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada pembayaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.dashboard>