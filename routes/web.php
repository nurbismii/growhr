<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('user.daftar', [App\Http\Controllers\UserController::class, 'daftar'])->name('user.daftar');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('log-harian', 'App\Http\Controllers\LogharianController');

    Route::get('/log-harian/sub/{id}', [App\Http\Controllers\LogharianController::class, 'getSubPekerjaan']);
    Route::post('log-harian/store/sub-pekerjaan', [App\Http\Controllers\LogharianController::class, 'subPekerjaanStore'])->name('log-harian.store.sub');
    Route::patch('/log-harian/sub/update/{id}', [App\Http\Controllers\LogharianController::class, 'subPekerjaanUpdate'])->name('log-harian.update.sub');
    Route::delete('/log-harian/sub/destroy/{id}', [App\Http\Controllers\LogharianController::class, 'subPekerjaanDestroy'])->name('log-harian.destroy.sub');
    
    Route::post('log-harian/update-status-pekerjaan/{id}', [App\Http\Controllers\LogharianController::class, 'updateStatusPekerjaan']);
    Route::post('log-harian/sub/update-status-pekerjaan/{id}', [App\Http\Controllers\LogharianController::class, 'updateSubStatusPekerjaan']);
    
    Route::resource('laporan-masalah', 'App\Http\Controllers\LaporanmasalahController');
    Route::resource('laporan-hasil', 'App\Http\Controllers\LaporanhasilController');
    Route::resource('profile', 'App\Http\Controllers\AccountController');
    Route::resource('user', 'App\Http\Controllers\UserController');

    Route::group(['prefix' => 'dropdown'], function () {
        Route::resource('status-pekerjaan', 'App\Http\Controllers\Dropdown\StatuspekerjaanController');
        Route::resource('kategori-pekerjaan', 'App\Http\Controllers\Dropdown\KategoripekerjaanController');
        Route::resource('jenis-pekerjaan', 'App\Http\Controllers\Dropdown\JenispekerjaanController');
        Route::resource('divisi', 'App\Http\Controllers\Dropdown\DivisiController');
        Route::resource('prioritas', 'App\Http\Controllers\Dropdown\PrioritasController');
    });
});
