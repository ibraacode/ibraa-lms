<x-layouts.dashboard title="Penilaian Karakter — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Penilaian Karakter (Sikap)</h1>

    <div class="bg-card border border-border rounded-xl p-6 max-w-xl">
        <form method="GET" action="" x-data="{ classroomId: '' }" @submit.prevent="window.location.href = '/teacher/character/' + classroomId + '?semester=' + $refs.semester.value">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Pilih Kelas</label>
                    <select x-model="classroomId" required class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Semester</label>
                    <select x-ref="semester" class="w-full px-4 py-2 rounded-lg border border-border bg-background">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-primary-foreground font-medium">Tampilkan Siswa</button>
            </div>
        </form>
    </div>
</x-layouts.dashboard>