<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/groups', [ChatController::class, 'createGroup']);
    Route::get('/groups', [ChatController::class, 'fetchGroups']);
    Route::post('/users/search', [\App\Http\Controllers\SearchController::class, 'searchByEmail']);
    Route::post('/messages/mark-read', [ChatController::class, 'markAsRead']);
    Route::get('/messages/unread-counts', [ChatController::class, 'fetchUnreadCounts']);
});
