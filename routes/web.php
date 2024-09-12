<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\GenerationController;
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
    $studentIndexRoute = StudentController::INDEX_ROUTE;
    $teacherIndexRoute = TeacherController::INDEX_ROUTE;
    $majorIndexRoute = MajorController::INDEX_ROUTE;
    $courseIndexRoute = CourseController::INDEX_ROUTE;
    $generationIndexRoute = GenerationController::INDEX_ROUTE;
    Route::middleware(["set.sharedProps:resource=student;primaryField=full_name;index_route=$studentIndexRoute", "set.breadcrumbs"])->group(function () {
        Route::get('/settings/students', [StudentController::class, 'index'])->name(StudentController::INDEX_ROUTE);
        Route::get('/settings/students/create', [StudentController::class, 'create'])->name(StudentController::CREATE_ROUTE);
        Route::post('/settings/students/store', [StudentController::class, 'store'])->name(StudentController::STORE_ROUTE);
        Route::get('/settings/students/edit-many', [StudentController::class, 'editMany'])->name(StudentController::EDIT_MANY_ROUTE);
        Route::delete('/settings/students/destroy-many', [StudentController::class, 'destroyMany'])->name(StudentController::DESTROY_MANY_ROUTE);
        Route::patch('/settings/students/update-many', [StudentController::class, 'updateMany'])->name(StudentController::UPDATE_MANY_ROUTE);
        Route::patch('/settings/students/{student}/update', [StudentController::class, 'update'])->name(StudentController::UPDATE_ROUTE);
        Route::get('/settings/students/{student}/edit', [StudentController::class, 'edit'])->name(StudentController::EDIT_ROUTE);
        Route::delete('/settings/students/{student}/destroy', [StudentController::class, 'destroy'])->name(StudentController::DESTROY_ROUTE);
        Route::get('/settings/students/{student}', [StudentController::class, 'show'])->name(StudentController::SHOW_ROUTE);
    });

    Route::middleware(["set.sharedProps:resource=teacher;primaryField=full_name;index_route=$teacherIndexRoute", "set.breadcrumbs"])->group(function () {
        Route::get('/settings/teachers', [TeacherController::class, 'index'])->name(TeacherController::INDEX_ROUTE);
        Route::get('/settings/teachers/create', [TeacherController::class, 'create'])->name(TeacherController::CREATE_ROUTE);
        Route::post('/settings/teachers/store', [TeacherController::class, 'store'])->name(TeacherController::STORE_ROUTE);
        Route::patch('/settings/teachers/{teacher}/update', [TeacherController::class, 'update'])->name(TeacherController::UPDATE_ROUTE);
        Route::get('/settings/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name(TeacherController::EDIT_ROUTE);
        Route::delete('/settings/teachers/{teacher}/destroy', [TeacherController::class, 'destroy'])->name(TeacherController::DESTROY_ROUTE);
        Route::delete('/settings/teachers/destroy-many', [TeacherController::class, 'destroyMany'])->name(TeacherController::DESTROY_MANY_ROUTE);
        Route::get('/settings/teachers/{teacher}', [TeacherController::class, 'show'])->name(TeacherController::SHOW_ROUTE);

    });
    Route::middleware(["set.sharedProps:resource=major;primaryField=title;index_route=$majorIndexRoute", "set.breadcrumbs" ])->group(function () {
        Route::get('/study/majors', [MajorController::class, 'index'])->name(MajorController::INDEX_ROUTE);
        Route::get('/study/majors/create', [MajorController::class, 'create'])->name(MajorController::CREATE_ROUTE);
        Route::post('/study/majors/store', [MajorController::class, 'store'])->name(MajorController::STORE_ROUTE);
        Route::patch('/study/majors/{major}/update', [MajorController::class, 'update'])->name(MajorController::UPDATE_ROUTE);
        Route::get('/study/majors/{major}/edit', [MajorController::class, 'edit'])->name(MajorController::EDIT_ROUTE);
        Route::delete('/study/majors/{major}/destroy', [MajorController::class, 'destroy'])->name(MajorController::DESTROY_ROUTE);
        Route::get('/study/majors/{major}', [MajorController::class, 'show'])->name(MajorController::SHOW_ROUTE);
    });
    Route::middleware(["set.sharedProps:resource=course;primaryField=title;index_route=$courseIndexRoute", "set.breadcrumbs" ])->group(function () {
        Route::get('/study/courses', [CourseController::class, 'index'])->name(CourseController::INDEX_ROUTE);
        Route::get('/study/courses/create', [CourseController::class, 'create'])->name(CourseController::CREATE_ROUTE);
        Route::post('/study/courses/store', [CourseController::class, 'store'])->name(CourseController::STORE_ROUTE);
        Route::patch('/study/courses/{course}/update', [CourseController::class, 'update'])->name(CourseController::UPDATE_ROUTE);
        Route::get('/study/courses/{course}/edit', [CourseController::class, 'edit'])->name(CourseController::EDIT_ROUTE);
        Route::delete('/study/courses/{course}/destroy', [CourseController::class, 'destroy'])->name(CourseController::DESTROY_ROUTE);
        Route::get('/study/courses/{course}', [CourseController::class, 'show'])->name(CourseController::SHOW_ROUTE);
    });
    Route::middleware(["set.sharedProps:resource=generation;primaryField=title;index_route=$generationIndexRoute","set.breadcrumbs"])->group(function () {
        Route::get('/settings/generations', [GenerationController::class, 'index'])->name(GenerationController::INDEX_ROUTE);
        Route::get('/settings/generations/create', [GenerationController::class, 'create'])->name(GenerationController::CREATE_ROUTE);
        Route::post('/settings/generations/store', [GenerationController::class, 'store'])->name(GenerationController::STORE_ROUTE);
        Route::patch('/settings/generations/{generation}/update', [GenerationController::class, 'update'])->name(GenerationController::UPDATE_ROUTE);
        Route::get('/settings/generations/{generation}/edit', [GenerationController::class, 'edit'])->name(GenerationController::EDIT_ROUTE);
        Route::delete('/settings/generations/{generation}/destroy', [GenerationController::class, 'destroy'])->name(GenerationController::DESTROY_ROUTE);
        Route::get('/settings/generations/{generation}', [GenerationController::class, 'show'])->name(GenerationController::SHOW_ROUTE);
    });
});

require __DIR__ . '/auth.php';
