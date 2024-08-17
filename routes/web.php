<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StepController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TravelController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Risorsa per Travel
        Route::resource('/travels', TravelController::class)->parameters(['travels' => 'travel']);
        Route::resource('travels.steps', StepController::class)->except(['index', 'show']);

        // rotte per step
        Route::get('travels/{travel}/steps/create', [StepController::class, 'create'])->name('steps.create');
        Route::post('travels/{travel}/steps', [StepController::class, 'store'])->name('steps.store');
        Route::delete('travels/{travel}/steps/{step}', [StepController::class, 'destroy'])->name('steps.destroy');
        Route::get('travels/{travel}/steps/{step}/edit', [StepController::class, 'edit'])->name('steps.edit');
        Route::put('admin/travels/{travel}/steps/{step}', [StepController::class, 'update'])->name('steps.update');
    });

require __DIR__ . '/auth.php';
