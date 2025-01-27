<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TimeSlotController;

// Static frontend view routes
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/canadian-experience-class', 'canadian-experience-class')->name('cad-experience');
Route::view('/caregivers', 'caregivers')->name('caregivers');
Route::view('/citizenship', 'citizenship')->name('citizenship');
Route::view('/federal-skilled-trades', 'federal-skilled-trades')->name('fed-skilled-trades');
Route::view('/federal-skilled-worker', 'federal-skilled-worker')->name('fed-skilled-worker');
Route::view('/permanent-residence', 'permanent-residence')->name('perm');
Route::view('/provincial-nomination-programs', 'provincial-nomination-programs')->name('prov-nom');
Route::view('/sponsorship', 'sponsorship')->name('sponsor');
Route::view('/study-permit', 'study-permit')->name('study-permit');
Route::view('/super-visa', 'super-visa')->name('super-visa');
Route::view('/temporary-residence', 'temporary-residence')->name('temp-res');
Route::view('/visitor-visa', 'visitor-visa')->name('visitor-visa');
Route::view('/work-permit', 'work-permit')->name('work-permit');

// Consultation form routes
Route::get('/book-consultation', [ClientController::class, 'create'])->name('client.create');
Route::post('/book-consultation', [ClientController::class, 'store'])->name('client.store');

// Agreement routes
Route::get('/terms-and-conditions', [ClientController::class, 'terms'])->name('client.terms');
Route::post('/terms-and-conditions', [ClientController::class, 'post_terms'])->name('client.post-terms');

// Stripe payment routes
Route::get('/payment', [PaymentController::class, 'create'])->name('stripe.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('stripe.success');

// Appointment routes
// Note the optional parameter 'token?', this helps to check whether a client is coming from email or booking appointment immediately after payment
Route::get('/book-appointment/{token?}', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/book-appointment/{token?}', [AppointmentController::class, 'store'])->name('appointment.store');



// Backend dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::patch('/clients', [ClientController::class, 'update'])->name('client.update');
    Route::get('/clients/{uuid}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/clients', [ClientController::class, 'sendEmail'])->name('client.send-email');
    Route::delete('/clients', [ClientController::class, 'destroy'])->name('client.destroy');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar', [CalendarController::class, 'store'])->name('calendar.store');

    Route::get('/time-slots', [TimeSlotController::class, 'index'])->name('time-slots');
    Route::post('/time-slots', [TimeSlotController::class, 'store'])->name('time-slots.store');
    Route::get('/time-slots/{slot_id}', [TimeSlotController::class, 'edit'])->name('time-slots.edit');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');

});

// Backend profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function () {
    // Send email

});

require __DIR__ . '/auth.php';
