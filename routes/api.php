<?php

use App\Http\Controllers\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [Users::class, 'store']);
Route::post('/logins', [Users::class, 'logins']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/users', [Users::class, 'all']);
    Route::post('/logout', [Users::class, 'logout']);

});
