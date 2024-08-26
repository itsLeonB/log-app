<?php

use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LogController::class, 'index']);
Route::post('/log/{status}', [LogController::class, 'log'])->name('log');
