<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    Route::resource('ujian', UjianController::class);
});

//route data soal
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/soal/data', [UjianController::class, 'data'])->name('soal.data');
    Route::resource('ujian/soal', UjianController::class);
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
