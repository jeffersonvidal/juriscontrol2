<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
| Ordem dos middlewares:
|  1. auth         → exige usuário logado
|  2. check.status → valida user ativo + empresa ativa
|  3. tenant       → garante isolamento multi-tenant
*/
Route::middleware(['auth', 'check.status', 'tenant'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Futuros módulos serão adicionados aqui
});