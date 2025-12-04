<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IntegrationController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::prefix('api')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/settings', [IntegrationController::class, 'getSettings']);
        Route::post('/settings', [IntegrationController::class, 'updateSettings']);
        Route::get('/reviews', [IntegrationController::class, 'getReviews']);
    });

    // Vue SPA
    Route::get('/app/{any?}', function () {
        return view('app');
    })->where('any', '.*');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
