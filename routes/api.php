<?php

use App\Http\Controllers\Api\RfidScanController;
use Illuminate\Support\Facades\Route;

Route::post('/rfid/scan', RfidScanController::class)
    ->middleware(['rfid.device', 'throttle:30,1'])
    ->name('rfid.scan');
