<?php

namespace App\Modules\SystemOption\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemOption;
use App\Modules\SystemOption\Http\Requests\SystemOptionRequest;
use App\Modules\SystemOption\Services\SystemOptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- CORREÇÃO: Importação da Facade Auth
use Illuminate\Support\Facades\Log;   // <-- Importação da Facade Log

class SystemOptionController extends Controller
{
    protected SystemOptionService $service;

    public function __construct(SystemOptionService $service)
    {
        $this->service = $service;
        $this->authorizeResource(SystemOption::class, 'system_option');
    }

    public function index()
    {
        return view('modules.system-option.index');
    }

    public function getData(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'option_status']);
            
            // Agora o Auth::id() funcionará corretamente
            Log::info('SystemOption getData chamado', [
                'user_id' => Auth::id(),
                'company_id' => Auth::user()?->company_id,
            ]);

            $data = $this->service->getAll($filters);
            
            return response()->json([
                'success' => true,
                'dados' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar opções do sistema: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro interno ao carregar dados.'
            ], 500);
        }
    }

    public function store(SystemOptionRequest $request): JsonResponse
    {
        try {
            $option = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'Opção criada com sucesso!', 'dados' => $option]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar opção: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function edit(SystemOption $system_option): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'dados' => $system_option]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar opção para edição: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao carregar opção.'], 500);
        }
    }

    public function update(SystemOptionRequest $request, SystemOption $system_option): JsonResponse
    {
        try {
            $optionId = is_object($system_option) ? $system_option->id : $system_option;
            $option = $this->service->update($optionId, $request->validated());
            return response()->json(['success' => true, 'message' => 'Opção atualizada com sucesso!', 'dados' => $option]);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar opção: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(SystemOption $system_option): JsonResponse
    {
        try {
            $optionId = is_object($system_option) ? $system_option->id : $system_option;
            $this->service->delete($optionId);
            return response()->json(['success' => true, 'message' => 'Opção excluída com sucesso!']);
        } catch (\Exception $e) {
            Log::error('Erro ao excluir opção: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}