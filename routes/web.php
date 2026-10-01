<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::controller(App\Http\Controllers\UserController::class)->prefix('user')->group(function () {

    Route::match(['get', 'post'], '/login', 'index')->name('user.login');

    Route::match(['get', 'post'], '/otp', 'OtpVerification')->name('user.otp');

    Route::match(['get', 'post'], '/logout', 'logout')->name('user.logout');

});


/*
|--------------------------------------------------------------------------
| Bus Routes - eTravos Integration
|--------------------------------------------------------------------------
*/

Route::controller(App\Http\Controllers\BusController::class)
    ->prefix('bus')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Bus Listing
        |--------------------------------------------------------------------------
        */

        Route::get('/list', 'index')->name('bus.list');


        /*
        |--------------------------------------------------------------------------
        | Bus Search
        |--------------------------------------------------------------------------
        */

        Route::post('/search', 'searchBuses')->name('bus.search');


        /*
        |--------------------------------------------------------------------------
        | Bus Details
        |--------------------------------------------------------------------------
        */

        Route::get('/details/{busId}', 'getBusDetails')
            ->name('bus.details');


        /*
        |--------------------------------------------------------------------------
        | Search Locations
        |--------------------------------------------------------------------------
        */

        Route::post('/search-locations', 'searchLocations')
            ->name('bus.search-locations');


        /*
        |--------------------------------------------------------------------------
        | Seat Selection Page
        |--------------------------------------------------------------------------
        */

        Route::get('/seats/{busId}', 'showSeats')
            ->name('bus.seats.page');


        /*
        |--------------------------------------------------------------------------
        | eTravos Seat Layout
        |--------------------------------------------------------------------------
        */

        Route::post('/seat-layout', 'getSeatLayout')
            ->name('bus.seat-layout');

        Route::post('/block', 'blockTicket')->name('bus.block');
        /*
        |--------------------------------------------------------------------------
        | Old Seat API Route
        | Keep for existing frontend compatibility
        |--------------------------------------------------------------------------
        */

        Route::post('/seats', 'getSeatLayout')
            ->name('bus.seats');


        /*
        |--------------------------------------------------------------------------
        | Booking Confirmation Page
        |--------------------------------------------------------------------------
        */

        Route::get('/booking/confirm', 'bookingConfirm')
            ->name('bus.booking.confirm');

/*
|--------------------------------------------------------------------------
| Razorpay Payment Page
|--------------------------------------------------------------------------
*/

Route::get('/payment', 'paymentPage')
    ->name('bus.payment');
    Route::get('/ticket-page', 'ticketPage')
    ->name('bus.ticket.page');
    Route::post('/razorpay/order', 'createRazorpayOrder')
    ->name('bus.razorpay.order');

Route::post('/razorpay/verify', 'verifyRazorpayPayment')
    ->name('bus.razorpay.verify');
        /*
        |--------------------------------------------------------------------------
        | Bus Booking
        |--------------------------------------------------------------------------
        */

        Route::post('/book', 'bookTicket')
            ->name('bus.book');

/*
|--------------------------------------------------------------------------
| Ticket Details
|--------------------------------------------------------------------------
*/

Route::post('/ticket', 'getTicketDetails')
    ->name('bus.ticket');

        /*
        |--------------------------------------------------------------------------
        | Old Confirmation Route
        | Kept for existing functionality
        |--------------------------------------------------------------------------
        */

        Route::post('/confirm', 'confirmBooking')
            ->name('bus.confirm');


        /*
        |--------------------------------------------------------------------------
        | Booking Details
        |--------------------------------------------------------------------------
        */

        Route::get('/booking/{bookingId}', 'getBookingDetails')
            ->name('bus.booking');


        /*
        |--------------------------------------------------------------------------
        | Cancel Booking
        |--------------------------------------------------------------------------
        */

        Route::post('/cancel', 'cancelBooking')
            ->name('bus.cancel');

});


/*
|--------------------------------------------------------------------------
| Bus Cities
|--------------------------------------------------------------------------
*/

Route::get('/bus/cities', [App\Http\Controllers\BusController::class, 'getCities'])
    ->name('bus.cities');


/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['customer_id'])->group(function () {

    Route::controller(App\Http\Controllers\CustomerController::class)
        ->prefix('user/dashboard')
        ->group(function () {

            Route::match(['get', 'post'], '/', 'index')
                ->name('user.dashboard');

            Route::match(['get', 'post'], '/update-profile', 'UpdateProfile')
                ->name('user.update.profile');

            Route::match(['get', 'post'], '/booking-history', 'BookingHistory')
                ->name('user.bookings');

            Route::match(['get', 'post'], '/refer-and-earn', 'Refer')
                ->name('user.refer');

            Route::match(['get', 'post'], '/notifications', 'Notifications')
                ->name('user.notifications');

            Route::match(['get', 'post'], '/support', 'support')
                ->name('user.support');

            Route::match(['get', 'post'], '/support/create', 'createTicket')
                ->name('user.ticket.create');

            Route::match(['get', 'post'], '/support/store', 'storeTicket')
                ->name('user.ticket.store');

            Route::match(['get', 'post'], '/support/view/{id}', 'viewTicket')
                ->name('user.ticket.view');

            Route::match(['get', 'post'], '/support/reply/{id}', 'replyTicket')
                ->name('user.ticket.reply');

            Route::match(['get', 'post'], '/payment-history', 'PaymentHistory')
                ->name('user.payment');

            Route::match(
                ['get', 'post'],
                '/user/payment-history',
                [App\Http\Controllers\CustomerController::class, 'PaymentHistory']
            )->name('user.payment.history');

            Route::match(['get', 'post'], '/wallet-history', 'WalletHistory')
                ->name('user.wallet');

            Route::match(['get', 'post'], '/customer-flight-booking', 'FlightsBooking')
                ->name('user.flight.booking');

            Route::match(['get', 'post'], '/customer-hotel-booking', 'HotelBooking')
                ->name('user.hotel.booking');

            Route::match(['get', 'post'], '/customer-car-booking', 'CarBooking')
                ->name('user.car.booking');

            Route::match(['get', 'post'], '/customer-cruise-booking', 'CruiseBooking')
                ->name('user.cruise.booking');

            Route::match(['get', 'post'], '/customer-tour-booking', 'TourBooking')
                ->name('user.tour.booking');

            Route::match(['get', 'post'], '/customer-bus-booking', 'BusBooking')
                ->name('user.bus.booking');

            Route::post('/booking/store', 'store')
                ->name('booking.store');
        });

});


/*
|--------------------------------------------------------------------------
| Flight / Hotel / Other Listing Routes
|--------------------------------------------------------------------------
*/

Route::match(
    ['get', 'post'],
    '/flight-list',
    [App\Http\Controllers\FlightController::class, 'index']
)->name('flight.list');

Route::match(
    ['get', 'post'],
    '/hotel-list',
    [App\Http\Controllers\HotelController::class, 'index']
)->name('hotel.list');


/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
*/

Route::match(
    ['get', 'post'],
    '/',
    [App\Http\Controllers\WebController::class, 'index']
)->name('home');

