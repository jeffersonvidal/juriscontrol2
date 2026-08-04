<?php

use App\Http\Controllers\Auth\AuthController;
use App\Modules\Companies\Http\Controllers\CompanyController;
use App\Modules\Core\Http\Controllers\CepController;
use App\Modules\SystemOptions\Http\Controllers\SystemOptionController;
use App\Modules\Tags\Http\Controllers\TagController;
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
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/list', [CompanyController::class, 'getList'])->name('list');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show'); // <-- ADICIONADO
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
    });


    // ============================================================
    // Módulo Tags
    // ============================================================
    Route::middleware(['auth', 'tenant'])->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
    });


    // ============================================================
    // Rota para consulta de CEP via AJAX
    // ============================================================
    Route::post('/cep/buscar', [CepController::class, 'buscar'])
        ->name('cep.buscar');


    // ============================================================
    // Rotas do Módulo System Option
    // ============================================================
    Route::middleware(['auth', 'tenant'])->group(function () {

        // 1. ROTAS ESPECÍFICAS DEVEM VIR PRIMEIRO
        Route::get('system-options/data', [SystemOptionController::class, 'data'])
            ->name('system-options.data');

        // 2. ROTAS RESOURCE VEM POR ÚLTIMO
        Route::resource('system-options', SystemOptionController::class)
            ->names('system-options');

    });

    // ===========================================================================
    // Rotas do módulo DriveSettings
    // ===========================================================================
    Route::prefix('drive-settings')->middleware(['auth', 'tenant'])->group(function () {
        Route::get('/', [App\Modules\DriveSettings\Http\Controllers\DriveSettingsController::class, 'index'])
            ->name('drive_settings.index');

        Route::post('/', [App\Modules\DriveSettings\Http\Controllers\DriveSettingsController::class, 'store'])
            ->name('drive_settings.store');

        Route::post('/test-connection', [App\Modules\DriveSettings\Http\Controllers\DriveSettingsController::class, 'testConnection'])
            ->name('drive_settings.test_connection');

        Route::delete('/{option}', [App\Modules\DriveSettings\Http\Controllers\DriveSettingsController::class, 'destroy'])
            ->name('drive_settings.destroy');
    });

    // ===========================================================================
    // Rotas de manipulação de arquivos do Drive (Protegidas por auth e tenant)
    // ===========================================================================
    Route::prefix('clients/files')->middleware(['auth', 'tenant'])->group(function () {
        Route::get('/{folderId}', [ClientController::class, 'listFiles'])->name('clients.files.list');
        Route::post('/upload/{folderId}', [ClientController::class, 'uploadFile'])->name('clients.files.upload');
        Route::post('/rename/{fileId}', [ClientController::class, 'renameFile'])->name('clients.files.rename'); // PUT spoofing
        Route::post('/delete/{fileId}', [ClientController::class, 'deleteFile'])->name('clients.files.delete'); // DELETE spoofing
    });

});