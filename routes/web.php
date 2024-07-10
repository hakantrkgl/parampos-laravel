<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('showPaymentForm');
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('processPayment');



Route::get('/test', function () {
    return 'Merhaba Dünya!';
});

Route::get('/', function () {
    return view('welcome');
});
