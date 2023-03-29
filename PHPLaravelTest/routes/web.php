<?php

declare(strict_types=1);

use App\Http\Controllers\LocationController;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Style Guide
Route::get('/style-guide', 'PageController@showStatic')
    ->middleware(['devOnly'])
    ->defaults('view', 'style-guide')
    ->name('style-guide');

// Create Account
Route::get('create-account', 'Auth\RegisterController@showRegistrationForm')->name('register')->middleware('includeInRoutesFormfield');
Route::post('create-account', 'Auth\RegisterController@register');

// Password Reset
Route::group(['prefix' => '/password'], function () {
    Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');

    Route::get('/reset/success', 'Auth\ResetPasswordController@showSuccess')->name('password.success');

    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
});

// Re-activate Account
Route::group(['prefix' => '/reactivate'], function () {
    Route::get('/email/{email}', 'Auth\ReactivateController@index')->name('reactivate.index');

    Route::get('/reset/success', 'Auth\ReactivateController@showSuccess')->name('reactivate.success');

    Route::get('/reset/{token}/{legacy?}', 'Auth\ReactivateController@showResetForm')->name('reactivate.reset');
});

// Sign In/Sign Out
Route::get('sign-in', ['uses' => 'Auth\LoginController@showLoginForm', 'as' => 'login'])->middleware('includeInRoutesFormfield');
Route::post('sign-in', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

// SSO
Route::group([ 'prefix' => '/sso', 'excluded_middleware' => [ 'checkOAuth' ] ], function () {
    Route::get('after-login', 'Auth\LoginController@ssoAfterLogin')->name('sso.after-login');
    Route::get('callback', 'Auth\LoginController@ssoCallback');
    Route::get('after-logout', 'Auth\LoginController@ssoAfterLogout')->name('sso.after-logout');
    Route::get('after-register', 'Auth\RegisterController@ssoAfterRegister')->name('sso.after-register');
    Route::get('after-forgot-password', 'Auth\ForgotPasswordController@ssoAfterForgotPassword')->name('sso.after-forgot-password');
});

// Email not verified
Route::get('email/verify', ['uses' => 'Auth\VerificationController@show'])->name('email.verify')->middleware('auth');

// Voyager routes
Route::group(['prefix' => '/admin'], function () {
    Voyager::routes();

    Route::redirect('/landing', Config::get('admin.urls.landing'))->name('admin.landing');
    Route::redirect('/login', route('login'));

    Route::group(['middleware' => 'admin.user'], function () {
        Route::get('/', ['uses' => 'Admin\VoyagerController@index', 'as' => 'voyager.dashboard']);

        Route::get('logs', function () {
            return redirect('/admin/compass?logs=1');
        })->name('voyager.logs');

        Route::get('/sales-tax', ['uses' => 'Admin\SalesTaxController@index', 'as' => 'voyager.sales-tax.index']);
        Route::post('/sales-tax', ['uses' => 'Admin\SalesTaxController@update', 'as' => 'voyager.sales-tax.update']);
        Route::get('/files/order/{file_category_id?}', ['uses' => 'Admin\FileController@order', 'as' => 'voyager.files.order']);
        Route::post('/files/order/{file_category_id}', ['uses' => 'Admin\FileController@update_order']);
        Route::get('/orders-export/csv', [ 'uses' => 'Admin\OrderController@exportCsv' ])->name('voyager.orders.csv');
        Route::get('/orders/{id}/print', [ 'uses' => 'Admin\OrderController@print' ])->name('voyager.orders.print');
        Route::get('/sync-wash-clubs', ['uses' => 'Admin\ProductVariantController@syncWithWashConnect', 'as' => 'voyager.products.sync-wash-clubs']);
    });
});

// Files
Route::group(['prefix' => '/files', 'middleware' => 'files'], function () {
    Route::get('/{id}/file', ['uses' => 'FileController@file', 'as' => 'files.file']);
    Route::get('/{id}/thumbnail', ['uses' => 'FileController@thumbnail', 'as' => 'files.thumbnail']);
});

// Company Files
Route::get('/company-files', 'FileController@companyFilesIndex')->middleware('companyFiles')->name('company-files.index');

// Shopping Cart
Route::group(['prefix' => '/cart'], function () {
    Route::get('/', 'CartController@index')->name('cart.index')->middleware('includeInRoutesFormfield');
    Route::post('/add-product', 'CartController@addProduct')->name('cart.add-product');
    Route::post('/update-row', 'CartController@updateRow')->name('cart.update-row');
    Route::post('/remove-row', 'CartController@removeRow')->name('cart.remove-row');
    Route::post('/add-coupon', 'CartController@addCoupon')->name('cart.add-coupon');
    Route::post('/remove-coupon', 'CartController@removeCoupon')->name('cart.remove-coupon');
});

// Terms
Route::group(['prefix' => '/terms'], function () {
    Route::get('/wash-clubs', 'TermsContentController@showMembershipTerms');
});

// Checkout
Route::group(['prefix' => '/checkout'], function () {
    Route::group(['middleware' => ['non-empty-cart']], function () {
        Route::get('/', 'CheckoutController@index')->name('checkout.index');

        Route::get('/account', 'CheckoutController@showAccount')->name('checkout.account');
        Route::post('/account', 'CheckoutController@storeAccount')->name('checkout.store-account');
        Route::get('sso/after-checkout-register', 'Auth\RegisterController@ssoAfterCheckoutRegister')->name('sso.after-checkout-register');

        Route::get('/payment-methods', 'CheckoutController@showPaymentMethods')->name('checkout.payment-methods.index')->middleware('require-checkout-ready-for-payment');
        Route::post('/payment-methods', 'CheckoutController@storePaymentMethodSelection')->name('checkout.payment-methods.store')->middleware('require-checkout-ready-for-payment');
        Route::get('/payment-methods/new', 'CheckoutController@editPaymentMethod')->name('checkout.payment-methods.edit')->middleware([ 'require-checkout-ready-for-payment', 'verified' ]);

        Route::get('/review', 'CheckoutController@showReview')->name('checkout.review');

        Route::get('/shipping', 'CheckoutController@showShipping')->name('checkout.shipping');
        Route::post('/shipping', 'CheckoutController@storeShipping');

        Route::group(['prefix' => '/memberships'], function () {
            Route::get('/home-car-wash', 'CheckoutController@showHomeCarWash')->name('checkout.memberships.home-car-wash.show');
            Route::get('/home-car-wash/edit', 'CheckoutController@editHomeCarWash')->name('checkout.memberships.home-car-wash.edit');
            Route::post('/home-car-wash/edit', 'CheckoutController@storeHomeCarWash')->name('checkout.memberships.home-car-wash.store');

            Route::group(['prefix' => '/{index}'], function () {
                Route::get('/details', 'CheckoutController@showMembershipDetails')->name('checkout.memberships.details.edit');
                Route::post('/details', 'CheckoutController@storeMembershipDetails')->name('checkout.memberships.details.store');
                Route::get('/modification-summary', 'CheckoutController@showMembershipModificationSummary')->name('checkout.memberships.modification-summary');
            });

            Route::get('/terms', 'CheckoutController@showMembershipTerms')->name('checkout.memberships.terms')->middleware('require-checkout-ready-for-payment');
        });
    });

    // The following routes are not subject to the Checkout Middleware
    Route::post('/payment-methods/new', 'CheckoutController@storeNewPaymentMethod')->name('checkout.payment-methods.storeNew')->middleware([ 'require-checkout-ready-for-payment', 'verified' ]);

    Route::get('/success/{orderId}/{accessToken?}', 'CheckoutController@showSuccess')->name('checkout.success');
    Route::get('sso/after-order-success-register/{orderId}/{accessToken?}', 'Auth\RegisterController@ssoAfterOrderSuccessRegister')->name('sso.after-order-success-register');
    Route::get('/success/{orderId}/{accessToken?}/registered', 'CheckoutController@showRegistrationSuccess')->name('checkout.registration-success');
});

// Products
Route::get('/products/{id}/{slugifiedName?}', 'ProductController@show')->name('products.show');

// Shop
Route::get('/shop/modify-membership/{washConnectId}', 'ShopController@showModifyMembership')->name('shop.modify-membership')->middleware('auth');

// My Account
Route::group(['prefix' => '/my-account', 'middleware' => ['auth']], function () {
    Route::get('/account/cancel', 'MyAccountController@cancelAccount')->name('my-account.account.cancel');
    Route::post('/account/cancel', 'MyAccountController@destroyAccount');

    Route::get('/account/manage', 'MyAccountController@manageAccount')->name('my-account.account.manage');

    // Route::get('/billing-information/edit', 'MyAccountController@editBilling')->name('my-account.billing-information.edit');

    Route::get('/contact-info-shipping-address/edit', 'MyAccountController@editContactInfoAndShippingAddress')->name('my-account.contact-info-shipping-address.edit');
    Route::post('/contact-info-shipping-address/edit', 'MyAccountController@updateContactInfoAndShippingAddress');

    Route::get('/notification-preferences/edit', 'MyAccountController@editNotificationPreferences')->name('my-account.notification-preferences.edit');
    Route::post('/notification-preferences/edit', 'MyAccountController@updateNotificationPreferences');

    Route::group(['prefix' => '/orders'], function () {
        Route::get('/{id}', 'OrderController@show')->name('my-account.orders.show');
    });

    Route::get('/password/edit', 'MyAccountController@editPassword')->name('my-account.password.edit')->middleware('verified');
    Route::post('/password/edit', 'MyAccountController@updatePassword')->middleware('verified');

    Route::get('/payment-methods', 'MyAccountController@showPaymentMethods')->name('my-account.payment-methods.show');
    Route::get('/payment-methods/edit', 'MyAccountController@editPaymentMethod')->name('my-account.payment-methods.edit')->middleware('verified');
    Route::post('/payment-methods/edit', 'MyAccountController@updatePaymentMethod')->middleware('verified');

    Route::group(['prefix' => '/memberships'], function () {
        Route::get('/{id}', 'MembershipController@show')->name('my-account.memberships.show');
        Route::post('/{id}/terminate', 'MembershipController@storeTerminate')->name('my-account.memberships.terminate');
        Route::post('/{washConnectId}/cancel-modification', 'MembershipController@storeCancelModification')->name('my-account.memberships.cancel-modification');
        Route::post('/{washConnectId}/cancel-termination', 'MembershipController@storeCancelTermination')->name('my-account.memberships.cancel-termination');
        Route::post('/{washConnectId}/reactivate', 'MembershipController@storeReactivate')->name('my-account.memberships.reactivate');
    });

    Route::get('/home-car-wash/edit', 'MyAccountController@editHomeCarWash')->name('my-account.home-car-wash.edit');
    Route::post('/home-car-wash/edit', 'MyAccountController@storeHomeCarWash')->name('my-account.home-car-wash.store');

    /* index has to go last on this one, because it accepts a wildcard $expandedSection after the base path */
    Route::get('/{expandedSection?}', 'MyAccountController@index')
        ->name('my-account.index')
        ->middleware('includeInRoutesFormfield');
});

// Locations
Route::group(['prefix' => '/locations'], function () {
    Route::get('/', function (Request $request) {
        // 301 redirects for legacy search queries
        if ($request->has('search_service')) {
            $legacyService = (int)$request->get('search_service', 0);

            if ($legacyService === 10) {
                $slug = 'tunnel-car-wash';
            } elseif ($legacyService === 11) {
                $slug = 'self-serve-car-wash';
            } elseif ($legacyService === 12) {
                return Redirect::to('/locations', 301);
            } elseif ($legacyService === 13) {
                $slug = 'touchless-car-wash';
            } elseif ($legacyService === 15 || $legacyService === 17) {
                $slug = 'gas';
            } elseif ($legacyService === 18) {
                $slug = 'hungry-bear-market';
            }

            if (isset($slug)) {
                return Redirect::to('/locations?services=["'.$slug.'"]', 301);
            }
        }

        return (new LocationController())->index($request);
    })->name('locations.index');

    // Legacy route from old site
    Route::get('/near_me', function () {
        return Redirect::to(cms_route('locations'), 301);
    });

    // Legacy routes from old site
    Route::get('/brown-bear-{siteNumber}', function ($siteNumber) {
        $location = Location::where('site_number', $siteNumber)
            ->orWhere('site_number', 'LIKE', $siteNumber.'/%')
            ->orWhere('site_number', 'LIKE', '%/'.$siteNumber)
            ->first();

        if (!$location) {
            return Redirect::to('/locations', 301);
        }

        return Redirect::to($location->url, 301);
    });

    Route::get('/{dynamicSlug}', 'LocationController@show')
        ->name('locations.show');
});

// Wash Cards
Route::group([ 'prefix' => '/wash-cards' ], function () {
    Route::get('/{cardNumber?}', 'CheckWashCardBalanceController@index');
});

include 'redirects.php';

/* WILDCARD CMS ROUTE MUST GO LAST */
// Page CMS routes
Route::get('/{path?}', 'PageController@show')
    ->name('page')
    ->defaults('path', '/')
    ->where('path', '.*');
