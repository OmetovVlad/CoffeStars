<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\MainController::class, 'index'])->name('index');

Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');

Route::get('/admin/products', [App\Http\Controllers\AdminProductsController::class, 'index'])->name('admin.product.index');
Route::get('/admin/products/create', [App\Http\Controllers\AdminProductsController::class, 'create'])->name('admin.product.create');
Route::post('/admin/products', [App\Http\Controllers\AdminProductsController::class, 'store'])->name('admin.product.store');
Route::get('/admin/products/{product}', [App\Http\Controllers\AdminProductsController::class, 'show'])->name('admin.product.show');
Route::get('/admin/products/{product}/edit', [App\Http\Controllers\AdminProductsController::class, 'edit'])->name('admin.product.edit');
Route::patch('/admin/products/{product}', [App\Http\Controllers\AdminProductsController::class, 'update'])->name('admin.product.update');
Route::delete('/admin/products/{product}', [App\Http\Controllers\AdminProductsController::class, 'destroy'])->name('admin.product.delete');

Route::get('/order', [App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
Route::post('/order', [App\Http\Controllers\OrderController::class, 'store'])->name('order.store');

Route::get('/admin/orders', [App\Http\Controllers\AdminOrdersController::class, 'index'])->name('admin.order.index');
Route::patch('/admin/orders/{order}', [App\Http\Controllers\AdminOrdersController::class, 'update'])->name('admin.order.update');
