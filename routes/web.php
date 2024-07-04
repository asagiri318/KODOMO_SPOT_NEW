<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AjaxController;
use App\Models\Prefecture;
use App\Http\Controllers\FavoriteController;

Route::post('/spots/{id}/favorite', [FavoriteController::class, 'addToFavorites'])->name('favorites.add');
Route::post('/spots/{id}/unfavorite', [FavoriteController::class, 'removeFromFavorites'])->name('favorites.remove');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // プロフィール削除用のルートを追加

    Route::post('/spots/{spot}/favorite', [SpotController::class, 'addToFavorites'])->name('spot.addToFavorites');

    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/', [UserController::class, 'index'])->name('mypage');

    Route::get('/spots/create', [SpotController::class, 'create'])->name('spot.create');
    Route::post('/spots', [SpotController::class, 'store'])->name('spot.store');
    Route::get('/spots/{id}', [SpotController::class, 'show'])->name('spot.show');

    Route::get('/spot/favorite', [SpotController::class, 'favorite'])->name('spot.favorite');

    Route::get('/mypage', [ProfileController::class, 'showMypage'])->name('mypage');

    Route::get('/spots/{id}/edit', [SpotController::class, 'edit'])->name('spot.edit');
    Route::put('/spots/{id}', [SpotController::class, 'update'])->name('spot.update');
    Route::delete('/spots/{id}', [SpotController::class, 'destroy'])->name('spot.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::post('/ajax/cities', [AjaxController::class, 'getCities'])->name('ajax.cities');
