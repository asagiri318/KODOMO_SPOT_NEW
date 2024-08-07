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
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SpotPhotoController;
use App\Http\Controllers\LikeController;

// スポット関連
Route::controller(SpotController::class)->group(function () {
    Route::get('/shared', 'shared')->name('shared');
    Route::post('{id}/unfavorite', [FavoriteController::class, 'removeFromFavorites'])->name('favorites.remove');

    Route::prefix('spots')->name('spot.')->group(function () {
        Route::get('/shared-spots', 'shared')->name('shared');
        Route::post('{id}/favorite', 'addToFavorites')->name('addToFavorites');
        Route::get('/favorite', 'favorite')->name('favorite');
    });

    // 認証が必要なスポット関連
    Route::middleware('auth')->prefix('spots')->name('spot.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{id}', 'show')->name('show');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}/update', 'update')->name('update');
        Route::delete('{id}/delete', 'destroy')->name('destroy');
    });
});

// お気に入り関連
Route::prefix('favorites')->name('favorites.')->controller(FavoriteController::class)->group(function () {
    Route::get('/', 'index')->name('index');
});

// いいね機能関連
Route::prefix('spots')->name('spot.')->controller(LikeController::class)->group(function () {
    Route::post('{spot}/like', 'toggleLike')->name('like');
    Route::get('{spot}/like-count', 'likeCount')->name('like-count');
});

// スポット写真関連
Route::prefix('spotphotos')->name('spotphotos.')->controller(SpotPhotoController::class)->group(function () {
    Route::delete('{photo}', 'destroy')->name('destroy');
    Route::post('/', 'store')->name('store');
});

// ユーザープロフィール関連
Route::prefix('profile')->middleware('auth')->name('profile.')->controller(ProfileController::class)->group(function () {
    Route::get('/edit', 'edit')->name('edit');
    Route::patch('/update', 'update')->name('update');
    Route::delete('/', 'destroy')->name('destroy');
    Route::delete('/photo', 'deletePhoto')->name('deletePhoto');
});

// ユーザー関連
Route::middleware('auth')->controller(UserController::class)->group(function () {
    Route::get('/', 'index')->name('mypage');
    Route::get('/mypage', 'index')->name('mypage');
    Route::get('/user/{id}', 'profile')->name('user.profile');
});

// 認証関連
Route::prefix('auth')->group(function () {
    Route::controller(EmailVerificationPromptController::class)->group(function () {
        Route::get('/verify-email', '__invoke')->name('verification.notice');
    });

    Route::controller(VerifyEmailController::class)->group(function () {
        Route::get('/verify-email/{id}/{hash}', '__invoke')->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    });

    Route::controller(EmailVerificationNotificationController::class)->group(function () {
        Route::post('/email/verification-notification', 'store')->middleware('throttle:6,1')->name('verification.send');
    });

    Route::controller(ConfirmablePasswordController::class)->group(function () {
        Route::get('/confirm-password', 'show')->name('password.confirm');
        Route::post('/confirm-password', 'store');
    });

    Route::controller(PasswordController::class)->group(function () {
        Route::put('/password', 'update')->name('password.update');
    });

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::post('/logout', 'destroy')->name('logout');
    });
});

// ゲストユーザー用
Route::middleware('guest')->group(function () {
    Route::controller(RegisteredUserController::class)->group(function () {
        Route::get('/register', 'create')->name('register');
        Route::post('/register', 'store');
    });

    Route::controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('/login', 'create')->name('guest.login'); // 変更しました
        Route::post('/login', 'store');
    });

    Route::controller(PasswordResetLinkController::class)->group(function () {
        Route::get('/forgot-password', 'create')->name('password.request');
        Route::post('/forgot-password', 'store')->name('password.email');
    });

    Route::controller(NewPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'create')->name('password.reset');
        Route::post('/reset-password', 'store')->name('password.update');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    });
});


// Ajax
Route::prefix('ajax')->name('ajax.')->controller(AjaxController::class)->group(function () {
    Route::post('/cities', 'getCities')->name('cities');
});
