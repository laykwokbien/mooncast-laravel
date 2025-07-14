<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\ProximoController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\RealtimeController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Models\Bulan;
use App\Models\siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\bulansController;

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

Route::middleware(['web', 'role:admin,operator'])->group(function () {
    Route::get('/', function (){
        return redirect('/dashboard');
    });

    Route::get('/dashboard', function () {
        $data = [
            'siswa' => siswa::whereNot('nis', 12)->get(),
            'operator' => User::where('role', 'operator')->get(),
            'bulan' => Bulan::get(),
            'last_update' => Carbon::parse(Bulan::max('updated_at'))->format('Y-m-d')
        ];
        return view('index', compact('data'));
    });
    
    Route::prefix('realtime')->group(function (){
        Route::get('/', [RealtimeController::class, 'index'])->name('realtime.index');
        Route::prefix('data')->group(function () {
            Route::get('/', [RealtimeController::class, 'data_index'])->name('realtime.data');
            Route::post('/', [RealtimeController::class, 'insert'])->name('realtime.insert');
            Route::get('/show/{id}', [RealtimeController::class, 'show'])->name('realtime.show');
            Route::get('/update/{id}', [RealtimeController::class, 'update_page']);
            Route::post('/update/{id}', [RealtimeController::class, 'update'])->name('realtime.update');
            Route::post('/delete/{id}', [RealtimeController::class, 'delete'])->name('realtime.destroy');
        });
        Route::get('/predict/{id}', [RealtimeController::class, 'predict_page'])->name('realtime.predict');
        Route::post('/predict/store/{id}', [RealtimeController::class, 'predict'])->name('realtime.store');
    });

    Route::prefix('multi')->group(function (){
        Route::get('/', [ProximoController::class, 'index'])->name('multi.index');
        Route::post('/{id}', [ProximoController::class, 'show'])->name('multi.show');
    });

    Route::prefix('data')->group(function() {
        Route::get('/', [DataController::class, 'index'])->name('siswa.data.index');
        Route::get('/create', [DataController::class, 'create_page'])->name('siswa.data.create');
        Route::post('/store', [DataController::class, 'create'])->name('siswa.data.store');
        Route::post('/import', [DataController::class, 'import'])->name('siswa.data.import');
        Route::get('/update/{id}', [DataController::class, 'update_page']);
        Route::post('/update/{id}', [DataController::class, 'update'])->name('siswa.data.update');
        Route::get('/delete/{id}', [DataController::class, 'delete'])->name('siswa.data.destroy');
    });
    
    Route::prefix('siswa')->group(function() {
        Route::get('/', [SiswaController::class, 'index'])->name('siswa.akun.index');
        Route::post('/show/{id}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::post('/store', [SiswaController::class, 'create'])->name('siswa.akun.store');
        Route::get('/generate', [SiswaController::class, 'generate_page'])->name('siswa.akun.generate');
        Route::post('/reset', [SiswaController::class, 'reset'])->name('siswa.akun.reset');
        Route::get('/update/{id}', [SiswaController::class, 'update_page']);
        Route::post('/update/{id}', [SiswaController::class, 'update'])->name('siswa.akun.update');
        Route::get('/delete/{id}', [SiswaController::class, 'delete'])->name('siswa.akun.destroy');
    });

});

Route::middleware(['web', 'role:admin'])->group(function () {
    Route::prefix('jurusan')->group(function() {
        Route::get('/', [JurusanController::class, 'index'])->name('jurusan.index');
        Route::get('/create', [jurusanController::class, 'create_page'])->name('jurusan.create');
        Route::post('/store', [jurusanController::class, 'create'])->name('jurusan.store');
        Route::get('/update/{id}', [jurusanController::class, 'update_page']);
        Route::post('/update/{id}', [jurusanController::class, 'update'])->name('jurusan.update');
        Route::get('/delete/{id}', [jurusanController::class, 'delete'])->name('jurusan.destroy');
    });

    Route::prefix('bulans')->group(function() {
        Route::get('/', [bulansController::class, 'index'])->name('bulan.index');
        Route::get('/create', [bulansController::class, 'create_page'])->name('bulan.create');
        Route::post('/store', [bulansController::class, 'create'])->name('bulan.store');
        Route::get('/update/{id}', [bulansController::class, 'update_page']);
        Route::post('/update/{id}', [bulansController::class, 'update'])->name('bulan.update');
        Route::get('/delete/{id}', [bulansController::class, 'delete'])->name('bulan.destroy');
    });

    Route::prefix('operator')->group(function() {
        Route::get('/', [OperatorController::class, 'index'])->name('operator.index');
        Route::get('/create', [OperatorController::class, 'create_page'])->name('operator.create');
        Route::post('/store', [OperatorController::class, 'create'])->name('operator.store');
        Route::post('/reset', [OperatorController::class, 'reset'])->name('operator.reset');
        Route::get('/update/{id}', [OperatorController::class, 'update_page']);
        Route::post('/update/{id}', [OperatorController::class, 'update'])->name('operator.update');
        Route::get('/delete/{id}', [OperatorController::class, 'delete'])->name('operator.destroy');
    });

    Route::prefix('admin')->group(function() {
        Route::post('/updateSchedule', [UserController::class, 'updateSchedule'])->name('admin.updateSchedule');
    });

    Route::prefix('model')->group(function (){
        Route::get('/train', [ProximoController::class, 'retrain'])->name('model.train');
        Route::get('/status', [ProximoController::class, 'checkStatus'])->name('model.status');
    });
});

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings', [UserController::class, 'update'])->name('settings.update');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'login_page'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('auth');
});