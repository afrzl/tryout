<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PaymentCallbackController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\SoalController as SoalController_Admin;
use App\Http\Controllers\Admin\UjianController as UjianController_Admin;

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
Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->middleware('auth', 'verified', 'role:admin')->name('dashboard.admin');

//route verify email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//route data user
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);
    Route::post('/user/resetPassword/{id}', [UserController::class, 'resetPassword'])->name('user.resetpassword');
    Route::post('/user/makeAdmin/{action}/{id}', [UserController::class, 'makeAdmin'])->name('user.makeAdmin');
});

//route data ujian
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/data', [UjianController_Admin::class, 'data'])->name('ujian.data');
    Route::get('/ujian/{id}/publish', [UjianController_Admin::class, 'publish'])->name('ujian.publish');
    Route::resource('ujian', UjianController_Admin::class);
});

//route data soal
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/soal/data/{id}', [SoalController_Admin::class, 'data'])->name('soal.data');
    // Route::get('/ujian/soal/{id}', [SoalController::class, 'index'])->name('soal.index');
    Route::resource('ujian.soal', SoalController_Admin::class)->shallow();
});

//route pembelian
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pembelian', PembelianController::class);
});

//route ujian
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ujian', UjianController::class);
    Route::paginate('ujian', [UjianController::class, 'index'])->name('ujian.page');
    Route::put('/ujian/mulaiujian/{id}', [UjianController::class, 'mulaiUjian'])->name('ujian.mulai');
    Route::put('/ujian/selesaiujian/{id}', [UjianController::class, 'selesaiUjian'])->name('ujian.selesai');
    Route::put('/ujian/storeragu/{id}', [UjianController::class, 'storeRagu'])->name('ujian.ragu');
    Route::get('/ujian/nilai/{id}', [UjianController::class, 'nilai'])->name('ujian.nilai');
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
