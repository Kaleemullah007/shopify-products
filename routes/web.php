<?php

use App\Http\Controllers\ShopifyProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php
Route::get('/create-shopify-product', [ShopifyProductController::class, 'index']);
Route::post('/create-shopify-product', [ShopifyProductController::class, 'store']);