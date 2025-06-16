<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\Grades2Controller;
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

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store'); //Stores new teachers!
});

Route::get('/subjects/{subject}', [SubjectsController::class, 'show'])->name('subjects.show');
Route::delete('/subjects/{subject}', [SubjectsController::class, 'destroy'])->name('subjects.destroy');
Route::get('/subjects/{subject}/edit', [SubjectsController::class, 'edit'])->name('subjects.edit');
Route::get('/subjects', [SubjectsController::class, 'index'])->name('subjects.index');
Route::get('/student/create', [StudentsController::class, 'create'])->name('students.create');
Route::get('/create/subject', [SubjectsController::class, 'create'])->name('subjects.create');
Route::post('/subject', [SubjectsController::class, 'store'])->name('subjects.store');
Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
Route::get('/grades/create', [Grades2Controller::class, 'create'])->name('grades.create');
Route::post('/grades', [Grades2Controller::class, 'store'])->name('grades.store');

require __DIR__.'/auth.php';
