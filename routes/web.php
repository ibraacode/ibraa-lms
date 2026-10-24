<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    $previous = url()->previous();
    if (str_contains($previous, '/locale/')) {
        return redirect('/');
    }
    return redirect($previous);
})->name('locale.switch');

// Guest routes
Route::middleware('guest')->group(function () {
    // Route untuk menampilkan halaman form login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    
    // Route untuk memproses login (dengan rate limiting)
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.attempt');
});
// ==========================================
// AUTH ROUTES (Semua yang butuh login ada di sini)
// ==========================================
Route::middleware(['auth', 'readonly.year'])->group(function () {
    
    // --- Core Auth & Profile ---
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])->name('password.update');
    
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // --- Modul Manajemen Pengguna (berbasis Permission) ---
    Route::middleware('permission:users.view')->group(function () {
        Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/users/{user}/toggle', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('admin.users.toggle');
    });

    Route::middleware('permission:users.create')->group(function () {
        Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');
    });

    Route::middleware('permission:users.import')->group(function () {
        Route::get('/admin/users/import', [\App\Http\Controllers\Admin\UserController::class, 'showImportForm'])->name('admin.users.import');
        Route::post('/admin/users/import', [\App\Http\Controllers\Admin\UserController::class, 'processImport'])->name('admin.users.process-import');
    });

    // --- Modul Akademik (Berbasis Permission) ---
    Route::middleware('permission:academic.subjects.manage')->prefix('admin/academic')->name('admin.academic.')->group(function () {
        Route::get('/subjects', [\App\Http\Controllers\Admin\AcademicController::class, 'subjects'])->name('subjects');
        Route::post('/subjects', [\App\Http\Controllers\Admin\AcademicController::class, 'storeSubject'])->name('subjects.store');
        
        Route::get('/classrooms', [\App\Http\Controllers\Admin\AcademicController::class, 'classrooms'])->name('classrooms');
        Route::post('/classrooms', [\App\Http\Controllers\Admin\AcademicController::class, 'storeClassroom'])->name('classrooms.store');
        
        Route::get('/schedules', [\App\Http\Controllers\Admin\AcademicController::class, 'schedules'])->name('schedules');
        Route::post('/schedules', [\App\Http\Controllers\Admin\AcademicController::class, 'storeSchedule'])->name('schedules.store');
    });

    // --- Penempatan Kelas Siswa (Berbasis Permission) ---
    Route::middleware('permission:academic.classrooms.manage')->prefix('admin/students')->name('admin.students.')->group(function () {
        Route::get('/assign', [\App\Http\Controllers\Admin\StudentClassController::class, 'index'])->name('assign');
        Route::post('/{student}/assign', [\App\Http\Controllers\Admin\StudentClassController::class, 'update'])->name('update-class');
    });

    // --- Presensi Guru (Berbasis Permission) ---
    Route::middleware('permission:teacher.attendance')->prefix('teacher/attendance')->name('teacher.attendance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\AttendanceController::class, 'index'])->name('index');
        Route::post('/open/{schedule}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'openSession'])->name('open');
        Route::get('/{session}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'show'])->name('show');
        Route::post('/{session}', [\App\Http\Controllers\Teacher\AttendanceController::class, 'update'])->name('update');
    });

      // --- Presensi Manual oleh Admin/TU ---
    Route::middleware('permission:attendance.manage')->prefix('admin/attendance/manual')->name('admin.attendance.manual.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'index'])->name('index');
        Route::get('/classroom/{classroom}', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'showClassroom'])->name('classroom');
        Route::post('/classroom/{classroom}/session/{schedule}', [\App\Http\Controllers\Admin\ManualAttendanceController::class, 'storeSession'])->name('store');
    });

    // --- Rekap Presensi (Bulanan) ---
    Route::middleware('permission:attendance.manage')->prefix('admin/attendance/report')->name('admin.attendance.report.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AttendanceReportController::class, 'index'])->name('index');
        Route::get('/print', [\App\Http\Controllers\Admin\AttendanceReportController::class, 'print'])->name('print');
    });

    // --- Presensi Siswa (Berbasis Permission) ---
    Route::middleware('permission:student.attendance')->prefix('student/attendance')->name('student.attendance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('index');
    });

    // --- Tugas Guru (Berbasis Permission) ---
    Route::middleware('permission:teacher.assignments')->prefix('teacher/assignments')->name('teacher.assignments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\AssignmentController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Teacher\AssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}', [\App\Http\Controllers\Teacher\AssignmentController::class, 'show'])->name('show');
        Route::post('/{assignment}/grade', [\App\Http\Controllers\Teacher\AssignmentController::class, 'grade'])->name('grade');
    });

    // --- Tugas Siswa (Berbasis Permission) ---
    Route::middleware('permission:student.assignments')->prefix('student/assignments')->name('student.assignments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\AssignmentController::class, 'index'])->name('index');
        Route::post('/{assignment}/submit', [\App\Http\Controllers\Student\AssignmentController::class, 'submit'])->name('submit');
    });

    // --- Jurnal Guru (Berbasis Permission) ---
    Route::middleware('permission:teacher.journals')->prefix('teacher/journals')->name('teacher.journals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\JournalController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Teacher\JournalController::class, 'store'])->name('store');
    });

    // --- Pengumuman (Berbasis Permission) ---
    Route::middleware('permission:announcements.create')->prefix('admin/announcements')->name('admin.announcements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('store');
    });

    // --- Rapor Guru (Berbasis Permission) ---
    Route::middleware('permission:teacher.gradebook')->prefix('teacher/gradebook')->name('teacher.gradebook.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\GradebookController::class, 'index'])->name('index');
        Route::get('/{schedule}', [\App\Http\Controllers\Teacher\GradebookController::class, 'show'])->name('show');
    });

    // --- Rapor Siswa (Berbasis Permission) ---
    Route::middleware('permission:student.gradebook')->prefix('student/gradebook')->name('student.gradebook.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\GradebookController::class, 'index'])->name('index');
    });

    // --- Manajemen Role & Permission (Berbasis Permission) ---
    Route::middleware('permission:roles.view')->prefix('admin/roles')->name('admin.roles.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RoleController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RoleController::class, 'store'])->name('store');
        Route::get('/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('edit');
        Route::put('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('destroy');
    });

      // Pengaturan Sistem
    Route::middleware('permission:system.settings')->prefix('admin/settings')->name('admin.settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
              Route::get('/features', [\App\Http\Controllers\Admin\FeatureToggleController::class, 'index'])->name('features');
        Route::post('/features', [\App\Http\Controllers\Admin\FeatureToggleController::class, 'update'])->name('features.update');
    });

      // Audit Log
    Route::middleware('permission:system.audit-log')->prefix('admin/audit')->name('admin.audit.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('index');
    });
  
    // --- Keuangan ---
    Route::middleware(['permission:finance.view', 'feature:feature_finance'])->prefix('admin/finance')->name('admin.finance.')->group(function () {
        Route::get('/fee-types', [\App\Http\Controllers\Admin\FinanceController::class, 'feeTypes'])->name('fee-types');
        Route::get('/invoices', [\App\Http\Controllers\Admin\FinanceController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{invoice}', [\App\Http\Controllers\Admin\FinanceController::class, 'showInvoice'])->name('invoices.show');
        Route::get('/summary', [\App\Http\Controllers\Admin\FinanceController::class, 'summary'])->name('summary');
        Route::get('/other-incomes', [\App\Http\Controllers\Admin\FinanceController::class, 'otherIncomes'])->name('other-incomes');

        Route::middleware('permission:finance.manage')->group(function () {
            Route::post('/fee-types', [\App\Http\Controllers\Admin\FinanceController::class, 'storeFeeType'])->name('fee-types.store');
            Route::post('/invoices', [\App\Http\Controllers\Admin\FinanceController::class, 'storeInvoice'])->name('invoices.store');
            Route::post('/invoices/{invoice}/payments', [\App\Http\Controllers\Admin\FinanceController::class, 'storePayment'])->name('payments.store');
            Route::post('/other-incomes', [\App\Http\Controllers\Admin\FinanceController::class, 'storeOtherIncome'])->name('other-incomes.store');
        });
    });

    // --- Absensi Harian (Admin/TU) ---
    Route::middleware(['permission:attendance.manage', 'feature:feature_qr_attendance'])->prefix('admin/attendance')->name('admin.attendance.')->group(function () {
        Route::get('/qr', [\App\Http\Controllers\Admin\DailyAttendanceController::class, 'qrCodes'])->name('qr');
        Route::get('/recap', [\App\Http\Controllers\Admin\DailyAttendanceController::class, 'dailyRecap'])->name('recap');
    });

    // --- Scan Absensi Siswa ---
    Route::middleware(['permission:student.attendance', 'feature:feature_qr_attendance'])->prefix('student/scan')->name('student.attendance-scan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\ScanAttendanceController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Student\ScanAttendanceController::class, 'process'])->name('process');
    });

      // Ekstrakurikuler (Admin/TU)
    Route::middleware('permission:extracurricular.manage')->prefix('admin/extracurriculars')->name('admin.extracurriculars.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ExtracurricularController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\ExtracurricularController::class, 'store'])->name('store');
        Route::post('/{extracurricular}/toggle-open', [\App\Http\Controllers\Admin\ExtracurricularController::class, 'toggleOpen'])->name('toggle-open');
        Route::get('/{extracurricular}/members', [\App\Http\Controllers\Admin\ExtracurricularController::class, 'members'])->name('members');
    });

    // Ekstrakurikuler (Guru Pembina)
    Route::middleware('permission:extracurricular.assess')->prefix('teacher/extracurriculars')->name('teacher.extracurriculars.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\ExtracurricularController::class, 'index'])->name('index');
        Route::get('/{extracurricular}', [\App\Http\Controllers\Teacher\ExtracurricularController::class, 'show'])->name('show');
        Route::post('/{extracurricular}/grade', [\App\Http\Controllers\Teacher\ExtracurricularController::class, 'grade'])->name('grade');
    });

    // Ekstrakurikuler (Siswa)
    Route::middleware('permission:extracurricular.join')->prefix('student/extracurriculars')->name('student.extracurriculars.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\ExtracurricularController::class, 'index'])->name('index');
        Route::post('/{extracurricular}/join', [\App\Http\Controllers\Student\ExtracurricularController::class, 'join'])->name('join');
    });

      // Bimbingan Konseling (BK)
    Route::middleware('permission:counseling.view')->prefix('counselor/cases')->name('counselor.cases.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Counselor\CaseController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Counselor\CaseController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Counselor\CaseController::class, 'store'])->name('store');
        Route::get('/{case}', [\App\Http\Controllers\Counselor\CaseController::class, 'show'])->name('show');
        Route::post('/{case}/note', [\App\Http\Controllers\Counselor\CaseController::class, 'addNote'])->name('add-note');
        Route::post('/{case}/status', [\App\Http\Controllers\Counselor\CaseController::class, 'updateStatus'])->name('update-status');
    });

      // Surat-Menyurat
    Route::middleware('permission:letters.manage')->prefix('admin/letters')->name('admin.letters.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LetterController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\LetterController::class, 'store'])->name('store');
        Route::get('/templates', [\App\Http\Controllers\Admin\LetterController::class, 'templates'])->name('templates');
        Route::post('/templates', [\App\Http\Controllers\Admin\LetterController::class, 'storeTemplate'])->name('templates.store');
        Route::delete('/templates/{template}', [\App\Http\Controllers\Admin\LetterController::class, 'destroyTemplate'])->name('templates.destroy');
        Route::get('/{letter}', [\App\Http\Controllers\Admin\LetterController::class, 'show'])->name('show');
        Route::get('/{letter}/print', [\App\Http\Controllers\Admin\LetterController::class, 'print'])->name('print');
    });

      // PPDB Admin
    Route::middleware('permission:admissions.manage')->prefix('admin/admissions')->name('admin.admissions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdmissionController::class, 'index'])->name('index');
        Route::get('/{admission}', [\App\Http\Controllers\Admin\AdmissionController::class, 'show'])->name('show');
        Route::post('/{admission}/verify', [\App\Http\Controllers\Admin\AdmissionController::class, 'verify'])->name('verify');
        Route::post('/{admission}/convert', [\App\Http\Controllers\Admin\AdmissionController::class, 'convertToStudent'])->name('convert');
    });

      // Pusat Bantuan
    Route::get('/help', [\App\Http\Controllers\HelpController::class, 'index'])->name('help.index');

      // Penilaian Karakter
    Route::middleware('permission:character.grade')->prefix('teacher/character')->name('teacher.character.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\CharacterController::class, 'index'])->name('index');
        Route::get('/{classroom}', [\App\Http\Controllers\Teacher\CharacterController::class, 'showClassroom'])->name('show');
        Route::post('/{classroom}', [\App\Http\Controllers\Teacher\CharacterController::class, 'store'])->name('store');
    });

      // PKL (Siswa)
    Route::middleware('permission:internship.log')->prefix('student/internship')->name('student.internship.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\InternshipController::class, 'index'])->name('index');
        Route::post('/check-in', [\App\Http\Controllers\Student\InternshipController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out', [\App\Http\Controllers\Student\InternshipController::class, 'checkOut'])->name('checkout');
    });

    // PKL (Admin/TU)
    Route::middleware('permission:internship.manage')->prefix('admin/internship')->name('admin.internship.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\InternshipController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\InternshipController::class, 'storePlacement'])->name('store');
        Route::get('/assign', [\App\Http\Controllers\Admin\InternshipController::class, 'assign'])->name('assign');
    });

      // PKL (Guru Pembimbing)
    Route::middleware('permission:internship.assess')->prefix('teacher/internship')->name('teacher.internship.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\InternshipController::class, 'index'])->name('index');
        Route::get('/{studentId}', [\App\Http\Controllers\Teacher\InternshipController::class, 'show'])->name('show');
        Route::post('/{studentId}/grade', [\App\Http\Controllers\Teacher\InternshipController::class, 'grade'])->name('grade');
        Route::get('/{studentId}/print', [\App\Http\Controllers\Teacher\InternshipController::class, 'print'])->name('print');
    });

    // Simpan Penempatan Siswa PKL (Admin)
    Route::middleware('permission:internship.manage')->post('/admin/internship/assign', [\App\Http\Controllers\Admin\InternshipController::class, 'storeAssign'])->name('admin.internship.assign.store');

      // Manajemen Tahun Ajaran
    Route::middleware('permission:academic.subjects.manage')->prefix('admin/academic-year')->name('admin.academic-year.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AcademicYearController::class, 'index'])->name('index');
        Route::post('/promote', [\App\Http\Controllers\Admin\AcademicYearController::class, 'promote'])->name('promote');
        Route::post('/graduate', [\App\Http\Controllers\Admin\AcademicYearController::class, 'graduate'])->name('graduate');
    });

      // Switch Tahun Ajaran
    Route::post('/switch-academic-year', function (Request $request) {
        session(['active_academic_year' => $request->academic_year]);
        return back();
    })->name('admin.academic-year.switch');
});

// PPDB Publik (tanpa login)
Route::prefix('ppdb')->name('admission.')->group(function () {
    Route::get('/', [\App\Http\Controllers\PublicAdmissionController::class, 'showForm'])->name('form');
    Route::post('/', [\App\Http\Controllers\PublicAdmissionController::class, 'store'])->name('store');
    Route::get('/success/{registrationNumber}', [\App\Http\Controllers\PublicAdmissionController::class, 'success'])->name('success');
});