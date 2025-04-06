<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});
Route::post('user.daftar', [App\Http\Controllers\UserController::class, 'daftar'])->name('user.daftar');

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['prefix' => 'insight'], function () {
        Route::get('papan-kerja', [\App\Http\Controllers\HomeController::class, 'index'])->name('papan-kerja');
        Route::get('kalender-kerja', [\App\Http\Controllers\HomeController::class, 'kalender'])->name('kalender-kerja');
        Route::get('tugas', [\App\Http\Controllers\HomeController::class, 'tugas'])->name('tugas');
    });

    Route::resource('log-harian', 'App\Http\Controllers\LogharianController');

    Route::get('/log-harian/sub/{id}', [App\Http\Controllers\LogharianController::class, 'getSubPekerjaan']);
    Route::post('log-harian/store/sub-pekerjaan', [App\Http\Controllers\LogharianController::class, 'subPekerjaanStore'])->name('log-harian.store.sub');
    Route::patch('/log-harian/sub/update/{id}', [App\Http\Controllers\LogharianController::class, 'subPekerjaanUpdate'])->name('log-harian.update.sub');
    Route::delete('/log-harian/sub/destroy/{id}', [App\Http\Controllers\LogharianController::class, 'subPekerjaanDestroy'])->name('log-harian.destroy.sub');

    Route::post('log-harian/update-status-pekerjaan/{id}', [App\Http\Controllers\LogharianController::class, 'updateStatusPekerjaan']);
    Route::post('log-harian/sub/update-status-pekerjaan/{id}', [App\Http\Controllers\LogharianController::class, 'updateSubStatusPekerjaan']);

    Route::post('laporan-masalah/update-status-pekerjaan/{id}', [App\Http\Controllers\LaporanmasalahController::class, 'updateStatusPekerjaan']);
    Route::post('laporan-hasil/update-status-laporan/{id}', [App\Http\Controllers\LaporanhasilController::class, 'updateStatusLaporan']);
    Route::post('/get-subkategori', [App\Http\Controllers\PelayananController::class, 'getSubKategori'])->name('get.subkategori');
    Route::get('/get-karyawan/{nik}', [App\Http\Controllers\PelayananController::class, 'getNamaKaryawan']);

    Route::resource('laporan-masalah', 'App\Http\Controllers\LaporanmasalahController');
    Route::resource('laporan-hasil', 'App\Http\Controllers\LaporanhasilController');
    Route::resource('laporan-pelayanan', 'App\Http\Controllers\PelayananController');
    Route::resource('profile', 'App\Http\Controllers\AccountController');
    Route::resource('user', 'App\Http\Controllers\UserController');

    Route::group(['prefix' => 'dropdown', 'middleware' => ['role:ADMIN']], function () {
        Route::resource('status-pekerjaan', 'App\Http\Controllers\Dropdown\StatuspekerjaanController');
        Route::resource('kategori-pekerjaan', 'App\Http\Controllers\Dropdown\KategoripekerjaanController');
        Route::resource('jenis-pekerjaan', 'App\Http\Controllers\Dropdown\JenispekerjaanController');
        Route::resource('divisi', 'App\Http\Controllers\Dropdown\DivisiController');
        Route::resource('prioritas', 'App\Http\Controllers\Dropdown\PrioritasController');
        Route::resource('kategori-pelayanan', 'App\Http\Controllers\Dropdown\KategoriPelayananController');
        Route::resource('sub-kategori-pelayanan', 'App\Http\Controllers\Dropdown\SubKategoriPelayananController');
    });
});
