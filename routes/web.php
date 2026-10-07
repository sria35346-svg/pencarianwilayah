<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataCrawlingController;
use App\Http\Controllers\DataCrawlingImportController;

Route::get('/', [DataCrawlingController::class, 'index']);
Route::post('/import', [DataCrawlingImportController::class, 'import'])
    ->name('data-crawling.import');
Route::get('/export', [DataCrawlingImportController::class, 'export'])
    ->name('data-crawling.export');