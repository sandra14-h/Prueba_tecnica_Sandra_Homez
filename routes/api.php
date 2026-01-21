<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ImportExportController;
use App\Exports\ProductsExport;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('products/export', [ImportExportController::class, 'export']);
    Route::post('products/import', [ImportExportController::class, 'import']);
    Route::apiResource('products', ProductController::class);
});




