<?php

use App\Http\Controllers\Api\RFIDController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(RFIDController::class)->group(function () {
    Route::post('/rfid/scan', 'scan')->name('rfid.scan');
});
