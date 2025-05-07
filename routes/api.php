<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FAQController;

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


/*
|--------------------------------------------------------------------------
| API Routes for FAQ
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for the FAQ module.
|
*/
Route::prefix('faqs')->group(function () {
    Route::get('/', [FAQController::class, 'index']);
    Route::post('/', [FAQController::class, 'store']);
    Route::get('/{id}', [FAQController::class, 'show']);
    Route::put('/{id}', [FAQController::class, 'update']);
    Route::delete('/{id}', [FAQController::class, 'destroy']);
});
