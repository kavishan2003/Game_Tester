<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PayEmailController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [GameController::class, 'getGames']);

Route::post('/contact', [GameController::class, 'submit'])
     ->name('contact.submit');

Route::post('/paypal',[PayEmailController::class,'save']);