Route::get(
    '/blog-details/{slug}',
    [App\Http\Controllers\WebController::class, 'blog_details']
)->name('blog_details');

Route::get(
    '/about-us',
    [App\Http\Controllers\WebController::class, 'about']
)->name('about');

Route::match(
    ['get', 'post'],
    '/contact-us',
    [App\Http\Controllers\WebController::class, 'contact']
)->name('contact');

Route::get(
    '/blog',
    [App\Http\Controllers\WebController::class, 'blog']
)->name('blog');

Route::get(
    '/blog/data',
    [App\Http\Controllers\WebController::class, 'getBlogData']
)->name('blog.data');


/*
|--------------------------------------------------------------------------
| Tour Routes
|--------------------------------------------------------------------------
*/

// Route::get('/fixed-departures/{tour_slug?}', [App\Http\Controllers\WebController::class, 'fixed_departures'])->name('fixed.departures');

Route::match(
    ['get', 'post'],
    '/corporate-tours',
    [App\Http\Controllers\WebController::class, 'CorporateTours']
)->name('corporate.tours');

Route::get(
    '/school-excursion',
    [App\Http\Controllers\WebController::class, 'SchoolTours']
)->name('school.tours');

Route::post(
    '/store-school-excursion',
    [App\Http\Controllers\WebController::class, 'store']
)->name('school-excursion.store');

Route::get(
    '/school-excursion/thankyou',
    [App\Http\Controllers\WebController::class, 'SchoolThankyou']
)->name('school-excursion.thankyou');

Route::get(
    '/college-excursion',
    [App\Http\Controllers\WebController::class, 'CollegeTours']
)->name('college.tours');

Route::post(
    '/apply-coupon',
    [App\Http\Controllers\WebController::class, 'applyCouponAjax']
)->name('apply.coupon.ajax');

Route::get(
    '/group-tours',
    [App\Http\Controllers\WebController::class, 'GroupTours']
)->name('group.tours');

Route::get(
    '/gallery',
    [App\Http\Controllers\WebController::class, 'gallery']
)->name('gallery');

Route::get(
    '/gallery/data',
    [App\Http\Controllers\WebController::class, 'getGalleryData']
)->name('gallery.data');

Route::get(
    '/domestic-tour/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'tour']
)->name('tour');

Route::get(
    '/international-tour/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'international_tour']
)->name('international.tour');

Route::get(
    '/honeymoon-tour/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'honeymoon_tour']
)->name('honeymoon.tour');

Route::get(
    '/cruise-tour/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'tour']
)->name('cruise.tour');

Route::get(
    '/domestic-tours-list',
    [App\Http\Controllers\WebController::class, 'tours_list']
)->name('tours.list');

Route::get(
    '/packages/ajax',
    [App\Http\Controllers\WebController::class, 'getPackagesAjax']
)->name('packages.ajax');

Route::get(
    '/international-tours-list',
    [App\Http\Controllers\WebController::class, 'international_tours_list']
)->name('international.tours.list');

Route::get(
    '/international/packages/ajax',
    [App\Http\Controllers\WebController::class, 'getInternationalPackagesAjax']
)->name('international.packages.ajax');

Route::get(
    '/domestic-tour-detail/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'tour_detail']
)->name('domestic.tour.detail');

Route::get(
    '/international-tour-detail/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'international_tour_detail']
)->name('international.tour.detail');

Route::get(
    '/honeymoon-tour-detail/{tour_slug?}',
    [App\Http\Controllers\WebController::class, 'honeymoon_tour_detail']
)->name('honeymoon.tour.detail');

Route::get(
    '/privacy-policy',
    [App\Http\Controllers\WebController::class, 'privacy_policy']
)->name('privacy_policy');

Route::get(
    '/term-conditions',
    [App\Http\Controllers\WebController::class, 'term_conditions']
)->name('term_conditions');


/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

Route::match(
    ['get', 'post'],
    '/change-date',
    [App\Http\Controllers\WebController::class, 'changeDate']
)->name('change.date');

Route::match(
    ['get', 'post'],
    '/get-price',
    [App\Http\Controllers\WebController::class, 'getPrice']
)->name('get.price');

Route::match(
    ['get', 'post'],
    '/pay-submit',
    [App\Http\Controllers\WebController::class, 'PaySubmit']
)->name('pay.submit');

Route::match(
    ['get', 'post'],
    '/update-pay-submit',
    [App\Http\Controllers\WebController::class, 'UpdatePaySubmit']
)->name('update.pay.submit');

Route::match(
    ['get', 'post'],
    '/submit-info',
    [App\Http\Controllers\WebController::class, 'SubmitInfo']
)->name('submit.info');

Route::match(
    ['get', 'post'],
    '/international-pay-submit',
    [App\Http\Controllers\WebController::class, 'InternationalPaySubmit']
)->name('international.pay.submit');

Route::match(
    ['get', 'post'],
    '/international-update-pay-submit',
    [App\Http\Controllers\WebController::class, 'InternationalUpdatePaySubmit']
)->name('international.update.pay.submit');

Route::match(
    ['get', 'post'],
    '/international-submit-info',
    [App\Http\Controllers\WebController::class, 'InternationalSubmitInfo']
)->name('international.submit.info');


/*
|--------------------------------------------------------------------------
| State / City
|--------------------------------------------------------------------------
*/

Route::match(
    ['get', 'post'],
    '/get-state-name',
    [App\Http\Controllers\StateController::class, 'GetStateName']
)->name('get.state.name');

Route::match(
    ['get', 'post'],
    '/get-city-name',
    [App\Http\Controllers\CityController::class, 'GetCityName']
)->name('get.city.name');

// Route::match(['get','post'],'/apply-coupon-ajax', [App\Http\Controllers\CityController::class, 'applyCouponAjax'])->name('apply.coupon.ajax');

Route::get(
    '/thank-you/{booking_id}',
    [App\Http\Controllers\WebController::class, 'ThankYou']
)->name('thank.you');


/*
|--------------------------------------------------------------------------
| Fixed Departures
|--------------------------------------------------------------------------
*/

Route::get(
    '/fixed-departures/domestic/{slug}',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'main_fixed_departures']
)->name('fixed.departures.india');

Route::get(
    '/fixed-departures/international/{slug}',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'main_fixed_departures']
)->name('fixed.departures.international');

Route::get(
    '/fixed-departures-details/{slug}',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'fixed_departures']
)->name('fixed.departures.details');

Route::get(
    '/fixed-departures-itinerary-details/{country?}/{slug}',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'fixed_departures_itinerary']
)->name('fixed.departures.itinerary');

Route::post(
    '/send-otp',
    [App\Http\Controllers\FixedItineraryController::class, 'sendOTP']
)->name('send.otp');

Route::post(
    '/verify-otp',
    [App\Http\Controllers\FixedItineraryController::class, 'verifyOTP']
)->name('verify.otp');

Route::post(
    '/verify-otp-download',
    [App\Http\Controllers\FixedItineraryController::class, 'verifyOTPAndDownload']
)->name('verify.otp.download');

Route::get(
    '/download-itinerary/{token}',
    [App\Http\Controllers\FixedItineraryController::class, 'downloadItinerary']
)->name('download.itinerary');

