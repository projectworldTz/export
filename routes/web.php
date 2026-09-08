<?php

use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminBrandingController;
use Illuminate\Support\Facades\Route;
Route::get('/language/{locale}',function(string $locale){abort_unless(in_array($locale,['en','fr']),404);session(['locale'=>$locale]);return back();})->name('language.switch');
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/products', [WebsiteController::class, 'products'])->name('products.index');
Route::get('/products/{product:slug}', [WebsiteController::class, 'product'])->name('products.show');
Route::view('/about', 'pages.about')->name('about');
Route::view('/services', 'pages.services')->name('services');
Route::get('/quote', [WebsiteController::class, 'quoteForm'])->name('quotes.create');
Route::post('/quote', [WebsiteController::class, 'quoteStore'])->middleware('throttle:10,1')->name('quotes.store');
Route::get('/import-request', [WebsiteController::class, 'importForm'])->name('imports.create');
Route::post('/import-request', [WebsiteController::class, 'importStore'])->middleware('throttle:10,1')->name('imports.store');
Route::get('/tracking', [WebsiteController::class, 'tracking'])->name('tracking');
Route::post('/tracking', [WebsiteController::class, 'track'])->middleware('throttle:20,1')->name('tracking.search');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::post('/contact', [WebsiteController::class, 'contactStore'])->middleware('throttle:10,1')->name('contact.store');
Route::post('/products/{product}/save/{list}',[WebsiteController::class,'saveProduct'])->whereIn('list',['wishlist','enquiry','cart'])->name('products.save');
Route::middleware('guest')->group(function(){Route::get('/admin/login',[AuthController::class,'showLogin'])->name('admin.login');Route::post('/admin/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('admin.login.submit');});
Route::prefix('admin')->middleware('auth')->group(function(){Route::get('/',[WebsiteController::class,'admin'])->name('admin');Route::post('/logout',[AuthController::class,'logout'])->name('admin.logout');Route::get('/branding',[AdminBrandingController::class,'edit'])->name('admin.branding.edit');Route::put('/branding',[AdminBrandingController::class,'update'])->name('admin.branding.update');Route::name('admin.')->group(function(){Route::resource('products',AdminProductController::class)->except('show');Route::delete('product-images/{image}',[AdminProductController::class,'destroyImage'])->name('product-images.destroy');});});
