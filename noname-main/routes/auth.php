<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/app/register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('/app/register', [RegisteredUserController::class, 'store']);

    Route::get('/app/login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('/app/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/app/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('/app/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('/app/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('/app/reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/app/verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('/app/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('/app/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('/app/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('/app/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/app/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/app/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::get('/game/logout.aspx', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout.aspx');
});
