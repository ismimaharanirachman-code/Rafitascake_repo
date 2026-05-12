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

use App\Http\Controllers\BahanBakuController;
Route::resource('BahanBaku', BahanBakuController::class); 

use App\Http\Controllers\SupplierController;
Route::resource('supplier', SupplierController::class);

use App\Http\Controllers\PegawaiController;
Route::resource('Pegawai', PegawaiController::class);

use App\Http\Controllers\CoaController;
Route::resource('coa', CoaController::class);

use App\Http\Controllers\ProdukController;
Route::resource('Produk', ProdukController::class);

use App\Http\Controllers\PengirimanEmailController;
Route::get('/kirim-email/{id}',[PengirimanEmailController::class, 'prosesKirimEmail']);

use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/penjualan/pdf', function () {
    $penjualan = Penjualan::all();
    $pdf = Pdf::loadView('pdf.penjualan', compact('penjualan'));
    return $pdf->download('daftar-penjualan.pdf');

});
use App\Http\Controllers\MidtransController;
Route::get('/midtrans', [MidtransController::class, 'index']);

Route::get('/midtrans-payment', function () {
    return view('midtrans.index', [
        'snapToken' => session('snapToken')
    ]);
});