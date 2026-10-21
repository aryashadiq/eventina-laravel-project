<?php

use Illuminate\Support\Facades\Route;

Route::prefix('organizers')->name('organizers.')->group(function () {
    Route::get('/login', [App\Http\Controllers\OrganizerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\OrganizerAuthController::class, 'login'])->name('login.post');

    // public registration for organizers
    Route::get('/register', [App\Http\Controllers\OrganizerRegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\OrganizerRegisterController::class, 'register'])->name('register.post');
    // password reset for organizers
    Route::get('/password/reset', [App\Http\Controllers\OrganizerPasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\OrganizerPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\OrganizerPasswordResetConfirmController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\OrganizerPasswordResetConfirmController::class, 'reset'])->name('password.update');
    Route::middleware(['auth:organizer', 'verified'])->group(function () {
        Route::get('/', [App\Http\Controllers\OrganizerController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [App\Http\Controllers\OrganizerRegisterController::class, 'profile'])->name('profile');
        Route::post('profile', [App\Http\Controllers\OrganizerRegisterController::class, 'updateProfile'])->name('profile.update');
        Route::resource('events', App\Http\Controllers\Organizer\EventController::class);
        // Event wizard steps
        Route::get('events/wizard/start', [App\Http\Controllers\Organizer\EventWizardController::class, 'start'])->name('events.wizard.start');
        Route::post('events/wizard/step/{step}', [App\Http\Controllers\Organizer\EventWizardController::class, 'postStep'])->name('events.wizard.post');
        Route::get('events/wizard/step/{step}', [App\Http\Controllers\Organizer\EventWizardController::class, 'showStep'])->name('events.wizard.show');
        Route::post('events/wizard/discard', [App\Http\Controllers\Organizer\EventWizardController::class, 'discard'])->name('events.wizard.discard');
    });
});
