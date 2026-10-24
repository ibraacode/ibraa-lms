<x-layouts.dashboard title="PKL Saya — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Presensi & Jurnal PKL</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errors->first() }}</div>
    @endif

    {{-- STATUS ABSEN HARI INI --}}
    <div class="bg-card border border-border rounded-xl p-6 mb-6">
        <h2 class="font-semibold text-lg mb-4">Status Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-muted/50 p-4 rounded-lg">
                <p class="text-xs text-foreground/60">Jam Masuk</p>
                <p class="text-2xl font-bold {{ $todayLog?->check_in_time ? 'text-green-600' : 'text-foreground/30' }}">
                    {{ $todayLog?->check_in_time ?? '--:--' }}
                </p>
            </div>
            <div class="bg-muted/50 p-4 rounded-lg">
                <p class="text-xs text-foreground/60">Jam Pulang</p>
                <p class="text-2xl font-bold {{ $todayLog?->check_out_time ? 'text-red-600' : 'text-foreground/30' }}">
                    {{ $todayLog?->check_out_time ?? '--:--' }}
                </p>
            </div>
        </div>
    </div>

    {{-- FORM ABSEN DENGAN KAMERA --}}
    <div class="bg-card border border-border rounded-xl p-6 mb-6" x-data="attendanceApp()">
        <h2 class="font-semibold text-lg mb-4">Absen PKL (Kamera & Lokasi)</h2>
        
        <div class="space-y-4">
            <video x-ref="video" autoplay class="w-full max-w-sm rounded-lg bg-black"></video>
            <canvas x-ref="canvas" class="hidden"></canvas>
            
            <div class="flex gap-2">
                <button type="button" @click="takePhoto" class="px-4 py-2 bg-primary text-white rounded-lg">Ambil Foto</button>
                <button type="button" @click="getLocation" class="px-4 py-2 bg-muted rounded-lg">Dapatkan Lokasi</button>
            </div>

            <p x-text="locationStatus" class="text-sm text-foreground/60"></p>

            {{-- Form Submit --}}
            <form x-ref="form" method="POST" :action="actionUrl">
                @csrf
                <input type="hidden" name="photo" x-model="photoData">
                <input type="hidden" name="lat" x-model="lat">
                <input type="hidden" name="long" x-model="long">
                <textarea name="activity" placeholder="Kegiatan hari ini..." class="w-full px-3 py-2 border rounded-lg mb-2"></textarea>
                <button type="submit" class="w-full py-2 bg-green-600 text-white rounded-lg">Kirim Presensi</button>
            </form>
        </div>
    </div>

    <script>
        function attendanceApp() {
            return {
                photoData: null,
                lat: null,
                long: null,
                locationStatus: 'Lokasi belum diambil.',
                actionUrl: '{{ route('student.internship.checkin') }}', // Default masuk
                
                init() {
                    navigator.mediaDevices.getUserMedia({ video: true })
                        .then(stream => {
                            this.$refs.video.srcObject = stream;
                        })
                        .catch(err => alert('Kamera tidak dapat diakses: ' + err));
                },
                
                takePhoto() {
                    const video = this.$refs.video;
                    const canvas = this.$refs.canvas;
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);
                    this.photoData = canvas.toDataURL('image/png');
                    alert('Foto berhasil diambil!');
                },
                
                getLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(pos => {
                            this.lat = pos.coords.latitude;
                            this.long = pos.coords.longitude;
                            this.locationStatus = 'Lokasi didapat: ' + this.lat + ', ' + this.long;
                        }, err => {
                            this.locationStatus = 'Gagal mendapatkan lokasi.';
                        });
                    } else {
                        this.locationStatus = 'Geolocation tidak didukung.';
                    }
                }
            }
        }
    </script>
</x-layouts.dashboard>