<?php

use App\Http\Controllers\EntityController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseLevelController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\CourseQuizController;
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

    //COURSE QUIZ
    Route::post('course-quiz/{courseQuiz}/questions', [CourseQuizController::class, 'storeQuestion'])->name('course-quiz.questions.store');
    Route::delete('course-quiz/{courseQuiz}/questions/{questionId}', [CourseQuizController::class, 'destroyQuestion'])->name('course-quiz.questions.destroy');
    
    Route::post('course-quiz/{quizId}/submit', [CourseQuizController::class, 'submitQuiz'])->name('course-quiz.submit');
    
    Route::get('course-quiz/{courseQuiz}/results', [CourseQuizController::class, 'showResults'])->name('course-quiz.results');
    Route::get('course-content/{courseContentId}/quiz/create', [CourseQuizController::class, 'create'])->name('quiz.create');
    
    //OTHERS
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class); 
    Route::resource('permissions', PermissionController::class);
    Route::resource('entity', EntityController::class);
    Route::resource('course', CourseController::class);
    Route::resource('course-level', CourseLevelController::class);
    Route::resource('activity', ActivityController::class);
    Route::resource('schedule', ScheduleController::class);
    Route::resource('course-student', CourseStudentController::class);
    Route::resource('course-quiz', CourseQuizController::class);
});

require __DIR__.'/auth.php';