<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('ask/answer', \App\Http\Controllers\AskForAnswerController::class);
Route::post('ask/clear', \App\Http\Controllers\ClearResults::class);
Route::post('ask/question', \App\Http\Controllers\ShowQuestionController::class);
