<x-layouts.public title="Daftar Ulang — IbraLMS">
    <div class="min-h-screen bg-background py-10 px-4">
        <div class="max-w-3xl mx-auto">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🎓</span>
                </div>
                <h1 class="text-3xl font-extrabold text-foreground">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-foreground/60 mt-2">Anda dinyatakan LULUS di {{ \App\Models\Setting::get('school_name') }}.</p>
                <p class="text-sm text-green-600 font-bold mt-1">Silakan lengkapi daftar ulang untuk mengaktifkan akun Anda.</p>
            </div>

            {{-- Info Box --}}
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-sm text-yellow-800">
                <b>Penting:</b> Isi semua data dengan benar. Data ini akan digunakan untuk rapor, ijazah, dan keperluan administrasi lainnya.
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('student.registration.store') }}" enctype="multipart/form-data" class="bg-card border border-border rounded-xl p-6 md:p-8 shadow-lg">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <h2 class="font-bold text-lg border-b border-border pb-2 mb-4">Data Pribadi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" value="{{ auth()->user()->name }}" readonly class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">NIS</label>
                        <input type="text" value="{{ auth()->user()->nis }}" readonly class="w-full px-4 py-2 rounded-lg border border-border bg-muted text-foreground/60 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No. HP / WhatsApp *</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Agama *</label>
                        <select name="religion" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('religion', $profile->religion) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tempat Lahir *</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $profile->birth_place) }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Tanggal Lahir *</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">NIK Siswa</label>
                        <input type="text" name="nik" value="{{ old('nik', $profile->nik) }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No. Kartu Keluarga</label>
                        <input type="text" name="kk_number" value="{{ old('kk_number', $profile->kk_number) }}" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Alamat Lengkap *</label>
                        <textarea name="address" rows="2" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">{{ old('address', $profile->address) }}</textarea>
                    </div>
                </div>

                <h2 class="font-bold text-lg border-b border-border pb-2 mb-4">Data Orang Tua</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Ayah *</label>
                        <input type="text" name="father_name" value="{{ old('father_name', $profile->father_name) }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Ibu *</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name', $profile->mother_name) }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">No. HP Orang Tua *</label>
                        <input type="text" name="parent_phone" value="{{ old('parent_phone', $profile->parent_phone) }}" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                    </div>
                </div>

                <h2 class="font-bold text-lg border-b border-border pb-2 mb-4">Upload Dokumen (Opsional)</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium mb-1">Kartu Keluarga</label>
                        <input type="file" name="document_kk" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Akta Kelahiran</label>
                        <input type="file" name="document_akta" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-2 rounded-lg border border-border bg-background text-sm">
                    </div>
                </div>

                <div class="flex items-start gap-3 mb-6">
                    <input type="checkbox" required id="confirm" class="mt-1">
                    <label for="confirm" class="text-sm text-foreground/80">Saya menyatakan data yang saya isi adalah <b>benar</b> dan siap mengikuti seluruh peraturan sekolah.</label>
                </div>

                <button type="submit" class="w-full py-3 bg-primary text-primary-foreground rounded-xl font-bold text-lg hover:opacity-90 shadow-lg">
                    Selesaikan Daftar Ulang
                </button>
            </form>
        </div>
    </div>
</x-layouts.public>