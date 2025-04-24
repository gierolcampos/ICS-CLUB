<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\GcashController;
use App\Http\Controllers\LetterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Member Management Routes
Route::resource('members', MemberController::class);

// Event Management Routes
Route::resource('events', EventController::class);
Route::get('calendar', [EventController::class, 'calendar'])->name('events.calendar');
Route::get('calendar/data', [EventController::class, 'calendarData'])->name('events.calendar.data');
Route::post('events/{event}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
Route::post('events/{event}/attendance', [EventController::class, 'markAttendance'])->name('events.attendance');
Route::get('events/{event}/attendees', [EventController::class, 'attendees'])->name('events.attendees');

// Announcement Routes
Route::resource('announcements', AnnouncementController::class);
Route::get('latest-announcements', [AnnouncementController::class, 'latest'])->name('announcements.latest');
Route::patch('announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');

// Payment Routes
Route::resource('payments', PaymentController::class);

// Letters Routes
Route::resource('letters', LetterController::class);

// Gcash Routes
Route::get('gcash/confirmation', [GcashController::class, 'confirmation'])->name('gcash.confirmation');
Route::get('gcash/payment', [GcashController::class, 'payment'])->name('gcash.payment');
Route::get('gcash/orders', [GcashController::class, 'orders'])->name('gcash.orders');
Route::get('gcash/receipt', [GcashController::class, 'receipt'])->name('gcash.receipt');

// About Us Route
Route::get('/about-us', [AboutController::class, 'index'])->name('about-us');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Analytics
    Route::get('/activity', [AdminController::class, 'activity'])->name('activity');
    
    // API Endpoints for Dashboard Data
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats'])->name('dashboard.stats');
        Route::get('/dashboard/activity', [AdminController::class, 'dashboardActivity'])->name('dashboard.activity');
        Route::get('/dashboard/upcoming-events', [AdminController::class, 'upcomingEvents'])->name('dashboard.events');
    });
    
    // Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

// Remove debug route in production
if (app()->environment('local')) {
    Route::get('/admin-test', function() {
        return view('admin.dashboard');
    });
}
