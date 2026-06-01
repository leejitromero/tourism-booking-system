<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TourPackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/packages'));

Route::get('/dashboard', function () {
    if (auth()->user()?->is_admin) return redirect()->route('admin.dashboard');
    return redirect('/packages');
})->middleware(['auth'])->name('dashboard');

Route::resource('packages', TourPackageController::class)->only(['index','show']);

Route::middleware(['auth'])->group(function () {
    Route::resource('bookings', BookingController::class)->only(['index','create','store','show','destroy']);
    Route::post('/bookings/{booking}/review', [BookingController::class, 'review'])->name('bookings.review');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::patch('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [AdminController::class, 'export'])->name('reports.export');
    Route::post('/packages/import', [AdminController::class, 'import'])->name('packages.import');
    Route::get('/packages/import/sample', [AdminController::class, 'sampleImport'])->name('packages.import.sample');

    Route::get('/packages/manage', [TourPackageController::class, 'manage'])->name('packages.manage');

    Route::resource('packages', TourPackageController::class)->except(['index','show']);
});

require __DIR__.'/auth.php';
