<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/marketing/send', [\App\Http\Controllers\SocialMarketingController::class, 'sendToSocialNetworks']);