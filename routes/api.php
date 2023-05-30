<?php

use App\Http\Controllers\DrinkController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(DrinkController::class)->group(function () {
    Route::post('drink', 'create');
    Route::get('drink', 'findAll');
    Route::get('drink/{id}', 'findOne');
    Route::patch('drink/{id}', 'patch');
});
Route::controller(WarehouseController::class)->group(function () {
    Route::post('warehouse', 'create');
    Route::get('warehouse', 'findAll');
    Route::get('warehouse/{id}', 'findOne');
    Route::patch('warehouse/{id}', 'patch');
});
Route::controller(StockController::class)->group(function () {
    Route::post('warehouse/{id}/stock', 'create');
    Route::get('stock', 'findAll');
    Route::get('stock/{id}', 'findOne');
    Route::get('warehouse/{id}/stock', 'findByWharehouse');
    Route::patch('stock/{id}', 'patch');
    Route::delete('stock/{id}', 'delete');
});
Route::controller(OperationController::class)->group(function () {
    Route::post('warehouse/{id}/operation', 'create');
    Route::get('operation', 'findAll');
    Route::get('operation/{id}', 'findOne');
    Route::get('warehouse/{id}/operation', 'findByWharehouse');
});