<x-layouts.dashboard title="Scan Absensi — IbraLMS">
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-2">Scan Absensi</h1>
        <p class="text-sm text-foreground/60 mb-6">Arahkan kamera ke QR Code yang ada di gerbang sekolah.</p>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium">{{ $errors->first() }}</div>
        @endif

        {{-- Area Kamera --}}
        <div id="reader" class="w-full bg-black rounded-xl overflow-hidden mb-4"></div>

        {{-- Form Manual (jika kamera error) --}}
        <form method="POST" action="{{ route('student.attendance-scan.process') }}" class="bg-card border border-border rounded-xl p-4" x-data="{ showManual: false }">
            @csrf
            <button type="button" @click="showManual = !showManual" class="text-sm text-primary font-medium mb-2">Kamera bermasalah? Input manual.</button>
            
            <div x-show="showManual" style="display: none;" class="flex gap-2 mt-2">
                <input type="text" name="token" placeholder="Masukkan kode QR..." required class="flex-1 px-3 py-2 rounded-lg border border-border bg-background text-sm">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm">Kirim</button>
            </div>
        </form>
    </div>

    {{-- Script Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan scanner agar tidak scan berulang-ulang
            html5QrcodeScanner.clear();
            
            // Kirim token via form otomatis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('student.attendance-scan.process') }}";
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = "{{ csrf_token() }}";
            
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = 'token';
            token.value = decodedText;
            
            form.appendChild(csrf);
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: {width: 250, height: 250} }, /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-layouts.dashboard>