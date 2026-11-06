<?php

use App\Http\Controllers\ZooController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ZooController::class, 'index'])->name('zoo');
Route::post('/adopt', [ZooController::class, 'adopt'])->name('zoo.adopt');
Route::post('/pets/{index}/{action}', [ZooController::class, 'act'])
    ->whereNumber('index')
    ->name('zoo.act');
Route::post('/reset', [ZooController::class, 'reset'])->name('zoo.reset');
