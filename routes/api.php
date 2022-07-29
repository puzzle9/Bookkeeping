<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Index@start');

Route::prefix('/sign')->group(function () {
    Route::post('/in', 'Sign@in');
    Route::post('/reg', 'Sign@reg');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/file')->group(function () {
        Route::post('/', 'File@upload');
        Route::delete('/', 'File@delete');
    });
});
