<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;    
use App\Http\Controllers\LevelController;    
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware(['auth'])->group(function() {
    
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/level', [LevelController::class, 'index']);
    Route::get('/kategori', [KategoriController::class, 'index']);
    Route::get('/user', [UserController::class, 'index']);
    Route::get('/user/tambah', [UserController::class, 'tambah']);
    Route::get('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
    Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
    Route::get('/user/ubah_simpan{id}', [UserController::class, 'ubah']);   
    
    Route::get('/', [WelcomeController::class,'index']);
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class,'index']);           // menampilkan halaman awal user
        Route::post('/list', [UserController::class,'list']);       // menampilkan data user dalam bntuk json untuk dtabse
        Route::get('/create', [UserController::class,'create']);    // halaman form tambah user
        Route::post('/', [UserController::class,'store']);          // menyimpan data user baru
        
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);     // Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);            // Menyimpandata user baru Ajax
    
        Route::get('/{id}', [UserController::class,'show']);        // detail user
        Route::get('/{id}/edit', [UserController::class,'edit']);   // halaman form edit user
        Route::put('/{id}', [UserController::class,'update']);      // simpan perubahan data user
    
        Route::get('/{id}/edit_ajax', [UserController::class,'edit_ajax']);     // halaman form edit user ajax
        Route::put('/{id}/update_ajax', [UserController::class,'update_ajax']); // simpan perubahan data user ajax
        Route::get('/{id}/delete_ajax', [UserController::class,'confirm_ajax']); // tampilkan form confirm delete user ajax
        Route::delete('/{id}/delete', [UserController::class,'delete_ajax']);   // hapus data user ajax
    
        Route::delete('/{id}', [UserController::class,'destroy']);  // menghapus data user
    });

});

Route::middleware(['authorize:ADM'])->group(function () {
    Route::get('/level', [LevelController::class, 'index']);
    Route::post('/level/list', [LevelController::class, 'list']); // untuk list json datatables
    Route::get('/level/create', [LevelController::class, 'create']);
    Route::post('/level', [LevelController::class, 'store']);
    Route::get('/level/{id}/edit', [LevelController::class, 'edit']); // untuk tampilkan form edit
    Route::put('/level/{id}', [LevelController::class, 'update']); // untuk proses update data
    Route::delete('/level/{id}', [LevelController::class, 'destroy']); // untuk proses hapus data
});

// artinya semua route di dalam group ini harus punya role ADM (Administrator) dan MNG (Manager)
Route::middleware(['authorize:ADM,MNG'])->group(function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/barang/list', [BarangController::class, 'list']);
    Route::get('/barang/create_ajax', [BarangController::class, 'create_ajax']); // ajax form create
    Route::post('/barang_ajax', [BarangController::class, 'store_ajax']); // ajax store
    Route::get('/barang/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // ajax form edit
    Route::put('/barang/{id}/update_ajax', [BarangController::class, 'update_ajax']); // ajax update
    Route::get('/barang/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // ajax form confirm
    Route::delete('/barang/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // ajax delete
});


