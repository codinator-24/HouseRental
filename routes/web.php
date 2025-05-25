<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\IndexController; // Add this line

use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\AuthAdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use Stripe\Stripe;

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
        Route::get('/sentbookings/{booking}', [BookingController::class, 'showDetailSentBooking'])->name('bookings.details.show');
        Route::patch('/sent-bookings/{booking}/update', [BookingController::class, 'updateSentBooking'])->name('bookings.sent.update')->middleware('auth');
        Route::delete('/my-bookings/sent/{booking}/delete', [BookingController::class, 'destroySentBooking'])->name('bookings.sent.destroy');
        Route::patch('/bookings/{booking}/accept', [BookingController::class, 'acceptBooking'])->name('bookings.accept');
        Route::patch('/bookings/{booking}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');

        Route::get('/notifications-data', [NotificationController::class, 'index'])->name('notifications.data');
        Route::post('/notifications/{notificationId}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

        Route::get('/agreement/{booking}/create', [AgreementController::class, 'create'])->name('agreement.create');
    });
});



//Route bo langauge
Route::get('/set/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ckb'])) {
        return redirect()->back()->cookie('lang', $lang, 60 * 24 * 365);
    }
});

//Route bo payment
Route::get('/pay', [StripeController::class, 'pay'])->name('pay');
Route::post('/checkout', [StripeController::class, 'checkout'])->name('checkout');
Route::get('/success', [StripeController::class, 'success'])->name('success');



// Admin Routes
// Admin Guest Routes (for login and registration)
// Accessible when not logged in as admin. 'guest:admin' redirects if admin is already logged in.
// Route::middleware('guest:admin')->group(function () {
Route::get('/admin/login', [AuthAdminController::class, 'showLoginForm'])->name('AdminLogin.form');
Route::post('/admin/login', [AuthAdminController::class, 'login'])->name('AdminLogin');
// });

// Authenticated Admin Routes
// These routes are protected by the 'admin.auth' middleware
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/register', [AuthAdminController::class, 'showRegistrationForm'])->name('AdminRegister.form');
    Route::post('/admin/register', [AuthAdminController::class, 'register'])->name('AdminRegister');
    Route::post('/admin/logout', [AuthAdminController::class, 'logout'])->name('AdminLogout'); // Renamed from 'logout'
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('AdminDashboard');
    Route::get('approve', [AdminController::class, 'viewaprove'])->name('aprove');
    Route::get('users', [AdminController::class, 'viewusers'])->name('users');
    Route::get('feedback', [AdminController::class, 'viewfeedback'])->name('feedback');
    Route::get('/approve-user', [AdminController::class, 'view_aprove_user'])->name('approve-user');
    Route::get('/delete-aprove/{id}', [AdminController::class, 'delete_aprove']);
    Route::get('/approve-house/{id}', [AdminController::class, 'approve_house']);
    Route::get('/delete-user/{id}', [AdminController::class, 'delete_user']);
    Route::get('/approve-user/{id}', [AdminController::class, 'approve_user']);
    Route::get('/delete-feedback/{id}', [AdminController::class, 'delete_feedback']);
   });

//Route bo pishandany data bo Admin
// Route::get('/aprove',[AdminController::class,'viewaprove']);
// Route::get('/users',[AdminController::class,'viewusers']);

//Stable

// Lerawa Route dika zia bkan
Route::post('/cash-appointment', [BookingController::class, 'scheduleCashAppointment'])->name('cash.appointment');Route::post('/cash-appointment', [BookingController::class, 'scheduleCashAppointment'])->name('cash.appointment');
Route::get('/contactUs', [DashboardController::class, 'show_contact'])->name('contact');
Route::post('/add_contact', [DashboardController::class, 'insert_contact'])->name('submit.contact');


