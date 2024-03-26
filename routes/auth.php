<?php

use App\Http\Middleware\SetLocale;
use App\Livewire\Auth\EmailVerification;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Profile;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:web', SetLocale::class])->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');

    if (config('services.should_verify_email')) {
        Route::middleware(['auth'])->group(function () {
            Route::get('/verify-email', EmailVerification::class)->name('verification.notice');
            Route::get('/verify-email/{id}/{hash}', [EmailVerification::class, 'verifyEmail'])->name('verification.verify');
            Route::post('/verify-email/send-notification', [EmailVerification::class, 'sendVerificationEmail'])->name('verification.send');
        });
    }

    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', Profile::class)->name('profile');
    });
});
