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

    Route::prefix('/bill/{bill_id}')->middleware('can:bill')->group(function () {
        Route::get('/base', 'Bill\Index@base');
        Route::get('/', 'Bill\Index@list');
        Route::get('/{info_id}', 'Bill\Index@info')->where('info_id', '\d+');
        Route::post('/', 'Bill\Index@createOrUpdate');
        Route::delete('/', 'Bill\Index@delete');

        Route::prefix('/setting')->group(function () {

            Route::prefix('/payee')->group(function () {
                Route::get('/', 'Bill\Setting\Payee@list');
                Route::post('/', 'Bill\Setting\Payee@createOrUpdate');
            });

            Route::prefix('/currency')->group(function () {
                Route::get('/', 'Bill\Setting\Currency@list');
                Route::post('/', 'Bill\Setting\Currency@createOrUpdate');
            });

            Route::prefix('/subject')->group(function () {
                Route::get('/parent', 'Bill\Setting\Subject@parent');
                Route::get('/', 'Bill\Setting\Subject@list');
                Route::post('/', 'Bill\Setting\Subject@createOrUpdate');
                Route::delete('/', 'Bill\Setting\Subject@delete');
            });
        });
    });
});
