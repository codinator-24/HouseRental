<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\IndexController; // Add this line
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
        Route::post('/booking/{house}/send-message', [HouseController::class, 'sendBooking'])->name('send.booking');
        Route::get('/MyBookings', [HouseController::class, 'MyBookings'])->name('my.bookings');
        Route::get('/bookings/{booking}', [HouseController::class, 'showBooking'])->name('bookings.show');
        // Example route definition (you'll need to create the controller and method)
        Route::get('/my-sent-bookings', [HouseController::class, 'showSentBookings'])->name('bookings.sent');
        // Route::get('/bookings/{booking}', [HouseController::class, 'showDetailSentBooking'])->name('bookings.details.show');
    });
});



//Route bo langauge
Route::get('/set/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ckb'])) {
        return redirect()->back()->cookie('lang', $lang, 60 * 24 * 365);
    }
});
