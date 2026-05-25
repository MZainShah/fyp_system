<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\adminPanel\DashboardController as AdminDashboardController;
use App\Http\Controllers\adminPanel\LoginController as AdminLoginController;
use App\Http\Controllers\adminPanel\AddSupervisorController as AdminAddSupervisorController;
use App\Http\Controllers\adminPanel\ViewSupervisorController as AdminViewSupervisorController;
use App\Http\Controllers\adminPanel\StudentImportController;
use App\Http\Controllers\adminPanel\AllocationController;
use App\Http\Controllers\supervisorPanel\LoginController as SupervisorLoginController;
use App\Http\Controllers\supervisorPanel\SupervisorDashboardController;
use App\Http\Controllers\studentPanel\StudentLoginController as StudentLoginController;
use App\Http\Controllers\studentPanel\ProjectSubmissionController;
use App\Http\Controllers\supervisorPanel\SupervisorStudentDirectoryController;
use App\Http\Controllers\studentPanel\SrsSddController;
use App\Http\Controllers\supervisorPanel\SrsSddEvaluationController;
use App\Http\Controllers\adminPanel\AdminNoticeController;
use App\Http\Controllers\NoticeBoardController;

Route::get('/', function () {
    return view('welcome');
});


// Student Dashboard Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/noticeboard', [NoticeBoardController::class, 'index'])->name('noticeboard');
});

// Supervisor Dashboard Routes
Route::prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/noticeboard', [NoticeBoardController::class, 'index'])->name('noticeboard');
});

// Admin Routes

Route::get('/admin/login', [AdminLoginController::class, 'showLogin']);
Route::post('/admin/login', [AdminLoginController::class, 'adminLogin']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/supervisor/create', [AdminAddSupervisorController::class, 'create']);
Route::post('/admin/supervisor/store', [AdminAddSupervisorController::class, 'store']);
Route::get('/admin/supervisor/view', [AdminViewSupervisorController::class, 'index'])->name('admin.supervisors');
Route::get('/admin/supervisor/edit/{id}', [AdminAddSupervisorController::class, 'edit'])->name('admin.supervisor.edit');
Route::post('/admin/supervisor/update/{id}', [AdminAddSupervisorController::class, 'update'])->name('admin.supervisor.update');
Route::post('/admin/supervisor/delete/{id}', [AdminAddSupervisorController::class, 'destroy'])->name('admin.supervisor.delete');
Route::get('/admin/import-students', [StudentImportController::class, 'index'])->name('admin.student.import');
Route::post('/admin/import-students', [StudentImportController::class, 'import']);
Route::get('/admin/students/list', [StudentImportController::class, 'studentsList'])->name('admin.students.list');
Route::delete('/admin/students/{id}',[StudentImportController::class, 'deleteStudent'])->name('admin.students.delete');
Route::get('/admin/students/{id}/edit', [StudentImportController::class, 'editStudent'])->name('admin.students.edit');
Route::put('/admin/students/{id}', [StudentImportController::class, 'updateStudent'])->name('admin.students.update');
Route::get('/admin/student/add', [StudentImportController::class, 'create'])->name('admin.students.add');
Route::post('/admin/students', [StudentImportController::class, 'store'])->name('admin.students.store');
Route::get('/admin/allocation', [AllocationController::class,'index'])->name('admin.allocations');
Route::post('/admin/run-allocation', [AllocationController::class,'allocate']);
Route::get('/admin/allocated-supervisors', [AllocationController::class, 'supervisorsAllocation'])->name('allocated.supervisors.list');
Route::get('/allocated/students/{supervisorId}', [AllocationController::class, 'viewAllocatedStudents'])->name('allocated.students.list');
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/notices', [AdminNoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices/store', [AdminNoticeController::class, 'store'])->name('notices.store');
});
Route::get('admin/all-notices', [AdminNoticeController::class, 'index'])->name('admin.notices.index');
Route::get('admin/edit-notice/{row}', [AdminNoticeController::class, 'edit'])->name('admin.notices.edit');
Route::put('admin/update-notice/{row}', [AdminNoticeController::class, 'update'])->name('admin.notices.update');
Route::get('admin/download-marksheet', [App\Http\Controllers\adminPanel\DashboardController::class, 'downloadMarksheet']);


// Supervisor Routes

Route::get('supervisor/login', [SupervisorLoginController::class, 'showLoginForm'])->name('supervisor.login');
Route::get('/auth/google', [SupervisorLoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SupervisorLoginController::class, 'handleGoogleCallback']);

Route::get('/supervisor/dashboard', [SupervisorDashboardController::class, 'index'])->name('supervisor.dashboard');
Route::post('/supervisor/logout', [SupervisorLoginController::class, 'logout'])->name('supervisor.logout');
Route::post('/supervisor/update-status', [SupervisorDashboardController::class, 'updateStatus'])->name('supervisor.updateStatus');
Route::get('/supervisor/student-directory', [SupervisorStudentDirectoryController::class, 'index'])->name('supervisor.studentDirectory');
Route::get('/supervisor/view-documentation', [SrsSddEvaluationController::class, 'index'])->name('supervisor.viewDocs');
Route::post('/supervisor/update-evaluation', [SrsSddEvaluationController::class, 'updateEvaluation'])->name('supervisor.updateEvaluation');
Route::get('/supervisor/download-marks-sheet', [SrsSddEvaluationController::class, 'downloadMarksSheet'])->name('supervisor.downloadMarks');

// 1. Student Routes


Route::get('/student/login', function () {
    return view('studentPanel.login'); 
})->name('student.login.view');

Route::get('/auth/student/google', [StudentLoginController::class, 'redirectToGoogle'])
    ->name('student.login.google');

Route::get('/auth/student/callback', [StudentLoginController::class, 'handleGoogleCallback']);

Route::post('/student/logout', [StudentLoginController::class, 'logout'])
    ->name('student.logout');

Route::get('/student/dashboard', [App\Http\Controllers\studentPanel\StudentLoginController::class, 'dashboard'])->name('student.dashboard');

Route::get('/student/project', [ProjectSubmissionController::class, 'index'])->name('student.project.view');

Route::post('/student/project/submit', [ProjectSubmissionController::class, 'store'])->name('student.project.submit');

Route::get('/student/srs-sdd', [SrsSddController::class, 'index'])->name('student.srssdd.view');
Route::post('/student/srs-sdd/store', [SrsSddController::class, 'store'])->name('student.srssdd.store');
Route::post('/student/srssdd/update', [SrsSddController::class, 'update'])->name('student.srssdd.update');