<?php

use App\Http\Controllers\EntityController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseLevelController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('course-content', [CourseContentController::class, 'index'])->name('course-content.index');

    // 2. Create & Store
    Route::get('course-content/create', [CourseContentController::class, 'create'])->name('course-content.create');
    Route::post('course-content', [CourseContentController::class, 'store'])->name('course-content.store');

    // 3. Edit & Update
    Route::get('course-content/{course_content}/edit', [CourseContentController::class, 'edit'])->name('course-content.edit');
    Route::put('course-content/{course_content}', [CourseContentController::class, 'update'])->name('course-content.update');

    // 4. Show
    Route::get('course-content/{id}', [CourseContentController::class, 'show'])->name('course-content.show');

    // 5. Delete / Destroy
    Route::delete('course-content/{id}', [CourseContentController::class, 'destroy'])->name('course-content.destroy');

    //OTHERS
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);    
    Route::resource('entity', EntityController::class);
    Route::resource('course', CourseController::class);
    Route::resource('course-level', CourseLevelController::class);
});

require __DIR__.'/auth.php';