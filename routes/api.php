<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('buyers',\App\Http\Controllers\Buyer\BuyersController::class)->only(['index','show']);
Route::resource('users', \App\Http\Controllers\User\UsersController::class);
Route::resource('sellers', \App\Http\Controllers\Seller\SellersController::class)->only(['index', 'show']);