<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('showPaymentForm');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('processPayment');

Route::get('/payment3D', [PaymentController::class, 'showPayment3DForm'])->name('payment3D.form');
Route::post('/payment3D', [PaymentController::class, 'process3DPayment'])->name('payment3D.process');
Route::post('/payment/callback', [PaymentController::class, 'paymentCallback'])->name('payment.callback');


Route::get('/test', function () {
    return 'Merhaba Dünya!';
});

Route::get('/', function () {
    return view('welcome');
});
