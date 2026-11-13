<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;

Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/lomba', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/lomba/{competition}', [CompetitionController::class, 'show'])->name('competitions.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/lomba/{competition}/favorit', [CompetitionController::class, 'toggleFavorite'])
        ->name('competitions.favorite');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');

    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/lomba/{competition}/daftar', [RegistrationController::class, 'create'])
            ->name('registrations.create');

        Route::post('/lomba/{competition}/daftar', [RegistrationController::class, 'store'])
            ->name('registrations.store');

        Route::get('/notifikasi', [NotificationController::class, 'index'])
            ->name('notifications.index');

        Route::post('/notifikasi/{notification}/read', [NotificationController::class, 'markRead'])
            ->name('notifications.read');

        Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profil/data-akun', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil/data-akun', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::middleware('role:penyelenggara')
        ->prefix('penyelenggara')
        ->name('organizer.')
        ->group(function () {

            Route::get('/', [OrganizerController::class, 'index'])->name('dashboard');
            Route::get('/lomba/baru', [OrganizerController::class, 'create'])->name('create');
            Route::post('/lomba', [OrganizerController::class, 'store'])->name('store');

            Route::get('/pembayaran/{order}', [OrganizerController::class, 'payment'])
                ->name('payment');

            Route::post('/pembayaran/{order}', [OrganizerController::class, 'submitPayment'])
                ->name('payment.submit');

            Route::get('/pembayaran/{order}/struk', [OrganizerController::class, 'receipt'])
                ->name('payment.receipt');
        });

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/', [AdminController::class, 'index'])->name('dashboard');

            Route::post('/publikasi/{order}/review', [AdminController::class, 'review'])
                ->name('publication.review');
        });
});