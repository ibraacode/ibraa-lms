<x-layouts.public title="Pendaftaran Siswa Baru">
    <div class="min-h-screen bg-background py-10 px-4">
        <div class="max-w-3xl mx-auto" x-data="{ step: 1, totalStep: 4 }">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-primary">{{ \App\Models\Setting::get('school_name', 'IbraLMS') }}</h1>
                <p class="text-lg font-semibold text-foreground/80 mt-2">Formulir PPDB Online</p>
                <p class="text-sm text-foreground/60">Tahun Ajaran {{ \App\Models\Setting::get('active_academic_year') }}</p>
            </div>

                      <div class="text-center mt-8">
                <a href="{{ route('admission.announcement') }}" class="text-sm text-primary hover:underline">
                    📢 Sudah daftar? Cek Pengumuman Kelulusan di sini
                </a>
                      </div>

            {{-- Progress Bar --}}
            <div class="mb-8">
                <div class="flex justify-between text-xs font-bold text-foreground/60 mb-2">
                    <span :class="step >= 1 ? 'text-primary' : ''">1. Data Diri</span>
                    <span :class="step >= 2 ? 'text-primary' : ''">2. Orang Tua</span>
                    <span :class="step >= 3 ? 'text-primary' : ''">3. Dokumen</span>
                    <span :class="step >= 4 ? 'text-primary' : ''">4. Konfirmasi</span>
                </div>
                <div class="h-2 w-full bg-muted rounded-full overflow-hidden">
                    <div class="h-full bg-primary transition-all duration-300" :style="`width: ${(step / totalStep) * 100}%`"></div>
                </div>
            </div>

            {{-- Pesan Error --}}
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admission.store') }}" enctype="multipart/form-data" class="bg-card border border-border rounded-xl p-6 md:p-8 shadow-sm">
                @csrf

                {{-- ================= STEP 1: DATA DIRI ================= --}}
                <div x-show="step === 1" x-transition.opacity>
                    <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mb-4">Data Diri Calon Siswa</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">Nama Lengkap *</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">NISN *</label>
                            <input type="text" name="nisn" value="{{ old('nisn') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Jenis Kelamin *</label>
                            <select name="gender" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                                <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Tempat Lahir *</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Tanggal Lahir *</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Agama</label>
                            <select name="religion" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                                <option value="">-- Pilih --</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('religion') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-1">No. HP Siswa/WA *</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm mb-1">Alamat Lengkap *</label>
                            <textarea name="address" rows="2" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="step = 2" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg font-bold">Selanjutnya &rarr;</button>
                    </div>
                </div>

                {{-- ================= STEP 2: DATA ORANG TUA ================= --}}
                <div x-show="step === 2" style="display: none;" x-transition.opacity>
                    <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mb-4">Data Orang Tua / Wali</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2"><h3 class="font-bold text-sm text-primary">Data Ayah</h3></div>
                        <div>
                            <label class="block text-sm mb-1">Nama Ayah *</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                                                <div>
                            <label class="block text-sm mb-1">Pekerjaan Ayah</label>
                            <select name="father_occupation" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                                <option value="">-- Pilih --</option>
                                @foreach($occupations as $o)
                                    <option value="{{ $o->name }}" {{ old('father_occupation') == $o->name ? 'selected' : '' }}>{{ $o->name }}</option>
                                @endforeach
                            </select>
                                                </div>

                        <div class="md:col-span-2 mt-2"><h3 class="font-bold text-sm text-primary">Data Ibu</h3></div>
                        <div>
                            <label class="block text-sm mb-1">Nama Ibu *</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                                              <div>
                            <label class="block text-sm mb-1">Pekerjaan Ibu</label>
                            <select name="mother_occupation" class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                                <option value="">-- Pilih --</option>
                                @foreach($occupations as $o)
                                    <option value="{{ $o->name }}" {{ old('mother_occupation') == $o->name ? 'selected' : '' }}>{{ $o->name }}</option>
                                @endforeach
                            </select>
                                              </div>
                        <div>
                            <label class="block text-sm mb-1">No. HP Orang Tua *</label>
                            <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Asal Sekolah *</label>
                            <input type="text" name="previous_school" value="{{ old('previous_school') }}" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                        </div>
                                            <div class="md:col-span-2">
                        <label class="block text-sm mb-1">Jurusan Pilihan *</label>
                        <select name="chosen_major" required class="w-full px-3 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->code }}" {{ old('chosen_major') == $m->code ? 'selected' : '' }}>{{ $m->name }} ({{ $m->code }})</option>
                            @endforeach
                        </select>
                                            </div>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="step = 1" class="px-6 py-2 bg-muted text-foreground rounded-lg font-bold">&larr; Kembali</button>
                        <button type="button" @click="step = 3" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg font-bold">Selanjutnya &rarr;</button>
                    </div>
                </div>

                {{-- ================= STEP 3: DOKUMEN ================= --}}
                <div x-show="step === 3" style="display: none;" x-transition.opacity>
                    <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mb-4">Upload Dokumen</h2>
                    <p class="text-xs text-foreground/60 mb-4">Format yang diterima: JPG, PNG, PDF (Maks 2MB).</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm mb-1">Kartu Keluarga (KK) *</label>
                            <input type="file" name="document_kk" required accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Akta Kelahiran *</label>
                            <input type="file" name="document_akta" required accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Ijazah / SKL (Opsional)</label>
                            <input type="file" name="document_ijazah" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Foto Terbaru *</label>
                            <input type="file" name="document_photo" required accept=".jpg,.jpeg,.png" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="step = 2" class="px-6 py-2 bg-muted text-foreground rounded-lg font-bold">&larr; Kembali</button>
                        <button type="button" @click="step = 4" class="px-6 py-2 bg-primary text-primary-foreground rounded-lg font-bold">Selanjutnya &rarr;</button>
                    </div>
                </div>

                {{-- ================= STEP 4: KONFIRMASI ================= --}}
                <div x-show="step === 4" style="display: none;" x-transition.opacity>
                    <h2 class="font-bold text-lg text-primary border-b border-border pb-2 mb-4">Konfirmasi & Pembayaran</h2>
                    
                    {{-- Ringkasan Data --}}
                    <div class="bg-muted/30 rounded-lg p-4 mb-4 text-sm">
                        <p><b>Perhatian:</b> Pastikan semua data dan dokumen sudah benar sebelum submit.</p>
                        <ul class="list-disc pl-5 mt-2 text-foreground/70">
                            <li>Data yang sudah dikirim tidak dapat diubah online.</li>
                            <li>Nomor Pendaftaran akan di-generate otomatis.</li>
                            <li>Silakan simpan nomor pendaftaran setelah submit.</li>
                        </ul>
                    </div>

                    @if($fee > 0)
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4">
                            <h3 class="font-bold text-blue-700 text-sm mb-2">Biaya Pendaftaran: Rp {{ number_format($fee, 0, ',', '.') }}</h3>
                            <p class="text-xs text-blue-600 mb-3">Silakan transfer ke rekening sekolah, lalu upload bukti transfer.</p>
                            <label class="block text-sm mb-1 font-bold text-blue-700">Upload Bukti Transfer *</label>
                            <input type="file" name="payment_proof" required accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-blue-300 bg-white text-sm">
                        </div>
                    @endif

                    <div class="flex items-start gap-3 mb-4">
                        <input type="checkbox" required id="terms" class="mt-1">
                        <label for="terms" class="text-sm text-foreground/80">Saya menyatakan bahwa data yang saya isi adalah <b>benar dan dapat dipertanggungjawabkan</b>.</label>
                    </div>

                    <div class="mt-6 flex justify-between">
                        <button type="button" @click="step = 3" class="px-6 py-2 bg-muted text-foreground rounded-lg font-bold">&larr; Kembali</button>
                        <button type="submit" class="px-8 py-2 bg-primary text-primary-foreground rounded-lg font-bold shadow-md hover:opacity-90">Daftar Sekarang</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-layouts.public>