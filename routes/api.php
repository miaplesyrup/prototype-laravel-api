<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfirmationController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    return response('ok!');
});
// Protected routes
Route::middleware('auth:api')->group(function () {
    // Protected route that requires authentication with Passport
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/confirm-email/{token}', 'ConfirmationController@confirmEmail');
Route::match(['options', 'post'], '/api/signup', [AuthController::class, 'signup'])
    ->middleware('cors');

Route::get('/dashboard', function () {
    // Only verified users may access this route
})->middleware('verified');

Route::get('/confirm/{id}/{token}', [VerificationController::class, 'verify'])
    ->name('verification.verify');

