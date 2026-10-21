<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

// Simple web auth routes - ONLY for regular users (NOT admin)
// Admin login is handled by Filament at /admin
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\RegisterController::class, 'register'])->name('register.post');
Route::get('/password/reset', [App\Http\Controllers\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\PasswordResetConfirmController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\PasswordResetConfirmController::class, 'reset'])->name('password.update');

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// User dashboard (protected) - only regular users, not admin
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/invoice/{invoice}', [App\Http\Controllers\UserInvoiceController::class, 'view'])->name('user.invoice.view');
    Route::get('/user/invoice/{invoice}/download', [App\Http\Controllers\UserInvoiceController::class, 'download'])->name('user.invoice.download');
});

// Event browsing
Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/event/{subevent}', [App\Http\Controllers\EventController::class, 'view'])->name('event.view');

// Volunteer registration (protected - authentication required)
Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/volunteer', [App\Http\Controllers\VolunteerController::class, 'register'])->name('volunteer.register');
    Route::post('/events/{event}/volunteer', [App\Http\Controllers\VolunteerController::class, 'store'])->name('volunteer.store');
});

// User volunteer dashboard
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/my-volunteers', [App\Http\Controllers\VolunteerController::class, 'myVolunteers'])->name('volunteer.my');
});

// Organizer volunteer management
Route::middleware(['auth', 'role:organizer'])->group(function () {
    Route::get('/events/{event}/volunteers', [App\Http\Controllers\VolunteerController::class, 'index'])->name('volunteer.index');
    Route::patch('/volunteers/{volunteer}/approve', [App\Http\Controllers\VolunteerController::class, 'approve'])->name('volunteer.approve');
    Route::patch('/volunteers/{volunteer}/reject', [App\Http\Controllers\VolunteerController::class, 'reject'])->name('volunteer.reject');
});

// Tickets and invoices
Route::get('/tickets/buy/{ticket}', [App\Http\Controllers\PaymentController::class, 'buy'])->name('tickets.buy.show');
Route::middleware(['auth'])->group(function () {
    Route::post('/tickets/buy/{ticket}', [App\Http\Controllers\InvoiceController::class, 'createFromTicket'])->name('tickets.buy');
    Route::get('/invoice/{invoice}', [App\Http\Controllers\InvoiceController::class, 'view'])->name('invoice.view');
    Route::get('/invoice/{invoice}/download', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
});

// Legacy admin JSON endpoints
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/backend/json/events', [App\Http\Controllers\AdminController::class, 'eventsJson'])->name('backend.json.events');
    Route::get('/backend/json/tickets', [App\Http\Controllers\AdminController::class, 'ticketsJson'])->name('backend.json.tickets');
    Route::get('/backend/json/payments', [App\Http\Controllers\AdminController::class, 'paymentsJson'])->name('backend.json.payments');
});

// Legacy admin pages (UI)
Route::middleware(['auth','role:admin'])->prefix('backend')->name('backend.')->group(function(){
    Route::get('/', [App\Http\Controllers\AdminController::class,'index'])->name('dashboard');
    Route::get('/events', [App\Http\Controllers\AdminController::class,'eventsPage'])->name('events');
    Route::get('/tickets', [App\Http\Controllers\AdminController::class,'ticketsPage'])->name('tickets');
    Route::get('/payments', [App\Http\Controllers\AdminController::class,'paymentsPage'])->name('payments');
    Route::get('/organizers/pending', [App\Http\Controllers\AdminController::class,'pendingOrganizers'])->name('organizers.pending');
    Route::post('/organizers/{id}/approve', [App\Http\Controllers\AdminController::class,'approveOrganizer'])->name('organizers.approve');
    Route::post('/organizers/{id}/revoke', [App\Http\Controllers\AdminController::class,'revokeOrganizer'])->name('organizers.revoke');
});

if (file_exists(__DIR__ . '/organizers.php')) {
    require __DIR__ . '/organizers.php';
}
if (file_exists(__DIR__ . '/qris.php')) {
    require __DIR__ . '/qris.php';
}
