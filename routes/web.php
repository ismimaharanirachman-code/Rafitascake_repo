<?php

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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\PelangganController;
Route::resource('pelanggan', PelangganController::class);

<<<<<<< HEAD

use App\Http\Controllers\SupplierController;
Route::resource('supplier', SupplierController::class);
=======
Route::resource('pelang', PelangganController::class);
>>>>>>> e846618a96b40408dbfe7e1e047fcc56a884cbf2
