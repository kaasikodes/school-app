<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LevelsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/classes', [LevelsController::class, 'index'])->name('levels.index');
    Route::get('/score/{id}', [DashboardController::class, 'create'])->name('score.create');
    Route::post('/score', [DashboardController::class, 'store'])->name('score.store');
});

Route::get('/classes', [LevelsController::class, 'index'])->name('levels.index');

