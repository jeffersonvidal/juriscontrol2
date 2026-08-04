<?php
namespace App\Modules\SystemOptions\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemOption;
use App\Modules\SystemOptions\Services\SystemOptionService;
use App\Modules\SystemOptions\Http\Requests\SystemOptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SystemOptionController extends Controller
{
    public function __construct(protected SystemOptionService $service)
    {
        //$this->authorizeResource(SystemOption::class, 'system_option');
    }

    public function index()
    {
        return view('modules.system-options.index');
    }

    /**
     * Retorna as opções da empresa em formato JSON para o AJAX
     * Aceita filtros via query parameters
     */
    public function data(Request $request)
    {
        try {
            // Extrai os filtros da requisição
            $filters = [
                'option_name' => $request->input('filter_option_name'),
                'option_value' => $request->input('filter_option_value'),
                'option_status' => $request->input('filter_option_status'),
            ];

            // Remove valores vazios
            $filters = array_filter($filters, fn($value) => $value !== null && $value !== '');

            $options = $this->service->getCompanyOptions($filters);
            
            return response()->json([
                'success' => true,
                'data' => $options
            ]);
        } catch (\Exception $e) {
            Log::error('SystemOptionController@data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar as configurações do sistema.'
            ], 500);
        }
    }

    public function show(SystemOption $systemOption)
    {
        return response()->json([
            'success' => true,
            'dados' => $systemOption
        ]);
    }

    public function update(SystemOptionRequest $request, SystemOption $systemOption)
    {
        try {
            $updated = $this->service->updateOption($systemOption->id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Configuração atualizada com sucesso!',
                'dados' => $updated
            ]);
        } catch (\Exception $e) {
            Log::error('SystemOptionController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar a configuração.'
            ], 500);
        }
    }
}