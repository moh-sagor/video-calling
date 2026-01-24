<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CallController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = App\Models\User::where('id', '!=', auth()->id())->get();
    return view('dashboard', ['users' => $users]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/call/start', [CallController::class, 'callUser']);
    Route::post('/call/accept', [CallController::class, 'acceptCall']);
    Route::post('/call/reject', [CallController::class, 'rejectCall']);
    Route::post('/call/ice-candidate', [CallController::class, 'iceCandidate']);

    Route::post('/meet/create', [App\Http\Controllers\MeetingController::class, 'create'])->name('meet.create');
    Route::get('/meet/{code}', [App\Http\Controllers\MeetingController::class, 'join'])->name('meet.join');
});

require __DIR__.'/auth.php';
