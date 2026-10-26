<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar semua permission
        $permissions = [
            // Manajemen Pengguna
            'users.view', 'users.create', 'users.update', 'users.delete', 'users.import',
            // Profil Sendiri
            'profile.view-own', 'profile.update-own',
            // Role & System
            'roles.view', 'roles.assign',
            'system.settings', 'system.audit-log',
            // Akademik
            'academic.subjects.manage',
            'academic.classrooms.manage',
            'academic.schedules.manage',
            // Penilaian
            'grades.view', 'grades.edit',
            // Absensi (guru & admin)
            'attendance.view', 'attendance.manage',
            // Pengumuman
            'announcements.create',
            // Menu Guru
            'teacher.attendance',
            'teacher.assignments',
            'teacher.journals',
            'teacher.gradebook',
            // Menu Siswa
            'student.attendance',
            'student.assignments',
            'student.gradebook',
            // Keuangan
            'finance.view', 'finance.manage',
                      // Ekstrakurikuler
            'extracurricular.view',
            'extracurricular.manage',   // untuk admin
            'extracurricular.assess',   // untuk guru pembina (beri nilai)
            'extracurricular.join',     // untuk siswa
                      // BK
            'counseling.view',
            'counseling.manage',
                      // Surat-Menyurat
            'letters.view', 'letters.manage',
                      // PPDB
            'admissions.view', 'admissions.manage',
                      // Nilai Karakter
            'character.view', 'character.grade',
                      // PKL
            'internship.manage', 'internship.assess', 'internship.log',
                      // Poin Pelanggaran
            'violations.view',
            'violations.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ============== ROLE ==============

        // Super Admin — semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        // TU — admin sekolah
        $tu = Role::firstOrCreate(['name' => 'tu']);
        $tu->syncPermissions([
            'users.view', 'users.create', 'users.update', 'users.import',
            'finance.view', 'finance.manage',
            'attendance.manage', // penting agar menu Absensi Harian muncul
            'academic.subjects.manage',
            'academic.classrooms.manage',
            'academic.schedules.manage',
            'announcements.create',
            'profile.view-own', 'profile.update-own',
            'extracurricular.view', 'extracurricular.manage',
            'letters.view', 'letters.manage',
            'admissions.view', 'admissions.manage',
            'character.view', 'character.grade',
            'internship.manage',
            'violations.view', 'violations.manage',
        ]);

        // Guru
        $guru = Role::firstOrCreate(['name' => 'guru']);
        $guru->syncPermissions([
            'teacher.attendance',
            'teacher.assignments',
            'teacher.journals',
            'teacher.gradebook',
            'announcements.create',
            'profile.view-own', 'profile.update-own',
            'extracurricular.view', 'extracurricular.assess',
            'character.view', 'character.grade',
            'internship.assess',
            'violations.view', 'violations.manage',
        ]);

        // Siswa
        $siswa = Role::firstOrCreate(['name' => 'siswa']);
        $siswa->syncPermissions([
            'student.attendance',
            'student.assignments',
            'student.gradebook',
            'profile.view-own', 'profile.update-own',
            'extracurricular.view', 'extracurricular.join',
            'internship.log',
        ]);

              $bk = Role::firstOrCreate(['name' => 'bk']);
        $bk->syncPermissions([
            'counseling.view', 'counseling.manage',
            'profile.view-own', 'profile.update-own',
            'violations.view', 'violations.manage',
        ]);

        // Role tambahan (tanpa permission default)
        Role::firstOrCreate(['name' => 'bk']);
        Role::firstOrCreate(['name' => 'cs']);
        Role::firstOrCreate(['name' => 'it']);
    }
}