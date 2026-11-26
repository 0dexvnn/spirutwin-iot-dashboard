<?php

use App\Http\Controllers\Admin\PetaniController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', DashboardController::class)->name('dashboard');
Route::get('/dashboard', DashboardController::class);
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/petani', [PetaniController::class, 'index'])->name('petani.index');
    Route::get('/petani/create', [PetaniController::class, 'create'])->name('petani.create');
    Route::post('/petani', [PetaniController::class, 'store'])->name('petani.store');
    Route::get('/petani/{petani}/edit', [PetaniController::class, 'edit'])->name('petani.edit');
    Route::put('/petani/{petani}', [PetaniController::class, 'update'])->name('petani.update');
    Route::delete('/petani/{petani}', [PetaniController::class, 'destroy'])->name('petani.destroy');
});
