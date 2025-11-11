<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('v1.')
    ->middleware(['throttle:global'])
    ->group(function () {

        // Public authentication routes
        Route::controller(UserController::class)->group(function () {
            Route::post('user/register', 'register')->name('user.register');
            Route::post('user/login', 'login')->middleware(['throttle:login'])->name('user.login');
        });

        // Protected routes (require valid JWT)
        Route::middleware('auth:api')->controller(UserController::class)->group(function () {
            Route::get('user/me', 'me')->name('user.me');
            Route::post('user/logout', 'logout')->name('user.logout');
            Route::post('user/refresh', 'refresh')->name('user.refresh');
        });
    });
