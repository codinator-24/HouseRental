<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\IndexController; // Add this line
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;



Route::middleware('lang')->group(function () {


    Route::get('/', [IndexController::class, 'index'])->name('home');
    Route::get('/houses/details/{house}', [HouseController::class, 'houseDetails'])->name('house.details');
    //Route for Guest Users
    Route::middleware('guest')->group(function () {

        Route::view('/register', 'auth.register')->name('register');
        Route::post('/register', [AuthController::class, 'register']);

        Route::view('/login', 'auth.login')->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    //Route for Authenicated Users
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('password.update');

        Route::get('/houses/add', [HouseController::class, 'ShowAddHouse'])->name('Show.house.add');
        Route::post('/houses/add', [HouseController::class, 'AddHouse'])->name('house.add');
        Route::get('/MyHouses', [HouseController::class, 'MyHouses'])->name('my.houses');
        Route::get('/MyHouses/{house}', [HouseController::class, 'editMyHouse'])->name('Myhouse.edit');
        Route::put('/MyHouses/{house}', [HouseController::class, 'updateMyHouse'])->name('Myhouse.update');
        Route::delete('/MyHouses/{house}', [HouseController::class, 'deleteMyHouse'])->name('Myhouse.delete');
        Route::delete('/myhouse/picture/{picture}', [HouseController::class, 'destroyPicture'])->name('myhouse.picture.destroy');
        Route::post('/booking/{house}/send-message', [BookingController::class, 'sendBooking'])->name('send.booking');
        Route::get('/MyBookings', [BookingController::class, 'MyBookings'])->name('my.bookings');
        Route::get('/bookings/{booking}', [BookingController::class, 'showBooking'])->name('bookings.show');
        Route::get('/my-sent-bookings', [BookingController::class, 'showSentBookings'])->name('bookings.sent');
        // Route::get('/bookings/{booking}', [BookingController::class, 'showDetailSentBooking'])->name('bookings.details.show');
        Route::delete('/my-bookings/sent/{booking}/delete', [BookingController::class, 'destroySentBooking'])->name('bookings.sent.destroy');
        Route::patch('/bookings/{booking}/accept', [BookingController::class, 'acceptBooking'])->name('bookings.accept');
        Route::patch('/bookings/{booking}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');

            Route::get('/notifications-data', [NotificationController::class, 'index'])->name('notifications.data');
    Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });
});



//Route bo langauge
Route::get('/set/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ckb'])) {
        return redirect()->back()->cookie('lang', $lang, 60 * 24 * 365);
    }
});
