<?php

use App\Http\Controllers\MajorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', function () {
        return redirect()->route('profile.user-info');
    });
    Route::get('/profile/user-info', [ProfileController::class, 'updateUserInfo'])->name('profile.user-info');
    Route::get('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/', function () {
        return redirect("/settings/students");
    })->name('home');


    Route::get('/settings', function () {
        return redirect("/settings/students");
    });
    Route::middleware("set.sharedProps:resource=student;primaryField=full_name")->group(function () {
        Route::get('/settings/students', [StudentController::class, 'index'])->name(StudentController::$INDEX_ROUTE);
        Route::get('/settings/students/create', [StudentController::class, 'create'])->name(StudentController::$INDEX_ROUTE);
        Route::post('/settings/students/store', [StudentController::class, 'store'])->name(StudentController::$STORE_ROUTE);
        Route::patch('/settings/students/{student}/update', [StudentController::class, 'update'])->name(StudentController::$UPDATE_ROUTE);
        Route::get('/settings/students/{student}/edit', [StudentController::class, 'edit'])->name(StudentController::$EDIT_ROUTE);
        Route::delete('/settings/students/{student}/destroy', [StudentController::class, 'destroy'])->name(StudentController::$DESTROY_ROUTE);
        Route::delete('/settings/students/destroy-many', [StudentController::class, 'destroyMany'])->name(StudentController::$DESTROY_MANY_ROUTE);
        Route::get('/settings/students/{student}', [StudentController::class, 'show'])->name(StudentController::$SHOW_ROUTE);
    });

    Route::middleware("set.sharedProps:resource=teacher;primaryField=full_name")->group(function () {
        Route::get('/settings/teachers', [TeacherController::class, 'index'])->name('settings.teachers');
        Route::get('/settings/teachers/create', [TeacherController::class, 'create'])->name('settings.teachers.create');
        Route::post('/settings/teachers/store', [TeacherController::class, 'store'])->name('settings.teachers.store');
        Route::patch('/settings/teachers/{teacher}/update', [TeacherController::class, 'update'])->name('settings.teachers.update');
        Route::get('/settings/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('settings.teachers.edit');
        Route::delete('/settings/teachers/{teacher}/destroy', [TeacherController::class, 'destroy'])->name('settings.teachers.destroy');
        Route::get('/settings/teachers/{teacher}', [TeacherController::class, 'show'])->name('settings.teachers.show');
    });
    Route::middleware("set.sharedProps:resource=major;primaryField=title")->group(function () {
        Route::get('/study/majors', [MajorController::class, 'index'])->name('study.majors');
        Route::get('/study/majors/create', [MajorController::class, 'create'])->name('study.majors.create');
        Route::post('/study/majors/store', [MajorController::class, 'store'])->name('study.majors.store');
        Route::patch('/study/majors/{major}/update', [MajorController::class, 'update'])->name('study.majors.update');
        Route::get('/study/majors/{major}/edit', [MajorController::class, 'edit'])->name('study.majors.edit');
        Route::delete('/study/majors/{major}/destroy', [MajorController::class, 'destroy'])->name('study.majors.destroy');
        Route::get('/study/majors/{major}', [MajorController::class, 'show'])->name('study.majors.show');
    });
});

require __DIR__ . '/auth.php';
