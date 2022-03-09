<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\ProcessedFile\ProcessedFileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1/')->group(function () {

    $exceptActions = ['create', 'edit'];

    # PRODUCT
    Route::resource('products', ProductController::class)->except($exceptActions);

    # PROCESSED FILES
    Route::get('processed-files/{id}', [ProcessedFileController::class, 'show'])->name('processed-files.show');
});
