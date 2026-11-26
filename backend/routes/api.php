<?php

use App\Http\Controllers\Api\SensorReadingController;
use App\Http\Controllers\Api\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/sensor-readings', [SensorReadingController::class, 'index']);
Route::post('/sensor-readings', [SensorReadingController::class, 'store']);
Route::post('/telegram/webhook', TelegramWebhookController::class);
