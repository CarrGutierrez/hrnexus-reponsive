<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Overview Routes
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('calendar', function () {
    return Inertia::render('Calendar');
})->name('calendar');

Route::get('messages', function () {
    return Inertia::render('Messages');
})->name('messages');

// Management Routes
Route::get('employees', function () {
    return Inertia::render('Employees');
})->name('employees');

Route::get('employees/{id}', function ($id) {
    return Inertia::render('EmployeeProfile', ['id' => $id]);
})->name('employees.profile');

Route::get('attendance', function () {
    return Inertia::render('Attendance');
})->name('attendance');

Route::get('leaves', function () {
    return Inertia::render('Leaves');
})->name('leaves');

Route::get('configuration', function () {
    return Inertia::render('Configuration');
})->name('configuration');

// Documents Routes
Route::get('documents', function () {
    return Inertia::render('Documents/Browse');
})->name('documents.browse');

Route::get('documents/upload', function () {
    return Inertia::render('Documents/Upload');
})->name('documents.upload');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
