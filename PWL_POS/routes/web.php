<?php

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
Route::pattern('id', '[0-9]+'); // artinya ketika ada paramater {id}, maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'store']);

Route::middleware(['auth'])->group(function(){
    Route::get('/', [WelcomeController::class,'index']);

    // user
    Route::prefix('user')->middleware(['authorize:ADM'])->group(function(){
    Route::get('/', [UserController::class, 'index']);          // menampilkan halaman awal user
    Route::post('/list', [UserController::class, 'list']);      //menampilkan data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);   // menampilkan halaman form tambah user
    Route::post('/', [UserController::class, 'store']);         // menampilkan data user baru
    Route::get('/create_ajax', [UserController::class, 'create_ajax']); // menampilkan halaman form tambah user Ajax
    Route::post('/ajax', [UserController::class, 'store_ajax']);        // menyimpan data user baru Ajax
    Route::get('/{id}', [UserController::class, 'show']);       // menampilkan detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // menampilkan halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);     // menyimpan perubahan data user
    Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
    Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);  // menampilkan halaman form edit user Ajax
    Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // menyimpan perubahan data user Ajax
    Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);  // untuk tampilkan form confirm delete user Ajax
    Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // untuk hapus data user Ajax
    Route::get('/import', [UserController::class, 'import']);
    Route::post('/import_ajax', [UserController::class, 'import_ajax']);
    Route::get('/export_excel', [UserController::class, 'export_excel']); 
    Route::get('/export_pdf', [UserController::class, 'export_pdf']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
    });


    // barang
    Route::prefix('barang')->middleware(['authorize:ADM,MNG,STF'])->group(function(){
        Route::get('/', [BarangController::class, 'index']);
        Route::post('/list', [BarangController::class, 'list']);
        Route::get('/create', [BarangController::class, 'create']);
        Route::post('/', [BarangController::class, 'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show']);
        Route::get('/{id}/edit', [BarangController::class, 'edit']);
        Route::put('/{id}', [BarangController::class, 'update']);
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']);
        Route::post('barang/list', [BarangController::class, 'list'])->name('barang.list');
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']);
        Route::delete('/{id}', [BarangController::class, 'destroy']);
        Route::get('/import', [BarangController::class, 'import']);
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
        Route::get('/export_excel', [BarangController::class, 'export_excel']); 
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']); 
    });

    // kategori
    Route::prefix('kategori')->middleware(['authorize:ADM,STF'])->group(function(){
        Route::get('/', [KategoriController::class, 'index']);
        Route::post('/list', [KategoriController::class, 'list']);
        Route::get('/create', [KategoriController::class, 'create']);
        Route::post('/', [KategoriController::class, 'store']);
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']);
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);
        Route::get('/{id}', [KategoriController::class, 'show']);
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);
        Route::put('/{id}', [KategoriController::class, 'update']);
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']);
        Route::post('user/list', [KategoriController::class, 'list'])->name('user.list');
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']);
        Route::delete('/{id}', [KategoriController::class, 'destroy']);
        Route::get('/import', [KategoriController::class, 'import']);
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
    }); 

    // level
    Route::prefix('level')->middleware(['authorize:ADM, MNG'])->group(function(){
        Route::get('/', [LevelController::class, 'index']);
        Route::post('/list', [LevelController::class, 'list']);
        Route::get('/create', [LevelController::class, 'create']);
        Route::post('/', [LevelController::class, 'store']);
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']);
        Route::post('/ajax', [LevelController::class, 'store_ajax']);
        Route::get('/{id}', [LevelController::class, 'show']);
        Route::get('/{id}/edit', [LevelController::class, 'edit']);
        Route::put('/{id}', [LevelController::class, 'update']);
        Route::post('level/list', [LevelController::class, 'list'])->name('level.list');
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']);
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']); 
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); 
        Route::delete('/{id}', [LevelController::class, 'destroy']);

    });

    // supplier
    Route::middleware(['authorize:ADM,MNG'])->group(function(){
        Route::get('/supplier', [SupplierController::class, 'index']); //menampilkan halaman awal leevel
        Route::post('/supplier/list',[SupplierController::class,'list']);   //menampilkan data Supplier dalam bentuk json
        Route::get('/supplier/create', [SupplierController::class, 'create']);   // menampilkan halaman form tambah Supplier
        Route::get('/supplier/create_ajax', [SupplierController::class, 'create_ajax']); // menampilkan halaman form tambah supplier Ajax
        Route::post('/supplier/ajax', [SupplierController::class, 'store_ajax']); // menyimpan data supplier baru Ajax
        Route::post('/supplier', [SupplierController::class, 'store']);         // menyimpan data Supplier baru
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit']); // menampilkan halaman form edit Supplier
        Route::put('/supplier/{id}', [SupplierController::class, 'update']);     // menyimpan perubahan data Supplier
        Route::get('/supplier/{id}/edit_ajax', [SupplierController::class,'edit_ajax']); //menampilkan halaman form edit user ajax
        Route::put('/supplier/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // menyimpan perubahan data supplier Ajax
        Route::get('/supplier/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/supplier/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // untuk hapus data supplier Ajax
        Route::get('/supplier/{id}', [SupplierController::class, 'show']);       // menampilkan detail level
        Route::get('/supplier/{id}/show_ajax', [SupplierController::class, 'show_ajax']);
        Route::get('/supplier/import', [SupplierController::class, 'import']); //ajax form upload excel
        Route::post('/supplier/import_ajax', [SupplierController::class, 'import_ajax']); //ajax form upload excel
        Route::get('/supplier/export_excel', [SupplierController::class, 'export_excel']); //export_excel
        Route::get('/supplier/export_pdf', [SupplierController::class, 'export_pdf']); //export_pdf
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy']); // menghapus data Supplier
    });

    // stok
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function(){
        Route::get('/stok', [StokController::class, 'index']);  // menampilkan halaman stok
        Route::post('/stok/list', [StokController::class, 'list'] );    //menampilkan data stok dalam bentuk json datatables
        Route::get('/stok/create_ajax', [StokController::class, 'create_ajax']); //Menampilkan halaman form tambah stok Ajax
        Route::post('/stok/ajax', [StokController::class, 'store_ajax']); // Menyimpan data stok baru Ajax
        Route::get('/stok/{id}', [StokController::class, 'show']);       //menampilkan detai stok
        Route::get('/stok/{id}/show_ajax', [StokController::class, 'show_ajax']);
        Route::get('/stok/{id}/edit_ajax', [StokController::class,'edit_ajax']); //menampilkan halaman form edit stok ajax
        Route::put('/stok/{id}/update_ajax', [StokController::class,'update_ajax']);   //menyimpan halaman form edit stok ajax
        Route::get('/stok/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); //tampil form confirm delete stok ajax
        Route::delete('/stok/{id}/delete_ajax', [StokController::class, 'delete_ajax']);  //hapus data stok
        Route::delete('/stok/{id}', [StokController::class, 'destroy']);     //mengahpus data stok
        Route::get('/stok/import', [StokController::class, 'import']); //ajax form upolad
        Route::post('/stok/import_ajax', [StokController::class, 'import_ajax']); //ajax import exvel)
        Route::get('/stok/export_excel', [StokController::class, 'export_excel']);  //export excel
        Route::get('/stok/export_pdf', [StokController::class, 'export_pdf']); //export pdf
    });

});