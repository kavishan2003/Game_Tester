<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/games', [GameController::class, 'getGames']);

Route::post('/contact', [GameController::class, 'submit'])
     ->name('contact.submit');