<?php


use App\Http\Controllers\Dashboard\HomeController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\ClassesController;
use App\Http\Controllers\Dashboard\TeachersController;
use App\Http\Controllers\Dashboard\AttendanceController;
use App\Http\Controllers\Dashboard\ExpensesController;
use App\Http\Controllers\Dashboard\StudentsController;
use App\Http\Controllers\Dashboard\RevenuesController;
use App\Http\Controllers\Dashboard\SalaryCardController;





use App\Http\Controllers\Dashboard\PhoneVerificationController;




use Illuminate\Support\Facades\Route;




Route::group(['prefix' => 'dashboard', 'middleware' => ['role:superadministrator|administrator']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard')->middleware('auth', 'checkverified', 'checkstatus');

    // admin users routes
    Route::resource('users', UsersController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('users/export/', [UsersController::class, 'export'])->name('users.export')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-users', [UsersController::class, 'trashed'])->name('users.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-users/{user}', [UsersController::class, 'restore'])->name('users.restore')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/activate-users/{user}', [UsersController::class, 'activate'])->name('users.activate')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/block-users/{user}', [UsersController::class, 'block'])->name('users.block')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('/add-bonus/{user}', [UsersController::class, 'bonus'])->name('users.bonus')->middleware('auth', 'checkverified', 'checkstatus');
    Route::post('/bulk', [UsersController::class, 'bulk'])->name('users.bulk')->middleware('auth', 'checkverified', 'checkstatus');


    // roles routes
    Route::resource('roles',  RoleController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-roles', [RoleController::class, 'trashed'])->name('roles.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-roles/{role}', [RoleController::class, 'restore'])->name('roles.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // classes routes
    Route::resource('classes', ClassesController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-classes', [ClassesController::class, 'trashed'])->name('classes.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-classes/{class}', [ClassesController::class, 'restore'])->name('classes.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // teachers route
    Route::resource('teachers', TeachersController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-teachers', [TeachersController::class, 'trashed'])->name('teachers.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-tasks/{teacher}', [TeachersController::class, 'restore'])->name('teachers.restore')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/delete-teachers/{teacher}', [TeachersController::class, 'destroy'])->name('teachers.del')->middleware('auth', 'checkverified', 'checkstatus');

    // attendance route
    Route::resource('attendance', AttendanceController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-attendance', [AttendanceController::class, 'trashed'])->name('attendance.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-attendance/{attendance}', [AttendanceController::class, 'restore'])->name('attendance.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // // expenses route
    Route::resource('expenses', ExpensesController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-expenses', [ExpensesController::class, 'trashed'])->name('expenses.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-expenses/{expense}', [ExpensesController::class, 'restore'])->name('expenses.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // // students route
    Route::resource('students', StudentsController::class)->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-students', [StudentsController::class, 'trashed'])->name('students.trashed')->middleware('auth', 'checkverified', 'checkstatus');
    Route::get('/trashed-students/{student}', [StudentsController::class, 'restore'])->name('students.restore')->middleware('auth', 'checkverified', 'checkstatus');


     // // revenues route
     Route::resource('revenues', RevenuesController::class)->middleware('auth', 'checkverified', 'checkstatus');
     Route::get('/trashed-revenues', [RevenuesController::class, 'trashed'])->name('revenues.trashed')->middleware('auth', 'checkverified', 'checkstatus');
     Route::get('/trashed-revenues/{revenue}', [RevenuesController::class, 'restore'])->name('revenues.restore')->middleware('auth', 'checkverified', 'checkstatus');

    // // salary_card  action
    Route::resource('cards',SalaryCardController::class)->middleware('auth', 'checkverified', 'checkstatus');


    // Route::post('/tasks/bulk-action', [TasksController::class, 'bulkAction'])->name('tasks.bulk-action')->middleware('auth', 'checkverified', 'checkstatus');
});



Route::group(['middleware' => ['role:superadministrator|administrator|tech']], function () {

    // verification routes
    Route::get('phone/verify', [PhoneVerificationController::class, 'show'])->name('phoneverification.notice')->middleware('auth', 'checkstatus');
    Route::post('phone/verify', [PhoneVerificationController::class, 'verify'])->name('phoneverification.verify')->middleware('auth', 'checkstatus');
    Route::get('/resend-code', [PhoneVerificationController::class, 'resend'])->name('resend-code')->middleware('auth', 'checkstatus');
});
