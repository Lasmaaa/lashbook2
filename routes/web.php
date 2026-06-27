<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.index')
            : redirect()->route('user.index');
    }

    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.index')
            : redirect()->route('user.index');
    })->name('dashboard');

    Route::get('/user/index', [BookingController::class, 'index'])->name('user.index');

    Route::get('/calendar', [BookingController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/schedule', [BookingController::class, 'scheduleForDate'])->name('calendar.schedule');
    Route::get('/calendar/available-times', [BookingController::class, 'availableTimes'])->name('calendar.available-times');
    Route::post('/book', [BookingController::class, 'store'])->name('book.store');

    Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty');
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    Route::view('/terms', 'pages.terms')->name('terms');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->middleware('is.admin')->name('admin.')->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', fn () => redirect()->route('admin.action-panel'))->name('dashboard');
        Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{date}', [AdminController::class, 'bookingsByDate'])->name('bookings.date');
        Route::post('/bookings/{booking}/status', [AdminController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/procedures', [AdminController::class, 'procedures'])->name('procedures');
        Route::get('/procedures/{date}', [AdminController::class, 'proceduresForDate'])->name('procedures.date');
        Route::post('/procedures/{date}', [AdminController::class, 'saveProceduresForDate'])->name('procedures.save');
        Route::post('/procedures/{date}/apply-all', [AdminController::class, 'applyProceduresToAll'])->name('procedures.apply-all');
        Route::get('/action-panel', [AdminController::class, 'actionPanel'])->name('action-panel');
        Route::get('/loyalty', [LoyaltyController::class, 'adminIndex'])->name('loyalty');
        Route::post('/loyalty/scan', [LoyaltyController::class, 'scan'])->name('loyalty.scan');
        Route::post('/loyalty/refresh', [LoyaltyController::class, 'refresh'])->name('loyalty.refresh');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.role');
    });
});

Route::get('/health', fn () => response('ok', 200)->header('Content-Type', 'text/plain'));

Route::post('/set-language', [LanguageController::class, 'set'])->name('language.set');
Route::post('/toggle-theme', [ProfileController::class, 'toggleTheme'])->name('theme.toggle');

require __DIR__.'/auth.php';