Route::post(
    '/booking/date-info',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'getDateInfo']
)->name('booking.date-info');

Route::post(
    '/booking/create',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'createBooking']
)->name('booking.create');

Route::post(
    '/booking/verify',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'verifyPayment']
)->name('booking.verify');

Route::get(
    '/booking/success/{bookingNumber?}',
    [App\Http\Controllers\WebFixedDeparturesController::class, 'bookingSuccess']
)->name('booking.success');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Route::get('/', function() { return redirect()->route('login');});

Route::get('/login', function () {
    return redirect('auth.login');
})->name('login');

Route::get(
    'forget-password',
    [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgetPasswordForm']
)->name('forget.password.get');

Route::post(
    'forget-password',
    [App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitForgetPasswordForm']
)->name('forget.password.post');

Route::get(
    'reset-password/{token}',
    [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm']
)->name('reset.password.get');

Route::post(
    'reset-password',
    [App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitResetPasswordForm']
)->name('reset.password.post');

Auth::routes();

Route::get(
    '/logout',
    [App\Http\Controllers\Auth\LoginController::class, 'logout']
)->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [App\Http\Controllers\HomeController::class, 'index']
    )->name('dashboard');

    Route::match(
        ['get', 'post'],
        '/update-profile',
        [App\Http\Controllers\HomeController::class, 'profile']
    )->name('update.profile');

    Route::match(
        ['get', 'post'],
        '/update-change-passward',
        [App\Http\Controllers\HomeController::class, 'ChangePassward']
    )->name('update.change.passward');


    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\SettingsController::class)
        ->prefix('settings')
        ->group(function () {

            Route::get('/general-settings', 'generalSettings')
                ->name('index');

            Route::match(['get', 'post'], '/package-percentage', 'UpdatePackagePercentage')
                ->name('package.percentage');

            Route::get('/payment-settings', 'PaymentSettings')
                ->name('payment.settings');

            Route::get('/social-settings', 'socialSettings')
                ->name('social.settings');

            Route::match(['get', 'post'], '/add-social-settings', 'AddsocialSettings')
                ->name('add.social.settings');

            Route::match(['get', 'post'], '/list-social-settings', 'getSocialLists')
                ->name('list.social.settings');

            Route::match(['get', 'post'], '/update-social-status', 'updateSocalStatus')
                ->name('update.social.status');

            Route::match(['get', 'post'], '/edit-social-status/{id}', 'editSocalStatus')
                ->name('edit.social.status');

            Route::get('/smtp-settings', 'smtpSettings')
                ->name('smtp.settings');

            Route::get('/sms-settings', 'smsSettings')
                ->name('sms.settings');

            Route::post('/update-smtp-settings', 'UpdateSmtpSettings')
                ->name('update.smtp.settings');

            Route::post('/update-general-settings', 'UpdateGeneralSettings')
                ->name('update.general.settings');

            Route::post('/update-sms-settings', 'UpdateSmsSettings')
                ->name('update.sms.settings');

            Route::post('/update-payment-settings', 'UpdatePaymentSettings')
                ->name('update.payment.settings');
        });


    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\PagesController::class)
        ->prefix('pages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-pages', 'index')
                ->name('list.pages');

            Route::match(['get', 'post'], '/list-pages-data', 'getPagesData')
                ->name('list.pages.data');

            Route::match(['get', 'post'], '/add-page', 'AddPage')
                ->name('add.page');

            Route::match(['get', 'post'], '/edit-page/{id}', 'EditPage')
                ->name('edit.page');

            Route::match(['get', 'post'], '/update-page-status', 'updatePageStatus')
                ->name('update.page.status');
        });


    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\CategoryController::class)
        ->prefix('category')
        ->group(function () {

            Route::match(['get', 'post'], '/list-category', 'index')
                ->name('list.category');

            Route::match(['get', 'post'], '/list-category-data', 'getCategoryData')
                ->name('list.category.data');

            Route::match(['get', 'post'], '/add-category', 'create')
                ->name('add.category');

            Route::match(['get', 'post'], '/edit-category/{id}', 'edit')
                ->name('edit.category');

            Route::match(['get', 'post'], '/update-category-status', 'updateCategoryStatus')
                ->name('update.category.status');

            Route::delete('/category-destroy/{id?}', 'destroy')
                ->name('category.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Country
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\CountryController::class)
        ->prefix('country')
        ->group(function () {

            Route::match(['get', 'post'], '/list-country', 'index')
                ->name('list.country');

            Route::match(['get', 'post'], '/list-country-data', 'getCountryData')
                ->name('list.country.data');

            Route::match(['get', 'post'], '/add-country', 'create')
                ->name('add.country');

            Route::match(['get', 'post'], '/edit-country/{id}', 'edit')
                ->name('edit.country');

            Route::match(['get', 'post'], '/update-country-status', 'updateCountryStatus')
                ->name('update.country.status');

            Route::delete('/country-destroy/{id?}', 'destroy')
                ->name('country.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\StateController::class)
        ->prefix('state')
        ->group(function () {

            Route::match(['get', 'post'], '/list-state', 'index')
                ->name('list.state');

            Route::match(['get', 'post'], '/list-state-data', 'getStateData')
                ->name('list.state.data');

            Route::match(['get', 'post'], '/add-state', 'create')
                ->name('add.state');

            Route::match(['get', 'post'], '/edit-state/{id}', 'edit')
                ->name('edit.state');

            Route::match(['get', 'post'], '/update-state-status', 'updateStateStatus')
                ->name('update.state.status');

            Route::delete('/state-destroy/{id?}', 'destroy')
                ->name('state.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | City
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\CityController::class)
        ->prefix('city')
        ->group(function () {

            Route::match(['get', 'post'], '/list-city', 'index')
                ->name('list.city');

            Route::match(['get', 'post'], '/list-city-data', 'getCityData')
                ->name('list.city.data');

            Route::match(['get', 'post'], '/add-city', 'create')
                ->name('add.city');

            Route::match(['get', 'post'], '/edit-city/{id}', 'edit')
                ->name('edit.city');

            Route::match(['get', 'post'], '/update-city-status', 'updateCityStatus')
                ->name('update.city.status');

            Route::delete('/city-destroy/{id?}', 'destroy')
                ->name('city.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Domestic Tour Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\ToursController::class)
        ->prefix('domestic-tour-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-domestic-tour-packages', 'index')
                ->name('list.tours');

            Route::match(['get', 'post'], '/list-domestic-tour-packages-data', 'getToursData')
                ->name('list.tours.data');

            Route::match(['get', 'post'], '/add-domestic-tour-packages', 'create')
                ->name('add.tours');

            Route::match(['get', 'post'], '/edit-domestic-tour-packages/{id}', 'edit')
                ->name('edit.tours');

            Route::match(['get', 'post'], '/update-domestic-tour-packages-status', 'updateToursStatus')
                ->name('update.tours.status');

            Route::delete('/domestic-tour-packages-destroy/{id?}', 'destroy')
                ->name('tours.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | International Tour Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\InternationalToursController::class)
        ->prefix('international-tour-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-international-tour-packages', 'index')
                ->name('list.international.tours');

            Route::match(['get', 'post'], '/list-international-tour-packages-data', 'getToursData')
                ->name('list.international.tours.data');

            Route::match(['get', 'post'], '/add-international-tour-packages', 'create')
                ->name('add.international.tours');

            Route::match(['get', 'post'], '/edit-international-tour-packages/{id}', 'edit')
                ->name('edit.international.tours');

            Route::match(['get', 'post'], '/update-international-tour-packages-status', 'updateToursStatus')
                ->name('update.international.tours.status');

            Route::delete('/international-tour-packages-destroy/{id?}', 'destroy')
                ->name('tours.international.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Honeymoon Tour Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\HoneymoonToursController::class)
        ->prefix('honeymoon-tour-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-honeymoon-tour-packages', 'index')
                ->name('list.honeymoon.tours');

            Route::match(['get', 'post'], '/list-honeymoon-tour-packages-data', 'getToursData')
                ->name('list.honeymoon.tours.data');

            Route::match(['get', 'post'], '/add-honeymoon-tour-packages', 'create')
                ->name('add.honeymoon.tours');

            Route::match(['get', 'post'], '/edit-honeymoon-tour-packages/{id}', 'edit')
                ->name('edit.honeymoon.tours');

            Route::match(['get', 'post'], '/update-honeymoon-tour-packages-status', 'updateToursStatus')
                ->name('update.honeymoon.tours.status');

            Route::delete('/honeymoon-tour-packages-destroy/{id?}', 'destroy')
                ->name('tours.honeymoon.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Fixed Departures Admin
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\FixedDeparturesController::class)
        ->prefix('admin-fixed-departures')
        ->group(function () {

            Route::match(['get', 'post'], '/list', 'index')
                ->name('list.fixed.departures');

            Route::match(['get', 'post'], '/list-data', 'getFixedDeparturesData')
                ->name('list.FixedDepartures.data');

            Route::match(['get', 'post'], '/add', 'create')
                ->name('add.FixedDepartures');

            Route::match(['get', 'post'], '/edit/{id}', 'edit')
                ->name('edit.FixedDepartures');

            Route::match(['get', 'post'], '/update-status', 'updateFixedDeparturesStatus')
                ->name('update.FixedDepartures.status');

            Route::delete('/destroy/{id?}', 'destroy')
                ->name('FixedDepartures.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Departure Dates
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\DeparturesDatesController::class)
        ->prefix('admin-fixed-departures-dates')
        ->group(function () {

            Route::match(['get', 'post'], '/list/{id?}', 'index')
                ->name('list.departures.dates');

            Route::match(['get', 'post'], '/list-data/{id?}', 'getData')
                ->name('list.departures.dates.data');

            Route::match(['get', 'post'], '/add/{id}', 'create')
                ->name('add.departures.dates');

            Route::match(['get', 'post'], '/edit/{tour_id}/{id}', 'edit')
                ->name('edit.departures.dates');

            Route::match(['get', 'post'], '/update-status', 'updateStatus')
                ->name('update.departures.dates.status');

            Route::match(['get', 'post'], '/tour-destroy', 'destroy')
                ->name('departures.dates.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Domestic Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\PackageController::class)
        ->prefix('domestic-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-packages/{id}', 'index')
                ->name('list.packages');

            Route::match(['get', 'post'], '/list-packages-data/{id}', 'getPackageData')
                ->name('list.packages.data');

            Route::match(['get', 'post'], '/add-packages/{id}', 'create')
                ->name('add.packages');

            Route::match(['get', 'post'], '/edit-packages/{tour_id}/{id}', 'edit')
                ->name('edit.packages');

            Route::match(['get', 'post'], '/update-packages-status', 'updatePackagesStatus')
                ->name('update.packages.status');

            Route::delete('/tour-packages-destroy/{id?}', 'destroy')
                ->name('packages.destroy');

            Route::delete('/delete-image/{imageId}', 'ImageDestroy')
                ->name('images.delete');

            Route::delete('/delete-vehicle-image/{imageId}', 'VehicleImageDestroy')
                ->name('vehicle.images.delete');

            Route::delete('/delete-hotels-image/{imageId}', 'HotelsImageDestroy')
                ->name('hotel.images.delete');

            Route::post('update-room-price/{id}', 'updateRoomPrice')
                ->name('update.room.price');

            Route::post('update-car-price/{id}', 'updateCarPrice')
                ->name('update.car.price');

            Route::get('/package-price-details/{id?}', 'getPackagePriceDetails')
                ->name('package.price.details');
        });


    /*
    |--------------------------------------------------------------------------
    | International Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\InternationalPackageController::class)
        ->prefix('international-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-packages/{id}', 'index')
                ->name('list.international.packages');

            Route::match(['get', 'post'], '/list-packages-data/{id}', 'getPackageData')
                ->name('list.international.packages.data');

            Route::match(['get', 'post'], '/add-packages/{id}', 'create')
                ->name('add.international.packages');

            Route::match(['get', 'post'], '/edit-packages/{tour_id}/{id}', 'edit')
                ->name('edit.international.packages');

            Route::match(['get', 'post'], '/update-packages-status', 'updatePackagesStatus')
                ->name('update.international.packages.status');

            Route::delete('/tour-packages-destroy/{id?}', 'destroy')
                ->name('international.packages.destroy');

            Route::delete('/delete-image/{imageId}', 'ImageDestroy')
                ->name('images.delete');

            Route::delete('/delete-vehicle-image/{imageId}', 'VehicleImageDestroy')
                ->name('vehicle.images.delete');

            Route::delete('/delete-hotels-image/{imageId}', 'HotelsImageDestroy')
                ->name('hotel.images.delete');

            Route::post('update-room-price/{id}', 'updateRoomPrice')
                ->name('update.room.price');

            Route::post('update-car-price/{id}', 'updateCarPrice')
                ->name('update.car.price');

            Route::get('/package-price-details/{id?}', 'getPackagePriceDetails')
                ->name('international.package.price.details');
        });


    /*
    |--------------------------------------------------------------------------
    | Honeymoon Packages
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\HoneymoonPackageController::class)
        ->prefix('honeymoon-packages')
        ->group(function () {

            Route::match(['get', 'post'], '/list-packages/{id}', 'index')
                ->name('list.honeymoon.packages');

            Route::match(['get', 'post'], '/list-packages-data/{id}', 'getPackageData')
                ->name('list.honeymoon.packages.data');

            Route::match(['get', 'post'], '/add-packages/{id}', 'create')
                ->name('add.honeymoon.packages');

            Route::match(['get', 'post'], '/edit-packages/{tour_id}/{id}', 'edit')
                ->name('edit.honeymoon.packages');

            Route::match(['get', 'post'], '/update-packages-status', 'updatePackagesStatus')
                ->name('update.honeymoon.packages.status');

            Route::match(['get', 'post'], '/tour-packages-destroy', 'destroy')
                ->name('honeymoon.packages.destroy');

            Route::delete('/delete-image/{imageId}', 'ImageDestroy')
                ->name('images.delete');

            Route::delete('/delete-vehicle-image/{imageId}', 'VehicleImageDestroy')
                ->name('vehicle.images.delete');

            Route::delete('/delete-hotels-image/{imageId}', 'HotelsImageDestroy')
                ->name('hotel.images.delete');

            Route::post('update-room-price/{id}', 'updateRoomPrice')
                ->name('update.room.price');

            Route::post('update-car-price/{id}', 'updateCarPrice')
                ->name('update.car.price');

            Route::get('/package-price-details/{id?}', 'getPackagePriceDetails')
                ->name('honeymoon.package.price.details');
        });


    /*
    |--------------------------------------------------------------------------
    | Domestic Itinerary
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\ItineraryController::class)
        ->prefix('domestic-itinerary')
        ->group(function () {

            Route::match(['get', 'post'], '/list-itinerary/{tour_id}/{id}', 'index')
                ->name('list.domestic.itinerary');

            Route::match(['get', 'post'], '/list-itinerary-data/{tour_id}/{id}', 'getItineraryData')
                ->name('list.domestic.itinerary.data');

            Route::match(['get', 'post'], '/add-itinerary/{tour_id}/{id}', 'create')
                ->name('add.domestic.itinerary');

            Route::match(['get', 'post'], '/edit-itinerary/{tour_id}/{package_id}/{id}', 'edit')
                ->name('edit.domestic.itinerary');

            Route::match(['get', 'post'], '/update-itinerary-status', 'updateItineraryStatus')
                ->name('update.domestic.itinerary.status');

            Route::delete('/tour-itinerary-destroy/{id?}', 'destroy')
                ->name('domestic.itinerary.destroy');

            Route::delete('/delete-itinerary-image/{imageId}', 'ImageDestroy')
                ->name('itinerary.images.delete');

            Route::match(['get', 'post'], '/domestic/itinerary/reorder', 'reorder')
                ->name('domestic.itinerary.reorder');
        });


    /*
    |--------------------------------------------------------------------------
    | International Itinerary
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\InternationalItineraryController::class)
        ->prefix('international-itinerary')
        ->group(function () {

            Route::match(['get', 'post'], '/list-itinerary/{tour_id}/{id}', 'index')
                ->name('list.international.itinerary');

            Route::match(['get', 'post'], '/list-itinerary-data/{tour_id}/{id}', 'getItineraryData')
                ->name('list.international.itinerary.data');

            Route::match(['get', 'post'], '/add-itinerary/{tour_id}/{id}', 'create')
                ->name('add.international.itinerary');

            Route::match(['get', 'post'], '/edit-itinerary/{tour_id}/{package_id}/{id}', 'edit')
                ->name('edit.international.itinerary');

            Route::match(['get', 'post'], '/update-itinerary-status', 'updateItineraryStatus')
                ->name('update.international.itinerary.status');

            Route::delete('/tour-itinerary-destroy/{id?}', 'destroy')
                ->name('international.itinerary.destroy');

            Route::delete('/delete-international-itinerary-image/{imageId}', 'ImageDestroy')
                ->name('international.images.delete');

            Route::match(['get', 'post'], '/international/itinerary/reorder', 'reorder')
                ->name('international.itinerary.reorder');
        });


    /*
    |--------------------------------------------------------------------------
    | Honeymoon Itinerary
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\HoneymoonItineraryController::class)
        ->prefix('honeymoon-itinerary')
        ->group(function () {

            Route::match(['get', 'post'], '/list-itinerary/{tour_id}/{id}', 'index')
                ->name('list.honeymoon.itinerary');

            Route::match(['get', 'post'], '/list-itinerary-data/{tour_id}/{id}', 'getItineraryData')
                ->name('list.honeymoon.itinerary.data');

            Route::match(['get', 'post'], '/add-itinerary/{tour_id}/{id}', 'create')
                ->name('add.honeymoon.itinerary');

            Route::match(['get', 'post'], '/edit-itinerary/{tour_id}/{package_id}/{id}', 'edit')
                ->name('edit.honeymoon.itinerary');

            Route::match(['get', 'post'], '/update-itinerary-status', 'updateItineraryStatus')
                ->name('update.honeymoon.itinerary.status');

            Route::match(['get', 'post'], '/honeymoon-tour-itinerary-destroy', 'destroy')
                ->name('honeymoon.itinerary.destroy');

            Route::delete('/delete-honeymoon-itinerary-image/{imageId}', 'ImageDestroy')
                ->name('honeymoon.images.delete');

            Route::match(['get', 'post'], '/honeymoon/itinerary/reorder', 'reorder')
                ->name('honeymoon.itinerary.reorder');
        });


    /*
    |--------------------------------------------------------------------------
    | Package Price
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\PackagePriceController::class)
        ->prefix('package-price')
        ->group(function () {

            Route::match(['get', 'post'], '/list-package-price/{tour_id}/{id}', 'index')
                ->name('package.price.list');

            Route::match(['get', 'post'], '/list-package-price-data/{tour_id}/{id}', 'getPackageData')
                ->name('list.package.price.data');

            Route::match(['get', 'post'], '/add-package-price/{tour_id}/{id}', 'create')
                ->name('add.package.price');

            Route::match(['get', 'post'], '/edit-package-price/{tour_id}/{package_id}/{id}', 'edit')
                ->name('edit.package.price');

            Route::match(['get', 'post'], '/update-package-price-status', 'updateStatus')
                ->name('update.package.price.status');

            Route::delete('/tour-package-price-destroy/{id?}', 'destroy')
                ->name('package.price.destroy');

            Route::get('/package/price/edit/{tour_id}/{package_id}', 'edit')
                ->name('edit.package.price');

            Route::post('/package/price/edit/{tour_id}/{package_id}', 'edit')
                ->name('edit.package.price.post');
        });


    /*
    |--------------------------------------------------------------------------
    | Agents
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\AgentsController::class)
        ->prefix('agents')
        ->group(function () {

            Route::match(['get', 'post'], '/list-agents', 'index')
                ->name('list.agents');

            Route::match(['get', 'post'], '/list-agents-data', 'getAgentsData')
                ->name('list.agents.data');

            Route::match(['get', 'post'], '/add-agents', 'create')
                ->name('add.agents');

            Route::get('/agents/edit/{id}', 'edit')
                ->name('edit.agents');

            Route::post('/agents/update/{id}', 'update')
                ->name('update.agents');

            Route::put('/agents/update/{id}', 'update')
                ->name('update.agents');

            Route::match(['get', 'post'], '/update-agents-status', 'updateAgentsStatus')
                ->name('update.agents.status');

            Route::delete('/agents-destroy/{id?}', 'destroy')
                ->name('agents.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Vendor
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\VendorController::class)
        ->prefix('vendor')
        ->group(function () {

            Route::match(['get', 'post'], '/list-vendor', 'index')
                ->name('list.vendor');

            Route::match(['get', 'post'], '/list-vendor-data', 'getVendorData')
                ->name('list.vendor.data');

            Route::match(['get', 'post'], '/add-vendor', 'create')
                ->name('add.vendor');

            Route::match(['get', 'post'], '/edit-vendor/{id}', 'edit')
                ->name('edit.vendor');

            Route::match(['get', 'post'], '/update-vendor-status', 'updateVendorStatus')
                ->name('update.vendor.status');

            Route::delete('/vendor-destroy/{id?}', 'destroy')
                ->name('vendor.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Testimonials
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\TestimonialsController::class)
        ->prefix('testimonials')
        ->group(function () {

            Route::match(['get', 'post'], '/list-testimonials', 'index')
                ->name('list.testimonials');

            Route::match(['get', 'post'], '/list-testimonials-data', 'getTestimonialsData')
                ->name('list.testimonials.data');

            Route::match(['get', 'post'], '/add-testimonials', 'create')
                ->name('add.testimonials');

            Route::match(['get', 'post'], '/edit-testimonials/{id}', 'edit')
                ->name('edit.testimonials');

            Route::match(['get', 'post'], '/update-testimonials-status', 'updateTestimonialsStatus')
                ->name('update.testimonials.status');

            Route::delete('/testimonials-destroy/{id?}', 'destroy')
                ->name('testimonials.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Blog
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\BlogController::class)
        ->prefix('blog')
        ->group(function () {

            Route::match(['get', 'post'], '/list-blog', 'index')
                ->name('list.blog');

            Route::match(['get', 'post'], '/list-blog-data', 'getBlogData')
                ->name('list.blog.data');

            Route::match(['get', 'post'], '/add-blog', 'create')
                ->name('add.blog');

            Route::match(['get', 'post'], '/edit-blog/{id}', 'edit')
                ->name('edit.blog');

            Route::match(['get', 'post'], '/update-blog-status', 'updateBlogStatus')
                ->name('update.blog.status');

            Route::delete('/blog-destroy/{id?}', 'destroy')
                ->name('blog.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Enquiries
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\EnquiryController::class)
        ->prefix('enquiry')
        ->group(function () {

            Route::match(['get', 'post'], '/list-enquiry', 'index')
                ->name('list.enquiry');

            Route::get('/enquiry/list', 'getEnquiryData')
                ->name('list.enquiry.data');

            Route::post('/enquiry/update-status', 'updateEnquiryStatus')
                ->name('update.enquiry.status');

            Route::delete('/enquiry/delete', 'deleteEnquiry')
                ->name('delete.enquiry');

            Route::post('/enquiry/bulk-delete', 'bulkDeleteEnquiries')
                ->name('bulk.delete.enquiries');

            Route::get('/enquiry/export', 'exportEnquiries')
                ->name('export.enquiries');

            Route::match(['get', 'post'], '/list-quick-enquiry', 'QuickEnquiry')
                ->name('list.quick.enquiry');

            Route::match(['get', 'post'], '/list-quick-enquiry-data', 'getQuickEnquiryData')
                ->name('list.quick.enquiry.data');

            Route::match(['get', 'post'], '/list-booking', 'BookingEnquiry')
                ->name('list.booking.enquiry');

            Route::match(['get', 'post'], '/list-booking-data', 'getBookingEnquiryData')
                ->name('list.booking.enquiry.data');
        });


    /*
    |--------------------------------------------------------------------------
    | Support Tickets
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\TicketsController::class)
        ->prefix('support-tickets')
        ->group(function () {

            Route::get('/list', 'index')
                ->name('list.support.tickets');

            Route::get('/support-tickets/data', 'listData')
                ->name('list.support.tickets.data');

            Route::post('/support-tickets/status', 'updateStatus')
                ->name('support.tickets.status');

            Route::get('/support-tickets/{id?}', 'show')
                ->name('support.tickets.show');

            Route::post('/support-tickets/{id?}/reply', 'reply')
                ->name('support.tickets.reply');

            Route::delete('/support-tickets/{id?}', 'destroy')
                ->name('support.tickets.destroy');

            Route::post('/support-tickets/{id}/mark-read', 'markRead')
                ->name('support.tickets.mark-read');
        });


    /*
    |--------------------------------------------------------------------------
    | FAQ
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\FaqController::class)
        ->prefix('faq')
        ->group(function () {

            Route::match(['get', 'post'], '/list-faq', 'index')
                ->name('list.faq');

            Route::match(['get', 'post'], '/list-faq-data', 'getFaqData')
                ->name('list.faq.data');

            Route::match(['get', 'post'], '/add-faq', 'create')
                ->name('add.faq');

            Route::match(['get', 'post'], '/edit-faq/{id}', 'edit')
                ->name('edit.faq');

            Route::match(['get', 'post'], '/update-faq-status', 'updateFaqStatus')
                ->name('update.faq.status');

            Route::delete('/faq-destroy/{id?}', 'destroy')
                ->name('faq.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Home Page Sections
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\HomePageSectionController::class)
        ->prefix('home-page-section')
        ->group(function () {

            Route::match(['get', 'post'], '/list-home-page-section', 'index')
                ->name('list.home.page.section');

            Route::match(['get', 'post'], '/list-home-page-section-data', 'getHomePageSectionData')
                ->name('list.home.page.section.data');

            Route::match(['get', 'post'], '/add-home-page-section', 'create')
                ->name('add.home.page.section');

            Route::match(['get', 'post'], '/edit-home-page-section/{id}', 'edit')
                ->name('edit.home.page.section');

            Route::match(['get', 'post'], '/update-home-page-section-status', 'updateHomePageSectionStatus')
                ->name('update.home.page.section.status');

            Route::delete('/home-page-section-destroy/{id?}', 'destroy')
                ->name('home.page.section.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\NotificationController::class)
        ->prefix('notification')
        ->group(function () {

            Route::match(['get', 'post'], '/list-notification', 'index')
                ->name('list.notification');

            Route::match(['get', 'post'], '/list-notification-data', 'getData')
                ->name('list.notification.data');

            Route::match(['get', 'post'], '/add-notification', 'create')
                ->name('add.notification');

            Route::match(['get', 'post'], '/edit-notification/{id}', 'edit')
                ->name('edit.notification');

            Route::match(['get', 'post'], '/update-faq-status', 'updateStatus')
                ->name('update.notification.status');

            Route::delete('/notification-destroy/{id?}', 'destroy')
                ->name('notification.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Coupons
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\CouponController::class)
        ->prefix('coupons')
        ->group(function () {

            Route::match(['get', 'post'], '/list-coupon', 'index')
                ->name('list.coupon');

            Route::match(['get', 'post'], '/list-coupon-data', 'getCouponData')
                ->name('list.coupon.data');

            Route::match(['get', 'post'], '/add-coupon', 'create')
                ->name('add.coupon');

            Route::match(['get', 'post'], '/edit-coupon/{id}', 'edit')
                ->name('edit.coupon');

            Route::match(['get', 'post'], '/update-coupon-status', 'updateCouponStatus')
                ->name('update.coupon.status');

            Route::delete('/coupon-destroy/{id?}', 'destroy')
                ->name('coupon.destroy');

            Route::match(['get', 'post'], '/list-coupon-history', 'History')
                ->name('list.coupon.history');
        });


    /*
    |--------------------------------------------------------------------------
    | Wallet
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\WalletController::class)
        ->prefix('wallet')
        ->group(function () {

            Route::match(['get', 'post'], '/list-wallet', 'index')
                ->name('list.wallet');

            Route::match(['get', 'post'], '/list-wallet-data', 'getWalletData')
                ->name('list.wallet.data');

            Route::match(['get', 'post'], '/add-wallet', 'create')
                ->name('add.wallet');

            Route::match(['get', 'post'], '/edit-wallet/{id}', 'edit')
                ->name('edit.wallet');

            Route::match(['get', 'post'], '/update-wallet-status', 'updateWalletStatus')
                ->name('update.wallet.status');

            Route::delete('/wallet-destroy/{id?}', 'destroy')
                ->name('wallet.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Subscribe
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\SubscribeController::class)
        ->prefix('subscribe')
        ->group(function () {

            Route::match(['get', 'post'], '/list-subscribe', 'index')
                ->name('list.subscribe');

            Route::match(['get', 'post'], '/list-subscribe-data', 'getSubscribeData')
                ->name('list.subscribe.data');

            Route::match(['get', 'post'], '/add-subscribe', 'create')
                ->name('add.subscribe');

            Route::match(['get', 'post'], '/edit-subscribe/{id}', 'edit')
                ->name('edit.subscribe');

            Route::match(['get', 'post'], '/update-subscribe-status', 'updateSubscribeStatus')
                ->name('update.subscribe.status');

            Route::delete('/subscribe-destroy/{id?}', 'destroy')
                ->name('subscribe.destroy');

            Route::match(['get', 'post'], '/list-subscribe-history', 'History')
                ->name('list.subscribe.history');

            Route::match(['get', 'post'], '/list-subscribe-history-data', 'getHistorySubscribeData')
                ->name('list.subscribe.history.data');
        });


    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\ProductController::class)
        ->prefix('product')
        ->group(function () {

            Route::match(['get', 'post'], '/list-product', 'index')
                ->name('list.product');

            Route::match(['get', 'post'], '/list-product-data', 'getProductData')
                ->name('list.product.data');

            Route::match(['get', 'post'], '/add-product', 'create')
                ->name('add.product');

            Route::match(['get', 'post'], '/edit-product/{id}', 'edit')
                ->name('edit.product');

            Route::match(['get', 'post'], '/update-product-status', 'updateProductStatus')
                ->name('update.product.status');

            Route::delete('/product-destroy/{id?}', 'destroy')
                ->name('product.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Users / Payments
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\PaymentController::class)
        ->prefix('users')
        ->group(function () {

            Route::match(['get', 'post'], '/add-users', 'add')
                ->name('add.users');

            Route::match(['get', 'post'], '/list-users', 'index')
                ->name('list.users');

            Route::match(['get', 'post'], '/edit-users/{id}', 'edit')
                ->name('edit.users');

            Route::match(['get', 'post'], '/list-users-data', 'getUsersData')
                ->name('list.users.data');

            Route::match(['get', 'post'], '/update-users-status', 'updateUsersStatus')
                ->name('update.users.status');

            Route::delete('/users-destroy/{id?}', 'destroy')
                ->name('users.destroy');

            Route::match(['get', 'post'], '/list-payment-data', 'getPaymentsData')
                ->name('list.payments.data');

            Route::match(['get', 'post'], '/list-payments', 'ListPayments')
                ->name('list.payments');
        });


    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    Route::match(
        ['get', 'post'],
        '/uploadimage',
        [App\Http\Controllers\UploadController::class, 'uploadimage']
    )->name('uploadimage');


    /*
    |--------------------------------------------------------------------------
    | Banner
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\BannerController::class)
        ->prefix('banner')
        ->group(function () {

            Route::match(['get', 'post'], '/list-banner', 'index')
                ->name('list.banner');

            Route::match(['get', 'post'], '/add-banner', 'Create')
                ->name('add.banner');

            Route::match(['get', 'post'], '/list-banner-data', 'getBannerData')
                ->name('list.banner.data');

            Route::match(['get', 'post'], '/update-banner-status', 'updateBannerStatus')
                ->name('update.banner.status');

            Route::match(['get', 'post'], '/edit-banner/{id}', 'Edit')
                ->name('edit.banner');

            Route::delete('/banner-destroy/{id?}', 'destroy')
                ->name('banner.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | Gallery
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\GalleryController::class)
        ->prefix('gallery')
        ->group(function () {

            Route::match(['get', 'post'], '/list-gallery', 'index')
                ->name('list.gallery');

            Route::match(['get', 'post'], '/add-gallery', 'Create')
                ->name('add.gallery');

            Route::match(['get', 'post'], '/list-gallery-data', 'getGalleryData')
                ->name('list.gallery.data');

            Route::match(['get', 'post'], '/update-gallery-status', 'updateGalleryStatus')
                ->name('update.gallery.status');

            Route::match(['get', 'post'], '/edit-gallery/{id}', 'Edit')
                ->name('edit.gallery');

            Route::delete('/gallery-destroy/{id?}', 'destroy')
                ->name('gallery.destroy');
        });


    /*
    |--------------------------------------------------------------------------
    | User Bookings
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\UserBookingController::class)
        ->prefix('users-booking')
        ->group(function () {

            Route::match(['get', 'post'], '/fixed-departures-list', 'index')
                ->name('fixed.departures.booking.list');

            Route::get('/get-fixed-departures-data', 'getFixedDeparturesData')
                ->name('get.fixed.departures.data');

            Route::get('/admin-fixed-departures', 'index')
                ->name('admin.fixed.departures');

            Route::get('/booking-details/{id}', 'getBookingDetails')
                ->name('booking.details');

            Route::get('/export-bookings', 'exportBookings')
                ->name('export.bookings');

            Route::post('/update-booking-status', 'updateBookingStatus')
                ->name('update.booking.status');

            Route::delete('/delete-booking/{id}', 'destroyBooking')
                ->name('delete.booking');

            Route::match(['get', 'post'], '/holidays-list', 'holidays_list')
                ->name('holidays.booking.list');

            Route::get('/get-holidays-booking-data', 'getHolidaysBooking')
                ->name('get.holidays-booking.data');

            Route::get('/holidays-booking-details/{id}', 'getHolidaysBookingDetails')
                ->name('holidays.booking.details');

            Route::get('/export-holidays-bookings', 'exportHolidaysBookings')
                ->name('export.holidays.bookings');

            Route::match(['get', 'post'], '/flight-list', 'flight_list')
                ->name('flight.booking.list');

            Route::match(['get', 'post'], '/hotel-list', 'hotel_list')
                ->name('hotel.booking.list');

            Route::match(['get', 'post'], '/bus-list', 'bus_list')
                ->name('bus.booking.list');

            Route::match(['get', 'post'], '/car-list', 'car_list')
                ->name('car.booking.list');

            Route::match(['get', 'post'], '/travel-insurance-list', 'travel_insurance')
                ->name('travel.insurance.booking.list');
        });


    /*
    |--------------------------------------------------------------------------
    | Group Tour Bookings
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\UserBookingGroupToursController::class)
        ->prefix('users-booking')
        ->group(function () {

            Route::match(['get', 'post'], '/school-excursion-list', 'school_excursion')
                ->name('school.excursion.booking.list');

            Route::match(['get', 'post'], '/college-excursion-list', 'college_excursion')
                ->name('college.excursion.booking.list');

            Route::match(['get', 'post'], '/corporate-tours-list', 'corporate_tours')
                ->name('corporate.tours.booking.list');

            Route::get('/get-corporate-tours-booking-data', 'getCorporateBooking')
                ->name('get.corporate.tours.data');

            Route::get('/details/{id}', 'getDetails')
                ->name('corporate.tours.details');

            Route::post('/status/{id}', 'updateStatus')
                ->name('corporate.tours.status');

            Route::delete('/corporate-tours/{id}', 'destroy')
                ->name('corporate.tours.delete');

            Route::get('/export/csv', 'exportCSV')
                ->name('corporate.tours.export');
        });


    /*
    |--------------------------------------------------------------------------
    | Package Highlights
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\PackageHighlightsController::class)
        ->prefix('package-highlights')
        ->group(function () {

            // Route::match(['get','post'],'/domestic-list/{tour_id}/{id}', 'index')->name('domestic.package.highlights.list');

            Route::match(['get', 'post'], '/domestic-add/{tour_id}/{id}', 'add')
                ->name('domestic.package.highlights.add');
        });

    Route::match(
        ['get', 'post'],
        '/highlights/edit/{id}',
        [App\Http\Controllers\PackageHighlightsController::class, 'edit']
    )->name('domestic.package.highlights.edit');

    Route::get(
        '/highlights/list/{tour_id}/{id}',
        [App\Http\Controllers\PackageHighlightsController::class, 'list']
    )->name('domestic.package.highlights.list');

    Route::get(
        '/highlights/get-data',
        [App\Http\Controllers\PackageHighlightsController::class, 'getHighlightsData']
    )->name('domestic.package.highlights.get-data');

    Route::post(
        '/highlights/delete',
        [App\Http\Controllers\PackageHighlightsController::class, 'deleteHighlight']
    )->name('domestic.package.highlights.delete');

    Route::post(
        '/highlights/update-status',
        [App\Http\Controllers\PackageHighlightsController::class, 'updateStatus']
    )->name('domestic.package.highlights.update-status');

    Route::get(
        '/highlights/get/{id}',
        [App\Http\Controllers\PackageHighlightsController::class, 'getHighlightData']
    )->name('domestic.package.highlights.get');


    /*
    |--------------------------------------------------------------------------
    | International Package Highlights
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\InternationalPackageHighlightsController::class)
        ->prefix('package-highlights')
        ->group(function () {

            // Route::match(['get','post'],'/domestic-list/{tour_id}/{id}', 'index')->name('international.package.highlights.list');

            Route::match(['get', 'post'], '/international-add/{tour_id}/{id}', 'add')
                ->name('international.package.highlights.add');
        });

    Route::match(
        ['get', 'post'],
        '/international-highlights/edit/{id}',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'edit']
    )->name('international.package.highlights.edit');

    Route::get(
        '/international-highlights/list/{tour_id}/{id}',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'list']
    )->name('international.package.highlights.list');

    Route::get(
        '/international-highlights/get-data',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'getHighlightsData']
    )->name('international.package.highlights.get-data');

    Route::post(
        '/international-highlights/delete',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'deleteHighlight']
    )->name('international.package.highlights.delete');

    Route::post(
        '/international-highlights/update-status',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'updateStatus']
    )->name('international.package.highlights.update-status');

    Route::get(
        '/international-highlights/get/{id}',
        [App\Http\Controllers\InternationalPackageHighlightsController::class, 'getHighlightData']
    )->name('international.package.highlights.get');


    /*
    |--------------------------------------------------------------------------
    | Honeymoon Package Highlights
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\HoneymoonPackageHighlightsController::class)
        ->prefix('package-highlights')
        ->group(function () {

            // Route::match(['get','post'],'/domestic-list/{tour_id}/{id}', 'index')->name('honeymoon.package.highlights.list');

            Route::match(['get', 'post'], '/honeymoon-add/{tour_id}/{id}', 'add')
                ->name('honeymoon.package.highlights.add');
        });

    Route::match(
        ['get', 'post'],
        '/honeymoon-highlights/edit/{id}',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'edit']
    )->name('honeymoon.package.highlights.edit');

    Route::get(
        '/honeymoon-highlights/list/{tour_id}/{id}',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'list']
    )->name('honeymoon.package.highlights.list');

    Route::get(
        '/honeymoon-highlights/get-data',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'getHighlightsData']
    )->name('honeymoon.package.highlights.get-data');

    Route::post(
        '/honeymoon-highlights/delete',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'deleteHighlight']
    )->name('honeymoon.package.highlights.delete');

    Route::post(
        '/honeymoon-highlights/update-status',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'updateStatus']
    )->name('honeymoon.package.highlights.update-status');

    Route::get(
        '/honeymoon-highlights/get/{id}',
        [App\Http\Controllers\HoneymoonPackageHighlightsController::class, 'getHighlightData']
    )->name('honeymoon.package.highlights.get');


    /*
    |--------------------------------------------------------------------------
    | Dynamic Pricing
    |--------------------------------------------------------------------------
    */

    Route::controller(App\Http\Controllers\DynamicPricingController::class)
        ->prefix('dynamic-price')
        ->group(function () {

            Route::match(['get', 'post'], '/list-dynamic-price', 'index')
                ->name('list.dynamic.price');

            Route::match(['get', 'post'], '/list-dynamic-price-data', 'getDynamicPriceData')
                ->name('list.dynamic.price.data');

            Route::match(['get', 'post'], '/add-dynamic-price', 'create')
                ->name('add.dynamic.price');

            Route::match(['get', 'post'], '/edit-dynamic-price/{id}', 'edit')
                ->name('edit.dynamic.price');

            Route::match(['get', 'post'], '/update-dynamic-price-status', 'updateDynamicPriceStatus')
                ->name('update.dynamic.price.status');

            Route::post('/dynamic-price-destroy/{id?}', 'destroy')
                ->name('dynamic.price.destroy');

            Route::match(['get', 'post'], '/list-car-dynamic-price', 'Carindex')
                ->name('list.car.dynamic.price');

            Route::match(['get', 'post'], '/list-car-dynamic-price-data', 'getCarDynamicPriceData')
                ->name('list.car.dynamic.price.data');

            Route::match(['get', 'post'], '/add-car-dynamic-price', 'Carcreate')
                ->name('add.car.dynamic.price');

            Route::match(['get', 'post'], '/edit-car-dynamic-price/{id}', 'Caredit')
                ->name('edit.car.dynamic.price');

            Route::match(['get', 'post'], '/update-car-dynamic-price-status', 'updateCarDynamicPriceStatus')
                ->name('update.car.dynamic.price.status');

            Route::post('/car-dynamic-price-destroy/{id?}', 'Cardestroy')
                ->name('car.dynamic.price.destroy');

            Route::get('/', 'CarBulkUpload')
                ->name('car.bulk.index');

            Route::post('/car-process', 'CarUpload')
                ->name('car.bulk.upload.process');

            Route::get('/car-template', 'CarDownloadTemplate')
                ->name('car.bulk.upload.template');

            Route::get('/room-index', 'Roomindex')
                ->name('room.index');

            Route::post('/room-process', 'Roomupload')
                ->name('room.bulk.upload.process');

            Route::get('/room-template', 'downloadTemplate')
                ->name('room.bulk.upload.template');
        });

});