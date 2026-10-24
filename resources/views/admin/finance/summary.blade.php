<x-layouts.dashboard title="Rekap Keuangan — IbraLMS">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold">Rekap Keuangan</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.finance.fee-types') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Jenis Tagihan</a>
            <a href="{{ route('admin.finance.invoices') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Tagihan Siswa</a>
            <a href="{{ route('admin.finance.other-incomes') }}" class="px-4 py-2 rounded-lg bg-card border border-border text-sm font-medium hover:bg-muted">Pemasukan Lain</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Kartu 1 -->
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-xs text-foreground/60 mb-1">Total Tagihan Siswa</p>
            <p class="text-2xl font-bold">Rp {{ number_format($stats['total_invoiced'], 0, ',', '.') }}</p>
        </div>
        
        <!-- Kartu 2 -->
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-xs text-foreground/60 mb-1">Diterima dari Siswa</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_student_paid'] ?? 0, 0, ',', '.') }}</p>
        </div>
        
        <!-- Kartu 3 -->
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-xs text-foreground/60 mb-1">Pemasukan Lain (BOS/Hibah)</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($stats['total_other_income'] ?? 0, 0, ',', '.') }}</p>
        </div>
        
        <!-- Kartu 4 -->
        <div class="bg-primary/5 border border-primary/20 rounded-xl p-5">
            <p class="text-xs text-primary mb-1 font-bold">TOTAL UANG MASUK</p>
            <p class="text-2xl font-bold text-primary">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kartu Tambahan -->
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-xs text-foreground/60 mb-1">Sisa Tagihan Belum Terbayar</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($stats['total_outstanding'] ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-card border border-border rounded-xl p-5">
            <p class="text-xs text-foreground/60 mb-1">Jumlah Siswa Bebas Biaya (Gratis)</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['total_exempt'] ?? 0 }} Siswa</p>
        </div>
    </div>
</x-layouts.dashboard>