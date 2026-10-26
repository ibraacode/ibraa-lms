<?php

return [
    // ================= KATEGORI: UTAMA =================
    [
        'label' => 'UTAMA',
        'route' => null, // Header Pemisah
    ],
    [
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'icon' => 'dashboard',
    ],
    [
        'label' => 'Profil Saya',
        'route' => 'profile.edit',
        'icon' => 'user',
        'permission' => 'profile.view-own',
    ],
    [
        'label' => 'Pengumuman',
        'route' => 'admin.announcements.index',
        'icon' => 'bell',
        'permission' => 'announcements.create',
    ],
    [
        'label' => 'Pusat Bantuan',
        'route' => 'help.index',
        'icon' => 'info',
    ],

    // ================= KATEGORI: ADMINISTRASI & AKADEMIK =================
    [
        'label' => 'ADMINISTRASI & AKADEMIK',
        'route' => null,
        'permission' => 'users.view', // Hanya muncul jika punya akses admin
    ],
    [
        'label' => 'Manajemen Pengguna',
        'icon' => 'user-card',
        'permission' => 'users.view',
        'children' => [
            [
                'label' => 'Daftar Pengguna',
                'route' => 'admin.users.index',
                'icon' => 'id-card',
                'permission' => 'users.view',
            ],
            [
                'label' => 'Tambah Pengguna',
                'route' => 'admin.users.create',
                'icon' => 'add-circle',
                'permission' => 'users.create',
            ],
            [
                'label' => 'Import Bulk',
                'route' => 'admin.users.import',
                'icon' => 'upload-file',
                'permission' => 'users.import',
            ],
        ],
    ],
    [
        'label' => 'Akademik',
        'icon' => 'book',
        'permission' => 'academic.subjects.manage',
        'children' => [
            [
                'label' => 'Mata Pelajaran',
                'route' => 'admin.academic.subjects',
                'icon' => 'book-2',
                'permission' => 'academic.subjects.manage',
            ],
            [
                'label' => 'Kelas / Rombel',
                'route' => 'admin.academic.classrooms',
                'icon' => 'building',
                'permission' => 'academic.classrooms.manage',
            ],
            [
                'label' => 'Jadwal Pelajaran',
                'route' => 'admin.academic.schedules',
                'icon' => 'calendar',
                'permission' => 'academic.schedules.manage',
            ],
            [
                'label' => 'Penempatan Kelas',
                'route' => 'admin.students.assign',
                'icon' => 'certificate',
                'permission' => 'academic.classrooms.manage',
            ],
            [
                'label' => 'Presensi Manual',
                'route' => 'admin.attendance.manual.index',
                'icon' => 'clipboard',
                'permission' => 'attendance.manage',
            ],
            [
                'label' => 'Laporan Kehadiran',
                'route' => 'admin.attendance.report.index',
                'icon' => 'chart-pie',
                'permission' => 'attendance.manage',
            ],
              [
                'label' => 'Master Data (Jurusan/Pekerjaan)',
                'route' => 'admin.master-data.index',
                'icon' => 'list',
                'permission' => 'academic.subjects.manage',
            ],
        ],
    ],
    [
        'label' => 'Tahun Ajaran',
        'route' => 'admin.academic-year.index',
        'icon' => 'calendar',
        'permission' => 'academic.subjects.manage',
    ],
    [
        'label' => 'Ekstrakurikuler',
        'route' => 'admin.extracurriculars.index',
        'icon' => 'award',
        'permission' => 'extracurricular.manage',
    ],
    [
        'label' => 'Surat-Menyurat',
        'route' => 'admin.letters.index',
        'icon' => 'message-2',
        'permission' => 'letters.manage',
    ],
    [
        'label' => 'PPDB Online',
        'route' => 'admin.admissions.index',
        'icon' => 'file',
        'permission' => 'admissions.manage',
        'feature' => 'feature_ppdb',
    ],

    // ================= KATEGORI: KEUANGAN =================
    [
        'label' => 'KEUANGAN',
        'route' => null,
        'permission' => 'finance.view',
    ],
    [
        'label' => 'Jenis Tagihan',
        'route' => 'admin.finance.fee-types',
        'icon' => 'discount',
        'permission' => 'finance.view',
    ],
    [
        'label' => 'Tagihan Siswa',
        'route' => 'admin.finance.invoices',
        'icon' => 'credit-card',
        'permission' => 'finance.view',
    ],
    [
        'label' => 'Rekap Keuangan',
        'route' => 'admin.finance.summary',
        'icon' => 'chart-pie',
        'permission' => 'finance.view',
    ],
    [
        'label' => 'Pemasukan Lain (BOS)',
        'route' => 'admin.finance.other-incomes',
        'icon' => 'coin-stack',
        'permission' => 'finance.view',
    ],

    // ================= KATEGORI: LAYANAN SISWA =================
    [
        'label' => 'LAYANAN SISWA',
        'route' => null,
        'permission' => 'counseling.view',
    ],
    [
        'label' => 'Bimbingan Konseling',
        'route' => 'counselor.cases.index',
        'icon' => 'message-2',
        'permission' => 'counseling.view',
    ],
    [
        'label' => 'Poin Pelanggaran',
        'icon' => 'alert-triangle',
        'permission' => 'violations.manage',
        'children' => [
            [
                'label' => 'Catat Pelanggaran',
                'route' => 'admin.violations.index',
                'icon' => 'alert-octagon',
                'permission' => 'violations.manage',
            ],
            [
                'label' => 'Jenis Pelanggaran',
                'route' => 'admin.violations.types',
                'icon' => 'list',
                'permission' => 'violations.manage',
            ],
            [
                'label' => 'Rekap Poin',
                'route' => 'admin.violations.recap',
                'icon' => 'chart-pie',
                'permission' => 'violations.manage',
            ],
        ],
    ],
    [
        'label' => 'Absensi Harian (QR)',
        'icon' => 'computer-camera',
        'permission' => 'attendance.manage',
        'feature' => 'feature_qr_attendance',
        'children' => [
            [
                'label' => 'Tampilkan QR',
                'route' => 'admin.attendance.qr',
                'icon' => 'smartphone-vibration',
                'permission' => 'attendance.manage',
            ],
            [
                'label' => 'Rekap Harian',
                'route' => 'admin.attendance.recap',
                'icon' => 'clipboard',
                'permission' => 'attendance.manage',
            ],
        ],
    ],
    [
        'label' => 'PKL (SMK)',
        'route' => 'admin.internship.index',
        'icon' => 'building',
        'permission' => 'internship.manage',
    ],

    // ================= KATEGORI: RUANG GURU =================
    [
        'label' => 'RUANG GURU',
        'route' => null,
        'permission' => 'teacher.attendance', // Hanya muncul jika punya akses guru
    ],
    [
        'label' => 'Presensi Mengajar',
        'route' => 'teacher.attendance.index',
        'icon' => 'check-circle',
        'permission' => 'teacher.attendance',
    ],
    [
        'label' => 'Manajemen Tugas',
        'route' => 'teacher.assignments.index',
        'icon' => 'bookmark',
        'permission' => 'teacher.assignments',
    ],
    [
        'label' => 'Jurnal Mengajar',
        'route' => 'teacher.journals.index',
        'icon' => 'book-3',
        'permission' => 'teacher.journals',
    ],
    [
        'label' => 'Rekap Nilai',
        'route' => 'teacher.gradebook.index',
        'icon' => 'chart-pie',
        'permission' => 'teacher.gradebook',
    ],
    [
        'label' => 'Nilai Karakter',
        'route' => 'teacher.character.index',
        'icon' => 'award',
        'permission' => 'character.grade',
    ],
    [
        'label' => 'Ekskul Saya',
        'route' => 'teacher.extracurriculars.index',
        'icon' => 'award',
        'permission' => 'extracurricular.assess',
    ],
    [
        'label' => 'Bimbingan PKL',
        'route' => 'teacher.internship.index',
        'icon' => 'briefcase',
        'permission' => 'internship.assess',
    ],

    // ================= KATEGORI: RUANG SISWA =================
    [
        'label' => 'RUANG SISWA',
        'route' => null,
        'permission' => 'student.attendance', // Hanya muncul jika punya akses siswa
    ],
    [
        'label' => 'Presensi Saya',
        'route' => 'student.attendance.index',
        'icon' => 'clipboard',
        'permission' => 'student.attendance',
    ],
    [
        'label' => 'Scan Kehadiran',
        'route' => 'student.attendance-scan.index',
        'icon' => 'computer-camera',
        'permission' => 'student.attendance',
        'feature' => 'feature_qr_attendance',
    ],
    [
        'label' => 'Tugas Saya',
        'route' => 'student.assignments.index',
        'icon' => 'bookmark',
        'permission' => 'student.assignments',
    ],
    [
        'label' => 'Rapor Saya',
        'route' => 'student.gradebook.index',
        'icon' => 'file',
        'permission' => 'student.gradebook',
    ],
    [
        'label' => 'Ekstrakurikuler',
        'route' => 'student.extracurriculars.index',
        'icon' => 'award',
        'permission' => 'extracurricular.join',
    ],
    [
        'label' => 'PKL Saya',
        'route' => 'student.internship.index',
        'icon' => 'briefcase',
        'permission' => 'internship.log',
    ],

    // ================= KATEGORI: SISTEM =================
    [
        'label' => 'SISTEM',
        'route' => null,
        'permission' => 'system.settings',
    ],
    [
        'label' => 'Pengaturan Sistem',
        'icon' => 'settings',
        'permission' => 'system.settings',
        'children' => [
            [
                'label' => 'Role & Permission',
                'route' => 'admin.roles.index',
                'icon' => 'approved',
                'permission' => 'roles.view',
            ],
            [
                'label' => 'Identitas Sekolah',
                'route' => 'admin.settings.index',
                'icon' => 'world',
                'permission' => 'system.settings',
            ],
            [
                'label' => 'Audit Log',
                'route' => 'admin.audit.index',
                'icon' => 'info',
                'permission' => 'system.audit-log',
            ],
            [
                'label' => 'Manajemen Modul',
                'route' => 'admin.settings.features',
                'icon' => 'box',
                'permission' => 'system.settings',
            ],
        ],
    ],
];