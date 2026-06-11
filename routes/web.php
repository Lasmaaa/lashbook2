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

    // USER ROUTES
    Route::get('/user/index', [BookingController::class, 'index'])->name('user.index');

    Route::get('/calendar', [BookingController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/available-times', [BookingController::class, 'availableTimes'])->name('calendar.available-times');
    Route::post('/book', [BookingController::class, 'store'])->name('book.store');

    Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty');
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

    // ADMIN ROUTES
    Route::prefix('admin')->middleware('is.admin')->name('admin.')->group(function () {
        Route::get('/index', [AdminController::class, 'index'])->name('index');
        Route::get('/dashboard', fn () => redirect()->route('admin.index'))->name('dashboard');
        Route::get('/bookings/{date}', [AdminController::class, 'bookingsByDate'])->name('bookings.date');
        Route::post('/bookings/{booking}/status', [AdminController::class, 'updateStatus'])->name('bookings.status');
        Route::get('/action-panel', [AdminController::class, 'actionPanel'])->name('action-panel');
        Route::get('/loyalty', [LoyaltyController::class, 'adminIndex'])->name('loyalty');
        Route::post('/loyalty/scan', [LoyaltyController::class, 'scan'])->name('loyalty.scan');
        Route::post('/loyalty/refresh', [LoyaltyController::class, 'refresh'])->name('loyalty.refresh');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role', [AdminController::class, 'changeRole'])->name('users.role');
    });
});

Route::post('/set-language', [LanguageController::class, 'set'])->name('language.set');
Route::post('/toggle-theme', [ProfileController::class, 'toggleTheme'])->name('theme.toggle');

require __DIR__.'/auth.php';