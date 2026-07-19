<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Modules\Auth\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * AuthController
 * --------------------------------------------------------
 * Controller de autenticação.
 * Regras do playbook:
 *  - "Controller NUNCA contém regras de negócio"
 *  - "Retorna JSON para operações AJAX"
 *  - "Retorna View para index"
 *  - "Exibe mensagens de retorno via sweetalert2"
 *
 * Princípio: Controller apenas orquestra (Request → Service → Response).
 */
class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Exibe a view de login (GET /login).
     * Regra: retorna View para index.
     */
    public function showLoginForm()
    {
        // Guard Clause: se já está autenticado, redireciona para dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Processa o login (POST /login) via AJAX.
     * Regra: retorna JSON para operações AJAX.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Delega a regra de negócio para o Service
        $result = $this->authService->login(
            $request->input('email'),
            $request->input('password')
        );

        // Retorna JSON para o frontend tratar (SweetAlert2 + redirect)
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Realiza o logout (POST /logout).
     */
    public function logout(Request $request)
    {
        $this->authService->logout();

        // Redireciona para login com mensagem via session
        return redirect()
            ->route('login')
            ->with('success', 'Você saiu com sucesso.');
    }
}