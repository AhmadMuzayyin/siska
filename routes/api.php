<?php

use App\Http\Controllers\Api\RfidScanController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/rfid/scan', RfidScanController::class)
    ->middleware(['rfid.device', 'throttle:30,1'])
    ->name('rfid.scan');

Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle'])
    ->name('telegram.webhook');
