<?php
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
Route::get('/products', [ApiController::class, 'products']);
Route::get('/products/{product:slug}', [ApiController::class, 'product']);
Route::post('/quotes', [ApiController::class, 'quote'])->middleware('throttle:10,1');
Route::post('/import-requests', [ApiController::class, 'importRequest'])->middleware('throttle:10,1');
Route::post('/contacts', [ApiController::class, 'contact'])->middleware('throttle:10,1');
Route::get('/track/{number}', [ApiController::class, 'track']);
Route::get('/dashboard', [ApiController::class, 'dashboard']);
