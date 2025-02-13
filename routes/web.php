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
Route::get('/consultation/create', [ClientController::class, 'create'])->name('client.create');
Route::post('/consultation/create', [ClientController::class, 'store'])->name('client.store');

// Agreement routes
Route::get('/consultation/terms-and-conditions', [ClientController::class, 'terms'])->name('client.terms');
Route::post('/consultation/terms-and-conditions', [ClientController::class, 'post_terms'])->name('client.post-terms');

// Stripe payment routes
Route::get('/payment', [PaymentController::class, 'create'])->name('stripe.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('stripe.success');
Route::get('/payment/info/{token?}/{appointment_uuid?}', [PaymentController::class, 'pendingOrConfirmedAppointment'])->name('stripe.info.pending-or-confirmed-appointment');
Route::get('/payment/info/confirmed', [PaymentController::class, 'confirmedPayment'])->name('stripe.info.confirmed-payment');

// Appointment routes. Note the optional parameter 'token?',
// this helps to check whether a client is coming from email or booking appointment immediately after payment
Route::get('/book-appointment/{token?}/{payment_uuid?}', [AppointmentController::class, 'create'])->name('appointment.create');
Route::post('/book-appointment/{token?}/{payment_uuid?}', [AppointmentController::class, 'store'])->name('appointment.store');

Route::get('/appointment/{appointment_uuid?}/reschedule', [AppointmentController::class, 'showRescheduleCalendar'])->name('appointment.show-reschedule-calendar');
Route::post('/appointment/{appointment_uuid?}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointment.reschedule');

Route::get('/appointment/{appointment_uuid?}/cancel', [AppointmentController::class, 'showCancelForm'])->name('appointment.cancel');
Route::post('/appointment/{appointment_uuid?}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');

Route::get('/appointment/completed', [AppointmentController::class, 'completed'])->name('appointment.completed');

Route::get('/appointment/no-account', [AppointmentController::class, 'noAccountOrAppointment'])->name('appointment.no-account');

// Backend dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients');
    Route::get('/client/{uuid}', [ClientController::class, 'show'])->name('client.show');
    Route::get('/client/{uuid}', [ClientController::class, 'edit'])->name('client.edit');
    Route::patch('/client', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/client', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::post('/clients', [ClientController::class, 'sendEmail'])->name('client.send-email');

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
