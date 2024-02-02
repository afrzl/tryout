<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\Admin\HimadaController;
use App\Http\Controllers\admin\VoucherController;
use App\Http\Controllers\Admin\PaketUjianController;
use App\Http\Controllers\Admin\PesertaUjianController;
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
Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->middleware('auth', 'verified', 'role:admin')->name('admin.dashboard');

//route data user
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
    Route::resource('user', UserController::class);
    Route::post('/user/resetPassword/{id}', [UserController::class, 'resetPassword'])->name('user.resetpassword');
    Route::post('/user/makeAdmin/{action}/{id}', [UserController::class, 'makeAdmin'])->name('user.makeAdmin');
});

//route data himada
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/himada/data', [HimadaController::class, 'data'])->name('himada.data');
    Route::resource('himada', HimadaController::class);
    Route::post('/himada/getUser', [HimadaController::class, 'getUser'])->name('himada.getUser');
});

//route data voucher
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/voucher/data', [VoucherController::class, 'data'])->name('voucher.data');
    Route::resource('voucher', VoucherController::class);
});

//route data paket ujian
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/paket/data', [PaketUjianController::class, 'data'])->name('paket.data');
    Route::resource('paket', PaketUjianController::class);
});

//route data ujian
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/ujian/data', [UjianController_Admin::class, 'data'])->name('ujian.data');
    Route::get('/ujian/{id}/publish', [UjianController_Admin::class, 'publish'])->name('ujian.publish');
    Route::resource('ujian', UjianController_Admin::class);
});

//route data peserta ujian
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/peserta_ujian/data', [PesertaUjianController::class, 'data'])->name('peserta_ujian.data');
    Route::get('/peserta_ujian/showdata/{id}', [PesertaUjianController::class, 'showData'])->name('peserta_ujian.show_data');
    Route::get('/peserta_ujian/pembelian/{id}', [PesertaUjianController::class, 'showPeserta'])->name('peserta_ujian.show_peserta');
    Route::get('/peserta_ujian/showdatapeserta/{id}', [PesertaUjianController::class, 'showDataPeserta'])->name('peserta_ujian.show_data_peserta');
    Route::get('/peserta_ujian/{id}/refresh', [PesertaUjianController::class, 'refresh'])->name('peserta_ujian.refresh');
    Route::resource('peserta_ujian', PesertaUjianController::class);
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
    Route::post('/pembelian/pay', [PembelianController::class, 'pay'])->name('pembelian.pay');
    Route::post('/pembelian/applyVoucher', [PembelianController::class, 'applyVoucher'])->name('pembelian.applyVoucher');
});

//route tryout
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tryout', [UjianController::class, 'tryout'])->name('tryout');
    Route::get('/tryout/{id}/pembahasan', [UjianController::class, 'pembahasan'])->name('tryout.pembahasan');
    Route::get('/tryout/{id}/nilai', [UjianController::class, 'nilai'])->name('tryout.nilai');
    Route::resource('tryout', UjianController::class);
    Route::paginate('tryout', [UjianController::class, 'index'])->name('ujian.page');
});

//route ujian
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ujian/{id}', [UjianController::class, 'ujian'])->name('ujian.index');
    Route::post('/ujian/store', [UjianController::class, 'store'])->name('ujian.store');
    Route::put('/ujian/mulaiujian/{id}', [UjianController::class, 'mulaiUjian'])->name('ujian.mulai');
    Route::put('/ujian/selesaiujian/{id}', [UjianController::class, 'selesaiUjian'])->name('ujian.selesai');
    Route::put('/ujian/storeragu/{id}', [UjianController::class, 'storeRagu'])->name('ujian.ragu');
    // Route::get('/ujian/nilai/{id}', [UjianController::class, 'nilai'])->name('ujian.nilai');
});

Route::get('sessiondestroy', [UjianController::class, 'sessionDestroy'])->name('session_destroy');

require_once __DIR__ . '/jetstream.php';

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Route::group(['middleware' => ['can:publish articles']], function () {
//     //
// });

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified'
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
