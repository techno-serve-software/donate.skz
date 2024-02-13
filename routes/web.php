<?php

Route::get('/', 'HomeController@index')->name('home');
Route::get('/donation', 'HomeController@donation')->name('home');

Route::get('quick-donate', 'QuickDonationController');
Route::get('zakat-donate', 'ZakatDonationController');

Route::post('programs', 'HomeController@programs')->name('program');
Route::get('qurbani', 'HomeController@qurbaniPage')->name('qurbani_page');
Route::post('program-country', 'HomeController@program_country')->name('program_country');
Route::post('program-rate', 'HomeController@program_rate')->name('program_rate');
Route::post('fetch-qurbani-programs', 'HomeController@fetchQurbaniPrograms')->name('fetchQurbaniPrograms');
Route::post('add-to-cart', 'HomeController@addToCart')->name('add_to_cart');
Route::post('cart-count', 'HomeController@itemCount')->name('cart_count');
Route::post('fetch-cart-data', 'HomeController@fetchCartData')->name('fetchCartData');
Route::post('update-quantity', 'HomeController@updateQuantity')->name('update_quantity');
Route::post('delete-item', 'HomeController@deleteItem')->name('delete_item');
Route::get('payment-success', 'HomeController@paymentSuccess')->name('payment.success');
Route::get('payment-fail', 'HomeController@paymentFailure')->name('payment.fail');
Route::get('payment-cancel', 'HomeController@paymentCancel')->name('payment.cancel');

Route::group(['prefix' => 'account'], function () {
    Route::get('profile', 'AccountController@profile');
    Route::get('changePassword', 'AccountController@changePassword');
    Route::get('address', 'AccountController@address');
    Route::get('donation', 'AccountController@donation');
    Route::get('direct-debit', 'AccountController@direct_debit');
    Route::post('updateDonorData', 'AccountController@updateDonorData');
    Route::post('update-password', 'AccountController@updatePassword');
    Route::post('ddPaymentDetails', 'AccountController@ddPaymentDetails');
});

Route::get('checkout', 'CheckoutController@index')->name('checkout');
Route::post('donate', 'CheckoutController@donate')->name('donate');

Route::post('addNewAddress','CheckoutController@addNewAddress')->name('addNewAddress');
Route::get('newAddressForm','CheckoutController@newAddressForm')->name('newAddressForm');
Route::get('response','CheckoutController@netPayResponse')->name('response');
Route::post('get_paf_data','CheckoutController@get_paf_data')->name('get_paf_data');
Route::post('city','CheckoutController@city')->name('city');
Route::post('city_name','CheckoutController@city_name')->name('city_name');

Route::get('stripe-checkout', 'CheckoutController@stripePayment')->name('stripe-checkout');

Route::post('stripe', 'StripeController@stripe')->name('stripe');
Route::post('stripe/init', 'StripeController@init')->name('stripe.init');
Route::post('payment', 'StripeController@payStripe')->name('stripe.payment');
Route::get('check-success', 'StripeController@checkSuccessStripe')->name('stripe.checkSuccess');
Route::get('payment/success', 'StripeController@paymentSuccess')->name('stripe.success');
Route::get('payment/cancel', 'StripeController@paymentCancelled')->name('stripe.cancel');
Route::post('webhook', 'StripeController@webhook')->name('stripe.webhook');

Route::post('login', 'LoginController@login')->name('login');
Route::get('logout', 'LoginController@logout')->name('logout');
Route::post('signup', 'LoginController@signup')->name('signup');
Route::post('reset-password','LoginController@resetPassword')->name('resetPassword');
Route::get('reset-page','LoginController@resetPage')->name('resetPage');
Route::post('reset-token','LoginController@resetToken')->name('resetToken');

Route::prefix('paypal')->name('paypal.')->group(function () {
    Route::get('checkout-success', 'CheckoutController@getExpressCheckoutSuccess')->name('checkout-success');
    Route::get('ipn', 'CheckoutController@paypalIpn')->name('ipn');
    Route::post('notify', 'CheckoutController@notify')->name('notify');
});
// Route::get('paypal/getIndex', 'PayPalController@getIndex');
// Route::get('paypal/getExpressCheckout', 'PayPalController@getExpressCheckout');
// Route::get('paypal/ec-checkout-success', 'PayPalController@getExpressCheckoutSuccess');
// Route::get('paypal/adaptive-pay', 'PayPalController@getAdaptivePay');
// Route::post('paypal/notify', 'PayPalController@notify');
