<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (visitantes)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (autenticadas + tenant + status)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.status', 'tenant'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ============================================================
    // Módulo Companies
    // ============================================================
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'index'])->name('index');
        Route::get('/list', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'getList'])->name('list');
        Route::post('/', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'store'])->name('store');
        Route::get('/{company}/edit', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [\App\Modules\Companies\Http\Controllers\CompanyController::class, 'destroy'])->name('destroy');
    });
});