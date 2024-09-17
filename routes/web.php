<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\KclassController;
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




    Route::redirect('/', '/settings/students')->name('home');
    Route::redirect('/settings', '/settings/students');
    $studentIndexRoute = StudentController::INDEX_ROUTE;
    $teacherIndexRoute = TeacherController::INDEX_ROUTE;
    $majorIndexRoute = MajorController::INDEX_ROUTE;
    $courseIndexRoute = CourseController::INDEX_ROUTE;
    $generationIndexRoute = GenerationController::INDEX_ROUTE;
    $kclassIndexRoute = KclassController::INDEX_ROUTE;
    Route::middleware([
        "set.sharedProps:resource=student;primaryField=full_name;index_route=$studentIndexRoute",
        "set.breadcrumbs"
    ])->controller(StudentController::class)->group(function () {
        Route::get('/settings/students', 'index')->name(StudentController::INDEX_ROUTE);
        Route::get('/settings/students/create', 'create')->name(StudentController::CREATE_ROUTE);
        Route::post('/settings/students/store', 'store')->name(StudentController::STORE_ROUTE);
        Route::get('/settings/students/edit-many', 'editMany')->name(StudentController::EDIT_MANY_ROUTE);
        Route::delete('/settings/students/destroy-many', 'destroyMany')->name(StudentController::DESTROY_MANY_ROUTE);
        Route::patch('/settings/students/update-many', 'updateMany')->name(StudentController::UPDATE_MANY_ROUTE);
        Route::patch('/settings/students/{student}/update', 'update')->name(StudentController::UPDATE_ROUTE);
        Route::get('/settings/students/{student}/edit', 'edit')->name(StudentController::EDIT_ROUTE);
        Route::delete('/settings/students/{student}/destroy', 'destroy')->name(StudentController::DESTROY_ROUTE);
        Route::get('/settings/students/{student}', 'show')->name(StudentController::SHOW_ROUTE);
    });
    // Teachers
    Route::middleware([
        "set.sharedProps:resource=teacher;primaryField=full_name;index_route=$teacherIndexRoute",
        "set.breadcrumbs"
    ])->controller(TeacherController::class)->group(function () {
        Route::get('/settings/teachers', 'index')->name(TeacherController::INDEX_ROUTE);
        Route::get('/settings/teachers/create', 'create')->name(TeacherController::CREATE_ROUTE);
        Route::post('/settings/teachers/store', 'store')->name(TeacherController::STORE_ROUTE);
        Route::patch('/settings/teachers/{teacher}/update', 'update')->name(TeacherController::UPDATE_ROUTE);
        Route::patch('/settings/teachers/update-many', 'updateMany')->name(TeacherController::UPDATE_MANY_ROUTE);
        Route::get('/settings/teachers/{teacher}/edit', 'edit')->name(TeacherController::EDIT_ROUTE);
        Route::get('/settings/teachers/edit-many', 'editMany')->name(TeacherController::EDIT_MANY_ROUTE);
        Route::delete('/settings/teachers/{teacher}/destroy', 'destroy')->name(TeacherController::DESTROY_ROUTE);
        Route::delete('/settings/teachers/destroy-many', 'destroyMany')->name(TeacherController::DESTROY_MANY_ROUTE);
        Route::get('/settings/teachers/{teacher}', 'show')->name(TeacherController::SHOW_ROUTE);
    });

    // Majors
    Route::middleware([
        "set.sharedProps:resource=major;primaryField=title;index_route=$majorIndexRoute",
        "set.breadcrumbs"
    ])->controller(MajorController::class)->group(function () {
        Route::get('/study/majors', 'index')->name(MajorController::INDEX_ROUTE);
        Route::get('/study/majors/create', 'create')->name(MajorController::CREATE_ROUTE);
        Route::post('/study/majors/store', 'store')->name(MajorController::STORE_ROUTE);
        Route::patch('/study/majors/{major}/update', 'update')->name(MajorController::UPDATE_ROUTE);
        Route::get('/study/majors/{major}/edit', 'edit')->name(MajorController::EDIT_ROUTE);
        Route::delete('/study/majors/{major}/destroy', 'destroy')->name(MajorController::DESTROY_ROUTE);
        Route::get('/study/majors/{major}', 'show')->name(MajorController::SHOW_ROUTE);
    });

    // Courses
    Route::middleware([
        "set.sharedProps:resource=course;primaryField=title;index_route=$courseIndexRoute",
        "set.breadcrumbs"
    ])->controller(CourseController::class)->group(function () {
        Route::get('/study/courses', 'index')->name(CourseController::INDEX_ROUTE);
        Route::get('/study/courses/create', 'create')->name(CourseController::CREATE_ROUTE);
        Route::post('/study/courses/store', 'store')->name(CourseController::STORE_ROUTE);
        Route::patch('/study/courses/{course}/update', 'update')->name(CourseController::UPDATE_ROUTE);
        Route::get('/study/courses/{course}/edit', 'edit')->name(CourseController::EDIT_ROUTE);
        Route::delete('/study/courses/{course}/destroy', 'destroy')->name(CourseController::DESTROY_ROUTE);
        Route::get('/study/courses/{course}', 'show')->name(CourseController::SHOW_ROUTE);
    });

    // Generations
    Route::middleware([
        "set.sharedProps:resource=generation;primaryField=title;index_route=$generationIndexRoute",
        "set.breadcrumbs"
    ])->controller(GenerationController::class)->group(function () {
        Route::get('/settings/generations', 'index')->name(GenerationController::INDEX_ROUTE);
        Route::get('/settings/generations/create', 'create')->name(GenerationController::CREATE_ROUTE);
        Route::post('/settings/generations/store', 'store')->name(GenerationController::STORE_ROUTE);
        Route::patch('/settings/generations/{generation}/update', 'update')->name(GenerationController::UPDATE_ROUTE);
        Route::get('/settings/generations/{generation}/edit', 'edit')->name(GenerationController::EDIT_ROUTE);
        Route::delete('/settings/generations/{generation}/destroy', 'destroy')->name(GenerationController::DESTROY_ROUTE);
        Route::get('/settings/generations/{generation}', 'show')->name(GenerationController::SHOW_ROUTE);
    });
    // Kclasses
    Route::middleware([
        "set.sharedProps:resource=class;resource_plural=classes;primaryField=title;index_route=$kclassIndexRoute",
        "set.breadcrumbs"
    ])->controller(KclassController::class)->group(function () {
        Route::get('/study/classes', 'index')->name(KclassController::INDEX_ROUTE);
        Route::get('/study/classes/create', 'create')->name(KclassController::CREATE_ROUTE);
        Route::post('/study/classes/store', 'store')->name(KclassController::STORE_ROUTE);
        Route::patch('/study/classes/{generation}/update', 'update')->name(KclassController::UPDATE_ROUTE);
        Route::get('/study/classes/{generation}/edit', 'edit')->name(KclassController::EDIT_ROUTE);
        Route::delete('/study/classes/{generation}/destroy', 'destroy')->name(KclassController::DESTROY_ROUTE);
        Route::get('/study/classes/{generation}', 'show')->name(KclassController::SHOW_ROUTE);
    });

});

require __DIR__ . '/auth.php';
