<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CaptureController;
use App\Http\Controllers\PayEmailController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [GameController::class, 'getGames']);

Route::post('/contact/send', [CaptureController::class, 'send'])->name('contact.send');

// Route::post('/contact/send', [CaptureController::class, 'send'])->name('contact.send');

// Route::post('/contact/send', [CaptureController::class, 'send'])
//      ->name('contact.submit');

Route::post('/paypal',[PayEmailController::class,'save']);
