<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SpotPhotoController;
use App\Http\Controllers\LikeController;

Route::post('/spots/{spot}/like', [LikeController::class, 'toggleLike'])->name('spot.like');
Route::get('/spots/{spot}/like-count', [LikeController::class, 'likeCount'])->name('spot.like-count');
Route::post('/spots/{spotId}/like', [LikeController::class, 'toggleLike'])->name('spot.toggleLike');
Route::get('/spots/{spotId}/like-count', [LikeController::class, 'likeCount'])->name('spot.likeCount');

Route::delete('/spot-photos/{id}', [SpotPhotoController::class, 'destroy'])->name('spot-photos.destroy');

Route::get('/shared-spots', [SpotController::class, 'shared'])->name('spot.shared');
Route::get('/shared', [SpotController::class, 'shared'])->name('shared');

Route::post('/spots/{id}/favorite', [SpotController::class, 'addToFavorites'])->name('spot.addToFavorites');
Route::post('/spots/{id}/unfavorite', [FavoriteController::class, 'removeFromFavorites'])->name('favorites.remove');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');

    Route::get('/spots/create', [SpotController::class, 'create'])->name('spot.create');
    Route::post('/spots', [SpotController::class, 'store'])->name('spot.store');
    Route::get('/spots/{id}', [SpotController::class, 'show'])->name('spot.show');
    Route::get('/spots/{id}/edit', [SpotController::class, 'edit'])->name('spot.edit');
    Route::put('/spots/{id}/update', [SpotController::class, 'update'])->name('spot.update');
    Route::delete('/spots/{id}/delete', [SpotController::class, 'destroy'])->name('spot.destroy');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/', [UserController::class, 'index'])->name('mypage');
    Route::get('/spot/favorite', [SpotController::class, 'favorite'])->name('spot.favorite');
    Route::get('/mypage', [UserController::class, 'index'])->name('mypage'); // UserController の index メソッドを使用する

    Route::get('/user/{id}', [UserController::class, 'profile'])->name('user.profile');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
});

Route::post('/ajax/cities', [AjaxController::class, 'getCities'])->name('ajax.cities');
