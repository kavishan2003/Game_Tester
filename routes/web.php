<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\PayEmailController;
use App\Http\Controllers\TransactionController;



Route::get('/Transactions', [TransactionController::class, 'index']);

Route::get('/', [GameController::class, 'getGames']);

Route::post('/contact/send', [CaptureController::class, 'send'])->name('contact.send');

Route::post('/paypal', [PayEmailController::class, 'save']);
