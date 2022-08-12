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

    Route::prefix('/book')->group(function () {
        Route::prefix('/account')->group(function () {
            Route::get('/', 'Book\Account@list');
            Route::post('/', 'Book\Account@createOrUpdate');
            Route::delete('/', 'Book\Account@delete');
        });
    });
});
