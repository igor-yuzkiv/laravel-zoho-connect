<?php

use Illuminate\Support\Facades\Route;
use ZohoConnect\Http\Controllers\AuthenticatorController;


return [
    Route::get("/", [AuthenticatorController::class, "index"]),
    Route::get("callback", [AuthenticatorController::class, "callback"]),
    Route::post("authorization", [AuthenticatorController::class, "authorization"]),
];
