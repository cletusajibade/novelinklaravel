<?php

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ConsultationsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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

// Stripe payment routes
Route::view('/pay', 'stripe.pay')->name('pay');
Route::post('/pay', [PaymentController::class, 'pay'])->name('stripe.pay');

// Frontend consultation booking routes
Route::get('/book-consultation', [ConsultationController::class, 'create'])->name('consultation.create');
Route::post('/book-consultation', [ConsultationController::class, 'store'])->name('consultation.store');


// Backend dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/consultations', [ConsultationsController::class, 'index'])->name('consultations');
    Route::patch('/consultations', [ConsultationsController::class, 'update'])->name('consultations.update');
    Route::get('/consultations/{uuid}', [ConsultationsController::class, 'edit'])->name('consultations.edit');
    Route::post('/consultations', [ConsultationsController::class, 'sendEmail'])->name('consultations.send-email');
    Route::delete('/consultations', [ConsultationsController::class, 'destroy'])->name('consultations.destroy');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments');
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
