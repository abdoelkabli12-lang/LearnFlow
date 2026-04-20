<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Models\Course;

Route::get('/', function () {
    $courses = Course::published()
        ->with(['category', 'user', 'reviews'])
        ->oldest()
        ->take(3)
        ->get();

    return view('welcome', compact('courses'));
})->name('home');

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.attempt');

Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::get('/change-password', [UserController::class, 'showChangePassword'])->name('password.show');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('password.update');

    Route::get('/admin/dashboard', [UserController::class, 'dashboard'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/host/dashboard', [UserController::class, 'dashboard'])
        ->middleware('role:host')
        ->name('host.dashboard');

    Route::get('/student/dashboard', [UserController::class, 'dashboard'])
        ->middleware('role:student')
        ->name('student.dashboard');

    Route::get('/visitor/dashboard', [UserController::class, 'dashboard'])
        ->middleware('role:visitor')
        ->name('visitor.dashboard');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::patch('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/{course}', [CourseController::class, 'show'])->name('show');
});

Route::middleware(['auth', 'role:host,admin'])->prefix('host/courses')->name('host.courses.')->group(function () {
    Route::get('/', [CourseController::class, 'hostIndex'])->name('index');
    Route::get('/create', [CourseController::class, 'create'])->name('create');
    Route::post('/', [CourseController::class, 'store'])->name('store');
    Route::get('/{course}', [CourseController::class, 'show'])->name('show');
    Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::patch('/{course}', [CourseController::class, 'update'])->name('update');
    Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
    Route::patch('/{course}/publish', [CourseController::class, 'togglePublish'])->name('publish');
});

Route::middleware(['auth', 'role:host,admin'])
    ->prefix('host/courses/{course}/modules')
    ->name('host.courses.modules.')
    ->scopeBindings()
    ->group(function () {
        Route::post('/', [ModuleController::class, 'store'])->name('store');
        Route::patch('/reorder', [ModuleController::class, 'reorder'])->name('reorder');
        Route::patch('/{module}', [ModuleController::class, 'update'])->name('update');
        Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth', 'role:host'])->prefix('host')->name('host.')->group(function () {
    // Lessons
    Route::post('/modules/{module}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
    Route::post('/modules/{module}/lessons/reorder', [LessonController::class, 'reorder'])->name('lessons.reorder');
});

// Lesson show is outside the host group — accessible by enrolled abonnés too
Route::middleware('auth')->get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');


Route::middleware(['auth'])->group(function () {
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('/enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::patch('/enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::patch('/enrollments/{enrollment}/cancel', [EnrollmentController::class, 'cancel'])->name('enrollments.cancel');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/courses/{course}/checkout', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/courses/{course}/checkout', [PaymentController::class, 'store'])->name('payment.store');
});


Route::middleware('auth')->group(function () {
    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
