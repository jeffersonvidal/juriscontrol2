<?php

namespace App\Modules\Companies\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Modules\Companies\Http\Requests\StoreCompanyRequest;
use App\Modules\Companies\Http\Requests\UpdateCompanyRequest;
use App\Modules\Companies\Services\CompanyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <-- ADICIONADO: Trait de autorização
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CompanyController
 * --------------------------------------------------------
 * Controlador do módulo Companies.
 * Regras do playbook:
 *  - Retorna View para index
 *  - Retorna JSON para operações AJAX
 *  - Controller NUNCA contém regras de negócio
 */
class CompanyController extends Controller
{
    // <-- ADICIONADO: Garante que o método $this->authorize() esteja disponível
    use AuthorizesRequests;

    public function __construct(
        private CompanyService $service
    ) {}

    /**
     * Exibe a view principal (index).
     */
    public function index(Request $request)
    {
        // Verifica a policy 'viewAny' para o model Company
        $this->authorize('viewAny', Company::class);
        return view('modules.companies.index');
    }

    /**
     * Lista dados para AJAX (usado na paginação/filtros da index).
     */
    public function getList(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Company::class);
        
        $filters = $request->only(['search', 'is_active']);
        $companies = $this->service->getList($filters, 15);

        // Retorna HTML parcial da tabela para ser injetado via JS
        $html = view('modules.companies.partials.table', compact('companies'))->render();

        return response()->json([
            'html' => $html,
            'links' => $companies->links()->toHtml(),
        ]);
    }

    /**
 * Exibe dados completos de uma empresa para visualização (AJAX).
 */
public function show(Company $company): JsonResponse
{
    $this->authorize('view', $company);

    $company->load(['addresses', 'responsible']);

    return response()->json([
        'success' => true,
        'data'    => $company,
    ]);
}

    /**
     * Armazena nova empresa com endereços (AJAX).
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        // A autorização também é verificada dentro do FormRequest, 
        // mas manter aqui reforça a segurança a nível de controller.
        $this->authorize('create', Company::class);

        $company = $this->service->store($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Empresa cadastrada com sucesso!',
            'data'    => $company,
        ]);
    }

    /**
     * Exibe dados de uma empresa para edição (AJAX).
     */
    public function edit(Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $company->load(['addresses']);

        return response()->json([
            'success' => true,
            'data'    => $company,
        ]);
    }

    /**
     * Atualiza empresa existente com endereços (AJAX).
     */
    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $this->service->update($company, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Empresa atualizada com sucesso!',
            'data'    => $company->fresh(['addresses', 'responsible']),
        ]);
    }

    /**
     * Exclui empresa (AJAX).
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);

        $this->service->destroy($company);

        return response()->json([
            'success' => true,
            'message' => 'Empresa excluída com sucesso!',
            'id'      => $company->id,
        ]);
    }
}