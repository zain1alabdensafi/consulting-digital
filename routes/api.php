<?php

use App\Http\Controllers\api\common_controller;
use App\Http\Controllers\api\experts_controller;
use App\Http\Controllers\api\time_controller;
use App\Http\Controllers\Api\users_controller;
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

// without authentication
Route::post("user_regester", [users_controller::class, "user_regester"]);
Route::post("expert_regester", [experts_controller::class, "expert_regester"]);
Route::post("login", [common_controller::class, "login"]);

// using authentication
Route::group(["middleware" => ['auth:sanctum']], function () {
    // expert controller
    Route::get("profile/{id}", [experts_controller::class, "expert_by_id"]);
    Route::get("expert-by-consultatoin/{consult_id}", [experts_controller::class, "ExpertsByConsultation"]);
    Route::get("get_all_experts", [experts_controller::class, "get_all_experts"]);
    // common controller
    Route::get("logout", [common_controller::class, "logout"]);
    Route::get("search", [common_controller::class, "Search"]);
    // time controller
    Route::post("time_available", [time_controller::class, "time_available"]);
    Route::post("booking", [time_controller::class, "booking"]);
    Route::get("free_time/{expert_id}", [time_controller::class, "free_time"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
