<?php

use Illuminate\Support\Facades\Route;

Route::prefix('qris')->name('qris.')->group(function () {
    Route::post('/create', [App\Http\Controllers\PaymentController::class, 'createQris'])->middleware('auth')->name('create');});

// Pakasir payment callback - accepts GET redirect from Pakasir and POST webhook
Route::match(['get', 'post'], '/payment/callback', [App\Http\Controllers\PaymentController::class, 'pakasirWebhook']);
