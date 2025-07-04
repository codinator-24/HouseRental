<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\IndexController; // Add this line
use App\Http\Controllers\CurrencyController; // Add this line for currency switching

use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\AuthAdminController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController; // Corrected Namespace for Admin Reviews
use App\Http\Controllers\Admin\ConfigurationController as AdminConfigurationController; // Added for Admin Configuration
use App\Http\Controllers\FavoriteController; // Added FavoriteController
use App\Http\Controllers\UserReviewController; // Added for User Reviews
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MessageController; // Added MessageController
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController; // Added ReportController
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
        Route::post('/cash-appointment', [AgreementController::class, 'CashAppointment'])->name('cash.appointment');

        // Report a house
        Route::post('/houses/{house}/report', [ReportController::class, 'store'])->name('house.report');
        Route::post('/report/user/{reportedUser}', [ReportController::class, 'reportUser'])->name('report.user');

        // Favorite a house
        Route::post('/favorites/{house}/toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');
        Route::get('/favorites', [FavoriteController::class, 'showFavorites'])->name('favorites.show');

        // Messaging Routes
        Route::get('/messages', [MessageController::class, 'allMessagesOverview'])->name('messages.overview');
        Route::get('/agreements/{agreement}/messages', [MessageController::class, 'index'])->name('agreements.messages.index');
        Route::post('/agreements/{agreement}/messages', [MessageController::class, 'store'])->name('agreements.messages.store');

        // Inquiry Messaging Routes
        Route::get('/messages/inquiry/house/{house}/with/{otherUser}', [MessageController::class, 'showInquiryThread'])->name('messages.inquiry.thread');
        Route::post('/messages/inquiry/house/{house}/with/{otherUser}', [MessageController::class, 'storeInquiryThreadMessage'])->name('messages.inquiry.thread.store');
        // Old routes, to be removed or redirected if necessary after confirming new ones work.
        // Route::get('/houses/{house}/inquire/{inquirer_id?}', [MessageController::class, 'showInquiryForm'])->name('houses.inquiry.show');
        // Route::post('/houses/{house}/inquire', [MessageController::class, 'storeInquiryMessage'])->name('houses.inquiry.store');

        // Example (adjust middleware as needed)
        Route::post('/maintenance', [MaintenanceController::class, 'InsertMaintenance'])->name('maintenance.insert');
        Route::put('/maintenance/tenant-update/{maintenance}', [MaintenanceController::class, 'tenantUpdate'])->name('maintenance.tenant.update');
        Route::post('/maintenance/tenant-cancel/{maintenance}', [MaintenanceController::class, 'tenantCancel'])->name('maintenance.tenant.cancel');
        // The following route can be kept if there's other general processing, or removed if accept/reject cover all landlord responses.
        // Route::post('/maintenance/landlord-process-response/{maintenance}', [MaintenanceController::class, 'processLandlordResponse'])->name('maintenance.landlord.process_response');

        // New routes for maintenance acceptance payment flow
        Route::post('/maintenance/{maintenance}/initiate-payment', [MaintenanceController::class, 'initiateAcceptancePayment'])->name('maintenance.initiate_payment');
        Route::get('/maintenance/{maintenance}/checkout-stripe', [StripeController::class, 'checkoutForMaintenance'])->name('maintenance.checkout.stripe');
        Route::get('/maintenance/payment/success', [StripeController::class, 'maintenancePaymentSuccess'])->name('maintenance.payment.success');
        Route::get('/maintenance/payment/cancel', [StripeController::class, 'maintenancePaymentCancel'])->name('maintenance.payment.cancel');

        // Route for rejecting a maintenance request (no payment involved)
        Route::post('/maintenance/{maintenance}/reject', [MaintenanceController::class, 'rejectMaintenanceRequest'])->name('maintenance.reject');

        // User Review Routes
        Route::get('/dashboard/my-reviews', [UserReviewController::class, 'index'])->name('reviews.my');
        Route::get('/dashboard/bookings/{booking}/review/create', [UserReviewController::class, 'create'])->name('reviews.create');
        Route::post('/dashboard/bookings/{booking}/review', [UserReviewController::class, 'store'])->name('reviews.store');
    });

    // Currency Switcher Route - accessible by guests and authenticated users
    Route::post('/currency/switch', [CurrencyController::class, 'switch'])->name('currency.switch');
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
Route::prefix('admin')->group(function () {
    // Admin Guest Routes (for login and registration)
    // Accessible when not logged in as admin. 'guest:admin' redirects if admin is already logged in.
    // Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthAdminController::class, 'showLoginForm'])->name('AdminLogin.form');
    Route::post('/login', [AuthAdminController::class, 'login'])->name('AdminLogin');
    // });

    // Authenticated Admin Routes
    // These routes are protected by the 'admin.auth' middleware
    Route::middleware('admin.auth')->group(function () {
        Route::get('/register', [AuthAdminController::class, 'showRegistrationForm'])->name('AdminRegister.form');
        Route::post('/register', [AuthAdminController::class, 'register'])->name('AdminRegister');
        Route::post('/logout', [AuthAdminController::class, 'logout'])->name('AdminLogout'); // Renamed from 'logout'
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('AdminDashboard');
        Route::get('/approve', [AdminController::class, 'viewaprove'])->name('aprove');
        Route::get('/users', [AdminController::class, 'viewusers'])->name('users');
        Route::get('/houses', [AdminController::class, 'view_house'])->name('houses');
        Route::get('/feedback', [AdminController::class, 'viewfeedback'])->name('feedback');
        Route::get('/approve-user', [AdminController::class, 'view_aprove_user'])->name('approve-user');
        Route::post('/delete-aprove/{id}', [AdminController::class, 'delete_aprove'])->name('admin.houses.delete_aprove');
        Route::post('/approve-house/{id}', [AdminController::class, 'approve_house'])->name('admin.houses.approve');
        Route::post('/delete-user/{id}', [AdminController::class, 'delete_user'])->name('admin.users.delete');
        Route::get('/deactivate-user/{id}', [AdminController::class, 'deactivate_user']);
        Route::post('/approve-user/{id}', [AdminController::class, 'approve_user'])->name('admin.users.approve');
        Route::post('/delete-feedback/{id}', [AdminController::class, 'delete_feedback'])->name('admin.feedback.delete');
        Route::post('/deactivate-house/{id}', [AdminController::class, 'deactivate_house'])->name('admin.houses.deactivate');
        Route::get('/profits', [AdminController::class, 'ViewProfit'])->name('profit');
        Route::get('/agreements', [AdminController::class, 'ViewAgreement'])->name('agreement');
        Route::get('/payments', [AdminController::class, 'ViewPayment'])->name('payment');
        Route::post('/payments/{payment}/update-cash-details', [AdminController::class, 'updateCashPaymentDetails'])->name('admin.payments.update.cash');
        Route::post('/payments/{payment}/update-credit-landlord-details', [AdminController::class, 'updateCreditLandlordDetails'])->name('admin.payments.update.credit');

        // Admin routes for managing reports
        Route::get('/reports/{report}', [AdminController::class, 'showReportDetails'])->name('admin.reports.show');
        Route::post('/reports/{report}/status', [AdminController::class, 'updateReportStatus'])->name('admin.reports.updateStatus');

        // Admin Review Management Routes
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::patch('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

        // Admin Configuration / Developer Tools (Local Environment Only)
        if (app()->environment('local')) {
            Route::get('/configuration', [AdminConfigurationController::class, 'index'])->name('admin.configuration.index');
            Route::patch('/configuration/bookings/{booking}/age', [AdminConfigurationController::class, 'ageBooking'])->name('admin.configuration.bookings.age');
            Route::post('/configuration/currency', [AdminConfigurationController::class, 'updateCurrencyRate'])->name('admin.configuration.currency.update'); // Added route
        }

        // Admin Communications / Messages Monitoring
        Route::get('/communications', [\App\Http\Controllers\Admin\AdminCommunicationController::class, 'index'])->name('admin.communications.index');
        Route::get('/communications/inquiry/house/{house}/with/{userA}/{userB}', [\App\Http\Controllers\Admin\AdminCommunicationController::class, 'showInquiryThread'])->name('admin.communications.inquiry.show');
        Route::get('/communications/agreement/{agreement}', [\App\Http\Controllers\Admin\AdminCommunicationController::class, 'showAgreementThread'])->name('admin.communications.agreement.show');
    });
});

//Stable

// Lerawa Route dika zia bkan

Route::get('/contactUs', [DashboardController::class, 'show_contact'])->name('contact');
Route::post('/add_contact', [DashboardController::class, 'insert_contact'])->name('submit.contact');
