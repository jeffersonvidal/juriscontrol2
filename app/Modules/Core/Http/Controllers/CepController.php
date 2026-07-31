<?php

namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Services\CepService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Classe CepController
 * 
 * Responsabilidade Única (SRP): Expor endpoint AJAX para consulta de CEP.
 * NUNCA contém regras de negócio - apenas orquestra o Service e retorna JSON.
 */
class CepController extends Controller
{
    public function __construct(
        protected CepService $cepService
    ) {}

    /**
     * Consulta CEP via AJAX.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buscar(Request $request): JsonResponse
    {
        // Validação inline do parâmetro
        $request->validate([
            'cep' => ['required', 'string', 'max:9']
        ]);

        $cep = $request->input('cep');
        
        // Chama o service para buscar o endereço
        $endereco = $this->cepService->buscarEndereco($cep);

        // Verifica se encontrou o endereço
        if (empty($endereco)) {
            return response()->json([
                'success' => false,
                'message' => 'CEP não encontrado ou inválido.',
                'dados' => null
            ], 404);
        }

        // Retorna sucesso com os dados estruturados
        return response()->json([
            'success' => true,
            'message' => 'Endereço encontrado com sucesso.',
            'dados' => $endereco
        ]);
    }
}