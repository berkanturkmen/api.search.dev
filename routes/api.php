<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\ForgotController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetController;

use App\Http\Controllers\API\PreferenceController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\ExploreController;

Route::prefix('auth')->group(function () {
	Route::post('/forgot', ForgotController::class);
	Route::post('/login', LoginController::class);
	Route::post('/register', RegisterController::class);
	Route::post('/reset', ResetController::class);
});

Route::middleware('auth:api')->group(function () {
	Route::get('/user', function (Request $request) {
		return $request->user();
	});

	Route::patch('/preference', PreferenceController::class);
	Route::get('/search', SearchController::class);
	Route::get('/explore', ExploreController::class);
});
