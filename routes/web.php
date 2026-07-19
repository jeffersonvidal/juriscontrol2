<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação (login, register, etc)
// O Laravel Breeze/Jetstream ou auth manual fica aqui
// Exemplo:
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================
// Rotas PROTEGIDAS (autenticadas + multi-tenant)
// ============================================================
Route::middleware(['auth', 'tenant'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Futuros módulos (Companies, Clients, LegalCases, etc)
    // serão registrados aqui dentro deste grupo.
    // Exemplo:
    // Route::resource('companies', CompanyController::class);
    // Route::resource('clients', ClientController::class);

});