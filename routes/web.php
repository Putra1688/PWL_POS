<?php

use App\Http\Controllers\LevelController;    
use App\Http\Controllers\KategoriController;    
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;


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

    Route::delete('/{id}', [UserController::class,'destroy']);  // menghapus data user
});
