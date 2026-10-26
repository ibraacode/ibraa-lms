<x-layouts.dashboard title="Pusat Bantuan — IbraLMS">
    <div class="max-w-4xl mx-auto" x-data="{ tab: 'admin' }">
        
        {{-- Header --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="{{ asset('icons/info.svg') }}" alt="icon" class="w-8 h-8 object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-foreground tracking-tight">Pusat Bantuan IbraLMS</h1>
            <p class="text-foreground/60 mt-2">Panduan lengkap penggunaan sistem untuk Admin, Guru, dan Siswa.</p>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex justify-center border-b border-border mb-8">
            <button @click="tab = 'admin'" :class="tab === 'admin' ? 'border-primary text-primary' : 'border-transparent text-foreground/60 hover:text-foreground'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Panduan Admin
            </button>
            <button @click="tab = 'guru'" :class="tab === 'guru' ? 'border-primary text-primary' : 'border-transparent text-foreground/60 hover:text-foreground'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Panduan Guru
            </button>
            <button @click="tab = 'siswa'" :class="tab === 'siswa' ? 'border-primary text-primary' : 'border-transparent text-foreground/60 hover:text-foreground'" class="px-6 py-3 font-bold text-sm border-b-2 transition-colors">
                Panduan Siswa
            </button>
        </div>

        {{-- ================= KONTEN ADMIN ================= --}}
        <div x-show="tab === 'admin'" class="space-y-4">
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">1</span>
                    Manajemen Pengguna & Akademik
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Buka menu <b>Manajemen Pengguna</b> untuk menambah akun Guru, Siswa, atau Staf. Email siswa/guru dibuat otomatis berdasarkan NIS/NIP.</li>
                    <li>Gunakan fitur <b>Import Bulk</b> untuk mendaftarkan ratusan siswa sekaligus menggunakan file CSV.</li>
                    <li>Sebelum membuat jadwal, pastikan Anda sudah membuat <b>Mata Pelajaran</b> dan <b>Kelas / Rombel</b> di menu Akademik.</li>
                    <li>Gunakan menu <b>Penempatan Kelas</b> untuk memasukkan siswa ke kelas yang sesuai di tahun ajaran aktif.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">2</span>
                    Tahun Ajaran & Kelulusan
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Selalu perhatikan <b>Dropdown Tahun Ajaran di Topbar</b>. Pastikan Anda berada di tahun yang benar sebelum menginput data.</li>
                    <li>Di akhir tahun ajaran, buka menu <b>Tahun Ajaran</b> untuk memproses <b>Kenaikan Kelas Massal</b> (X ke XI, XI ke XII).</li>
                    <li>Klik tombol <b>Luluskan Kelas XII</b> agar siswa tersebut berstatus lulus dan tidak bisa login kembali.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">3</span>
                    Keuangan & Sarana
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Gunakan menu <b>Keuangan</b> untuk membuat tagihan SPP, mencatat pembayaran, dan mencatat pemasukan lain (BOS/Hibah).</li>
                    <li>Jangan lupa membuat <b>Template Surat</b> terlebih dahulu sebelum menerbitkan surat untuk siswa.</li>
                    <li>Di menu <b>Pengaturan Sistem</b>, Anda bisa mengubah logo sekolah, nama sekolah, dan menyalakan/mematikan modul tertentu.</li>
                </ul>
            </div>
        </div>

        {{-- ================= KONTEN GURU ================= --}}
        <div x-show="tab === 'guru'" style="display: none;" class="space-y-4">
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">1</span>
                    Presensi & Jurnal
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Buka menu <b>Presensi Mengajar</b> untuk membuka sesi absensi kelas hari ini.</li>
                    <li>Pastikan Anda mengisi <b>Jurnal Mengajar</b> setiap selesai KBM agar tercatat materi yang diajarkan.</li>
                    <li>Guru yang menjadi <b>Wali Kelas</b> akan melihat kelasnya di menu <b>Nilai Karakter</b> untuk mengisi sikap Spiritual dan Sosial.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">2</span>
                    Tugas & Nilai
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Buka menu <b>Manajemen Tugas</b> untuk membuat tugas baru. Siswa akan mengumpulkan jawaban secara online.</li>
                    <li>Setelah siswa mengumpulkan, klik <b>Beri Nilai</b> pada detail tugas.</li>
                    <li>Buka menu <b>Rekap Nilai</b> untuk melihat matriks nilai seluruh siswa di kelas yang Anda ampu.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">3</span>
                    Bimbingan PKL (SMK)
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Jika Anda menjadi pembimbing PKL, buka menu <b>Bimbingan PKL</b>.</li>
                    <li>Anda bisa melihat jurnal harian siswa, foto selfie presensi PKL, dan memberikan nilai akhir PKL.</li>
                    <li>Cetak laporan PKL dengan mengklik tombol <b>Cetak Laporan PKL</b>.</li>
                </ul>
            </div>
        </div>

        {{-- ================= KONTEN SISWA ================= --}}
        <div x-show="tab === 'siswa'" style="display: none;" class="space-y-4">
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">1</span>
                    Kehadiran Harian (QR)
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Jika sekolah Anda mengaktifkan Absensi QR, buka menu <b>Scan Kehadiran</b>.</li>
                    <li>Izinkan akses kamera HP Anda, lalu arahkan ke layar QR Code yang disediakan sekolah (di gerbang atau layar kelas).</li>
                    <li>Lakukan scan saat masuk (pagi) dan saat pulang.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">2</span>
                    Tugas & Rapor
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Buka menu <b>Tugas Saya</b> untuk melihat tugas yang diberikan guru. Klik "Kumpulkan Tugas" sebelum tenggat waktu.</li>
                    <li>Buka menu <b>Rapor Saya</b> untuk melihat nilai rata-rata per mata pelajaran dan nilai sikap (karakter).</li>
                    <li>Di halaman Rapor, klik tombol <b>🖨️ Cetak Rapor</b> jika Anda ingin menyimpannya sebagai PDF.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-2xl p-6">
                <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm font-bold">3</span>
                    PKL & Ekstrakurikuler
                </h2>
                <ul class="space-y-3 text-sm text-foreground/80 list-disc pl-12">
                    <li>Jika Anda siswa SMK, buka menu <b>PKL Saya</b> untuk melakukan presensi (selfie + GPS) dan mengisi jurnal harian.</li>
                    <li>Buka menu <b>Ekstrakurikuler</b> untuk mendaftar ekskul yang masih dibuka.</li>
                </ul>
            </div>
        </div>

    </div>
</x-layouts.dashboard>