<?php

use App\Http\Controllers\Api\PartnerController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Partner API for broadtechinfosys.com.np (Authorization: Bearer PARTNER_API_TOKEN)
Route::prefix('partner')->middleware('partner')->group(function () {
    Route::get('/products', [PartnerController::class, 'products']);
    Route::post('/orders', [PartnerController::class, 'storeOrder']);
    Route::get('/orders/{reference}', [PartnerController::class, 'showOrder']);
});
