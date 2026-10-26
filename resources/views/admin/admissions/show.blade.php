<x-layouts.dashboard title="Detail Pendaftar — IbraLMS">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.admissions.index') }}" class="text-foreground/60 hover:text-primary">← Kembali</a>
        <div>
            <h1 class="text-2xl font-bold">{{ $admission->full_name }}</h1>
            <p class="text-sm font-mono text-foreground/60">{{ $admission->registration_number }}</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Data Pendaftar --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Data Diri --}}
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-4 border-b border-border pb-2">Data Pendaftar</h2>
                <dl class="grid grid-cols-2 gap-y-3 text-sm">
                    <dt class="text-foreground/60">NISN</dt><dd>{{ $admission->nisn }}</dd>
                    <dt class="text-foreground/60">Jenis Kelamin</dt><dd>{{ $admission->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    <dt class="text-foreground/60">Tempat, Tanggal Lahir</dt><dd>{{ $admission->birth_place }}, {{ $admission->birth_date?->format('d F Y') }}</dd>
                    <dt class="text-foreground/60">Agama</dt><dd>{{ $admission->religion ?? '-' }}</dd>
                    <dt class="text-foreground/60">Asal Sekolah</dt><dd>{{ $admission->previous_school }}</dd>
                    <dt class="text-foreground/60">Jurusan Pilihan</dt><dd>{{ $admission->chosen_major }}</dd>
                    <dt class="text-foreground/60">No. HP</dt><dd>{{ $admission->phone }}</dd>
                    <dt class="text-foreground/60">Alamat</dt><dd class="col-span-1">{{ $admission->address }}</dd>
                </dl>
            </div>

            {{-- Dokumen --}}
            <div class="bg-card border border-border rounded-xl p-6">
                <div class="flex justify-between items-center mb-4 border-b border-border pb-2">
                    <h2 class="font-bold text-lg">Dokumen Pendukung</h2>
                    @if($admission->is_documents_verified)
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded">✓ Terverifikasi</span>
                    @else
                        <form method="POST" action="{{ route('admin.admissions.verify-documents', $admission->id) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-primary text-primary-foreground text-xs font-bold rounded hover:opacity-90">✓ Verifikasi Dokumen</button>
                        </form>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @php
                        $docs = [
                            'document_kk' => 'Kartu Keluarga',
                            'document_akta' => 'Akta Kelahiran',
                            'document_ijazah' => 'Ijazah',
                            'document_photo' => 'Foto',
                            'payment_proof' => 'Bukti Pembayaran',
                        ];
                    @endphp
                    @foreach($docs as $field => $label)
                        @if($admission->$field)
                            <a href="{{ Storage::url($admission->$field) }}" target="_blank" class="block p-3 border border-border rounded-lg hover:border-primary transition text-center">
                                <div class="text-2xl mb-1">📄</div>
                                <p class="text-xs font-bold text-foreground/80">{{ $label }}</p>
                            </a>
                        @else
                            <div class="block p-3 border border-border rounded-lg text-center bg-muted/30">
                                <div class="text-2xl mb-1 opacity-30">📄</div>
                                <p class="text-xs text-foreground/40">{{ $label }} (kosong)</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Aksi --}}
        <div class="space-y-4">
            {{-- Input Nilai --}}
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-4 border-b border-border pb-2">Input Nilai Seleksi</h2>
                <form method="POST" action="{{ route('admin.admissions.score', $admission->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm mb-1">Nilai Rata-rata Rapor</label>
                        <input type="number" step="0.01" name="report_score" value="{{ $admission->report_score }}" min="0" max="100" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Nilai Tes Masuk</label>
                        <input type="number" name="test_score" value="{{ $admission->test_score }}" min="0" max="100" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div class="p-3 bg-primary/10 rounded-lg">
                        <p class="text-xs text-foreground/60 mb-1">Nilai Akhir (60% Rapor + 40% Tes)</p>
                        <p class="text-2xl font-bold text-primary">{{ $admission->final_score ? number_format($admission->final_score, 2) : '-' }}</p>
                    </div>
                    <button type="submit" class="w-full py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold">Simpan Nilai</button>
                </form>
            </div>

            {{-- Status Manual --}}
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-4 border-b border-border pb-2">Status Pendaftaran</h2>
                <form method="POST" action="{{ route('admin.admissions.verify', $admission->id) }}" class="space-y-3">
                    @csrf
                    <select name="status" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        <option value="pending" {{ $admission->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="accepted" {{ $admission->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ $admission->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <textarea name="notes" rows="2" placeholder="Catatan verifikasi..." class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">{{ $admission->notes }}</textarea>
                    <button type="submit" class="w-full py-2 bg-foreground text-background rounded-lg text-sm font-bold">Update Status</button>
                </form>
            </div>

            {{-- Konversi ke Siswa --}}
            @if($admission->status == 'accepted' && !$admission->converted_user_id)
                <div class="bg-primary/5 border-2 border-primary/20 rounded-xl p-6">
                    <h2 class="font-bold text-lg mb-2 text-primary">Konversi ke Siswa</h2>
                    <p class="text-xs text-foreground/60 mb-3">Siswa akan dibuatkan akun dengan password default.</p>
                    <form method="POST" action="{{ route('admin.admissions.convert', $admission->id) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm mb-1">NIS Baru</label>
                            <input type="text" name="nis" required placeholder="20260001" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Kelas</label>
                            <select name="classroom_id" required class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                                <option value="">-- Pilih --</option>
                                @foreach(\App\Models\Classroom::where('academic_year', \App\Helpers\AcademicYear::current())->get() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2 bg-primary text-primary-foreground rounded-lg text-sm font-bold" onclick="return confirm('Konversi pendaftar menjadi siswa?')">🎓 Konversi Sekarang</button>
                    </form>
                </div>
            @elseif($admission->converted_user_id)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                    <p class="text-green-700 font-bold">✓ Sudah dikonversi menjadi siswa</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.dashboard>