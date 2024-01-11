<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\AturanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PenggunaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('login');
// });
Route::group(['middleware' => 'revalidate'], function () {

    Route::get('/welcome', function () {
        return view('welcome');
    });
    // Route::get('/home', [AuthController::class, 'home'])->name('home')->middleware('auth');
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/role', [AuthController::class, 'role'])->name('role');

    //ruang
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', [RuangController::class, 'index'])->name('index.ruang');
        Route::get('/action', [RuangController::class, 'action'])->name('action.ruang');
        Route::get('/create', [RuangController::class, 'create'])->name('create.ruang');
        Route::post('/store', [RuangController::class, 'store'])->name('store.ruang');
        Route::get('/show/{id}', [RuangController::class, 'show'])->name('show.ruang');
        Route::post('/update/{id}', [RuangController::class, 'update'])->name('update.ruang');
        Route::delete('/delete/{id}', [RuangController::class, 'destroy'])->name('delete.ruang');

        //jenis
        Route::get('/jenis', [JenisController::class, 'index'])->name('index.jenis');
        Route::get('/actionJenis', [JenisController::class, 'action'])->name('action.jenis');
        Route::get('/createJenis', [JenisController::class, 'create'])->name('create.jenis');
        Route::post('/storeJenis', [JenisController::class, 'store'])->name('store.jenis');
        Route::get('/showJenis/{id}', [JenisController::class, 'show'])->name('show.jenis');
        Route::post('/updateJenis/{id}', [JenisController::class, 'update'])->name('update.jenis');
        Route::delete('/deleteJenis/{id}', [JenisController::class, 'destroy'])->name('delete.jenis');

        //kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('index.kategori');
        Route::get('/actionKategori', [KategoriController::class, 'action'])->name('action.kategori');
        Route::get('/createKategori', [KategoriController::class, 'create'])->name('create.kategori');
        Route::post('/storeKategori', [KategoriController::class, 'store'])->name('store.kategori');
        Route::get('/showKategori/{id}', [KategoriController::class, 'show'])->name('show.kategori');
        Route::post('/updateKategori/{id}', [KategoriController::class, 'update'])->name('update.kategori');
        Route::delete('/deleteKategori/{id}', [KategoriController::class, 'destroy'])->name('delete.kategori');

        //aturan
        Route::get('/aturan', [AturanController::class, 'index'])->name('index.aturan');
        Route::get('/actionAturan', [AturanController::class, 'action'])->name('action.aturan');
        Route::get('/getKategori', [AturanController::class, 'getKategori'])->name('getKategori.aturan');
        Route::get('/createAturan', [AturanController::class, 'create'])->name('create.aturan');
        Route::post('/storeAturan', [AturanController::class, 'store'])->name('store.aturan');
        Route::get('/download/{doc}', [AturanController::class, 'download'])->name('download.aturan');
        Route::get('/showAturan/{id}', [AturanController::class, 'show'])->name('show.aturan');
        Route::post('/updateAturan/{id}', [AturanController::class, 'update'])->name('update.aturan');
        Route::delete('/deleteAturan/{id}', [AturanController::class, 'destroy'])->name('delete.aturan');

        //media
        Route::get('/media', [MediaController::class, 'index'])->name('index.media');
        Route::get('/actionMedia', [MediaController::class, 'action'])->name('action.media');
        Route::get('/getKategori', [MediaController::class, 'getKategori'])->name('getKategori.media');
        Route::get('/createMedia', [MediaController::class, 'create'])->name('create.media');
        Route::post('/storeMedia', [MediaController::class, 'store'])->name('store.media');
        Route::get('/downloadCover/{cover}', [MediaController::class, 'downloadCover'])->name('downloadCover.media');
        Route::get('/downloadDoc/{doc}', [MediaController::class, 'downloadDoc'])->name('downloadDoc.media');
        Route::get('/showMedia/{id}', [MediaController::class, 'show'])->name('show.media');
        Route::post('/updateMedia/{id}', [MediaController::class, 'update'])->name('update.media');
        Route::delete('/deleteMedia/{id}', [MediaController::class, 'destroy'])->name('delete.media');

        //pengguna
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('index.pengguna');
        Route::get('/actionPengguna', [PenggunaController::class, 'action'])->name('action.pengguna');
        Route::get('/getKategori', [PenggunaController::class, 'getKategori'])->name('getKategori.pengguna');
        Route::get('/createPengguna', [PenggunaController::class, 'create'])->name('create.pengguna');
        Route::post('/storePengguna', [PenggunaController::class, 'store'])->name('store.pengguna');
        Route::get('/showPengguna/{id}', [PenggunaController::class, 'show'])->name('show.pengguna');
        Route::post('/updatePengguna/{id}', [PenggunaController::class, 'update'])->name('update.pengguna');
        Route::delete('/deletePengguna/{id}', [PenggunaController::class, 'destroy'])->name('delete.pengguna');
    });
});
