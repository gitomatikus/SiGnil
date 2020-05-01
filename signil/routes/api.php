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
Route::post('question/choose', \App\Http\Controllers\ChooseQuestionController::class);
Route::post('question/show', \App\Http\Controllers\ShowQuestionController::class);
Route::post('question/hide', \App\Http\Controllers\HideQuestionController::class);
Route::post('round/change', \App\Http\Controllers\ChangeRoundController::class);
Route::post('answer/show', \App\Http\Controllers\ShowAnswerController::class);
Route::post('file', \App\Http\Controllers\LoadPackController::class);
Route::get('file/{hash}', \App\Http\Controllers\GetPackController::class);
Route::post('user', \App\Http\Controllers\AddUserController::class);
Route::put('user', \App\Http\Controllers\UpdateUserController::class);
Route::delete('user', \App\Http\Controllers\DeleteUserController::class);
