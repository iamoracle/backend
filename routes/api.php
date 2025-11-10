<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')
    ->as('v1.')
    ->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('user/register', 'register')->name('user.register');
        });
    });
