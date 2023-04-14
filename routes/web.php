<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PaymentCallbackController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

//route data user
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);
    Route::post('/user/resetPassword/{id}', [UserController::class, 'resetPassword'])->name('user.resetpassword');
    Route::post('/user/makeAdmin/{action}/{id}', [UserController::class, 'makeAdmin'])->name('user.makeAdmin');
});

//route data ujian
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/data', [UjianController::class, 'data'])->name('ujian.data');
    Route::get('/ujian/{id}/publish', [UjianController::class, 'publish'])->name('ujian.publish');
    Route::resource('ujian', UjianController::class);
});

//route data soal
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/soal/data/{id}', [SoalController::class, 'data'])->name('soal.data');
    // Route::get('/ujian/soal/{id}', [SoalController::class, 'index'])->name('soal.index');
    Route::resource('ujian.soal', SoalController::class)->shallow();
});

//route pembelian
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pembelian', PembelianController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::group(['middleware' => ['can:publish articles']], function () {
//     //
// });

require __DIR__.'/auth.php';
