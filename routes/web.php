<?php

use App\Http\Controllers\ZooController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ZooController::class, 'index'])->name('zoo');
Route::post('/adopt', [ZooController::class, 'adopt'])->name('zoo.adopt');
Route::post('/pets/{id}/{action}', [ZooController::class, 'act'])
    ->whereNumber('id')
    ->name('zoo.act');
Route::delete('/pets/{id}', [ZooController::class, 'release'])
    ->whereNumber('id')
    ->name('zoo.release');
Route::post('/reset', [ZooController::class, 'reset'])->name('zoo.reset');
