<x-layouts.dashboard title="Penempatan Kelas — IbraLMS">
    <h1 class="text-2xl font-bold mb-6">Penempatan Kelas Siswa</h1>

    @if (session('success'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm font-medium">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm font-medium">{{ $errors->first() }}</div>
    @endif

    <div class="bg-card border border-border rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-muted/50 border-b border-border">
                    <tr>
                        <th class="px-4 py-3">Nama Siswa</th>
                        <th class="px-4 py-3">NIS</th>
                        <th class="px-4 py-3">Kelas Saat Ini</th>
                        <th class="px-4 py-3">Pindahkan Ke</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($students as $student)
                        @php
                            // Ambil data dari $profiles array, bukan relasi $student->studentProfile
                            $profile = $profiles[$student->id] ?? null;
                            $currentClassId = $profile ? $profile->classroom_id : null;
                            
                            // Cari nama kelasnya
                            $currentClass = $currentClassId ? $classrooms->where('id', $currentClassId)->first() : null;
                        @endphp
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $student->name }}</td>
                            <td class="px-4 py-3">{{ $student->nis }}</td>
                            <td class="px-4 py-3">
                                @if($currentClass)
                                    <span class="px-2 py-1 rounded-md bg-primary text-white text-xs font-bold">
                                        {{ $currentClass->name }}
                                    </span>
                                @else
                                    <span class="text-red-500 text-xs font-medium bg-red-50 px-2 py-1 rounded-md">
                                        Belum ada kelas
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.students.update-class', $student->id) }}" class="flex items-center gap-2">
                                    @csrf
                                    <select name="classroom_id" class="px-3 py-1.5 rounded-lg border border-border bg-background text-sm">
                                        <option value="">-- Lepas Kelas --</option>
                                        @foreach($classrooms as $c)
                                            <option value="{{ $c->id }}" {{ $currentClassId == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-primary text-primary-foreground rounded-md text-xs font-medium hover:opacity-90">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-foreground/50">Belum ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-border">{{ $students->links() }}</div>
    </div>
</x-layouts.dashboard>