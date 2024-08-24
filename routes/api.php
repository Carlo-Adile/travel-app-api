<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TravelController;
use App\Http\Controllers\API\StepController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotte di autenticazione
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');

// Rotte protette da autenticazione
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rotte per il profilo utente
    Route::put('/profile', [ProfileController::class, 'update'])->name('api.profile.update');

    // Rotta personalizzata per visualizzare un Travel tramite slug
    Route::get('/travels/{id}', [TravelController::class, 'show'])->name('api.travels.show');

    // Rotte per Travel (CRUD)
    Route::apiResource('travels', TravelController::class)->except(['show']);

    // Rotte per Step (CRUD) come sotto-risorsa di Travel
    Route::apiResource('travels.steps', StepController::class)->shallow();
});
