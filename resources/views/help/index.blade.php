<x-layouts.dashboard title="Pusat Bantuan — IbraLMS">
    <div class="max-w-4xl mx-auto" x-data="{ tab: 'admin' }">
        <h1 class="text-2xl font-bold mb-2">Pusat Bantuan IbraLMS</h1>
        <p class="text-foreground/60 mb-6">Panduan singkat penggunaan sistem untuk Admin, Guru, dan Siswa.</p>

        {{-- Tab Navigation --}}
        <div class="flex border-b border-border mb-6">
            <button @click="tab = 'admin'" :class="{ 'border-primary text-primary': tab === 'admin', 'border-transparent text-foreground/60': tab !== 'admin' }" class="px-6 py-3 font-medium border-b-2 transition">
                Panduan Admin / TU
            </button>
            <button @click="tab = 'guru'" :class="{ 'border-primary text-primary': tab === 'guru', 'border-transparent text-foreground/60': tab !== 'guru' }" class="px-6 py-3 font-medium border-b-2 transition">
                Panduan Guru
            </button>
            <button @click="tab = 'siswa'" :class="{ 'border-primary text-primary': tab === 'siswa', 'border-transparent text-foreground/60': tab !== 'siswa' }" class="px-6 py-3 font-medium border-b-2 transition">
                Panduan Siswa
            </button>
        </div>

        {{-- Konten Admin --}}
        <div x-show="tab === 'admin'" class="space-y-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">1. Mengelola Pengguna</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Manajemen Pengguna &rarr; Tambah Pengguna</b> untuk membuat akun baru (Guru/Siswa).</li>
                    <li>Untuk mendaftarkan banyak siswa sekaligus, gunakan menu <b>Import Bulk</b> dengan format file CSV yang disediakan.</li>
                    <li>Gunakan menu <b>Penempatan Kelas</b> untuk memasukkan siswa ke dalam kelas (Rombel).</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">2. Pengaturan Akademik</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Isi data <b>Mata Pelajaran</b> terlebih dahulu, lalu buat <b>Kelas / Rombel</b>.</li>
                    <li>Buka menu <b>Jadwal Pelajaran</b> untuk mengatur KBM (Guru, Mapel, Kelas, dan Jam).</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">3. Menerbitkan Surat & Rapor</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Gunakan menu <b>Surat-Menyurat</b>. Buat template terlebih dahulu, lalu terbitkan untuk siswa.</li>
                    <li>Untuk mencetak rapor siswa, buka menu <b>Rekap Nilai</b> dan klik tombol Cetak.</li>
                </ul>
            </div>
        </div>

        {{-- Konten Guru --}}
        <div x-show="tab === 'guru'" style="display: none;" class="space-y-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">1. Melakukan Presensi</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Presensi Mengajar</b>.</li>
                    <li>Pilih jadwal kelas yang ingin diisi hari ini, lalu klik <b>Buka Sesi Presensi</b>.</li>
                    <li>Ubah status siswa (Hadir/Izin/Sakit/Alpa) dan klik Simpan.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">2. Memberi Tugas & Nilai</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Manajemen Tugas</b> dan klik Buat Tugas Baru.</li>
                    <li>Setelah siswa mengumpulkan, buka detail tugas dan klik <b>Beri Nilai</b> pada kolom kanan.</li>
                    <li>Nilai akan otomatis masuk ke Rapor Siswa.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">3. Jurnal & Ekstrakurikuler</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Setiap selesai mengajar, biasakan mengisi <b>Jurnal Mengajar</b>.</li>
                    <li>Jika Anda menjadi pembina ekskul, buka menu <b>Ekskul Saya</b> untuk memberi nilai ekskul.</li>
                </ul>
            </div>
        </div>

        {{-- Konten Siswa --}}
        <div x-show="tab === 'siswa'" style="display: none;" class="space-y-4">
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">1. Absensi Harian (QR Code)</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Scan Kehadiran</b>.</li>
                    <li>Izinkan kamera HP Anda, lalu arahkan ke layar QR Code yang ada di gerbang sekolah.</li>
                    <li>Pastikan Anda sudah scan saat masuk dan saat pulang.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">2. Tugas & Rapor</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Tugas Saya</b> untuk melihat daftar tugas. Klik Kumpulkan Tugas sebelum tenggat waktu.</li>
                    <li>Nilai tugas dan rata-rata rapor Anda bisa dilihat di menu <b>Rapor Saya</b>.</li>
                </ul>
            </div>
            <div class="bg-card border border-border rounded-xl p-6">
                <h2 class="font-bold text-lg mb-3">3. Ekstrakurikuler</h2>
                <ul class="list-disc pl-5 space-y-2 text-sm text-foreground/80">
                    <li>Buka menu <b>Ekstrakurikuler</b> untuk mendaftar ekskul yang tersedia.</li>
                    <li>Jika pendaftaran ditutup, hubungi pembina atau admin.</li>
                </ul>
            </div>
        </div>
    </div>
</x-layouts.dashboard>