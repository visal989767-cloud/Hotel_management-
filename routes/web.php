<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'home'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [DashboardController::class, 'settingsUpdate'])->name('settings.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('room-types', RoomTypeController::class)
        ->parameters(['room-types' => 'room_type'])
        ->except('show');
    Route::resource('rooms', RoomController::class)
        ->parameters(['rooms' => 'room'])
        ->except('show');
    Route::resource('guests', GuestController::class)
        ->parameters(['guests' => 'guest'])
        ->except('show');
    Route::resource('bookings', BookingController::class)
        ->parameters(['bookings' => 'booking'])
        ->except('show');
});
