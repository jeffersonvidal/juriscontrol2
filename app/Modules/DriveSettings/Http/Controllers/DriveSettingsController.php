<?php

namespace App\Modules\DriveSettings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemOption;
use App\Modules\DriveSettings\Services\DriveSettingsService;
use App\Modules\DriveSettings\Http\Requests\DriveSettingsRequest;
use Illuminate\Support\Facades\Log;

class DriveSettingsController extends Controller
{
    private DriveSettingsService $service;

    /**
     * Constructor com injeção de dependência e autorização
     */
    public function __construct(DriveSettingsService $service)
    {
        $this->authorizeResource(SystemOption::class, 'option');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = auth()->user()->company_id;

        try {
            // Busca usando o nome correto da opção
            $credentials = $this->service->getByOptionName('google_drive_credentials', $companyId);

            return view('modules.drive_settings.index', compact('credentials'));

        } catch (\Exception $e) {
            Log::error('DriveSettings: Erro ao carregar página. Erro: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar configurações.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DriveSettingsRequest $request)
    {
        $companyId = auth()->user()->company_id;

        // Verificação manual de segurança: garante que pelo menos um foi enviado
        if (!$request->hasFile('credentials_file') && !$request->filled('credentials_json')) {
            return response()->json([
                'success' => false,
                'message' => 'Você deve enviar o arquivo JSON ou colar o conteúdo do JSON.'
            ], 422);
        }

        try {
            $jsonContent = '';

            if ($request->hasFile('credentials_file')) {
                $jsonContent = file_get_contents($request->file('credentials_file')->getRealPath());
            } elseif ($request->filled('credentials_json')) {
                $jsonContent = $request->input('credentials_json');
            }

            $result = $this->service->saveCredentials($companyId, $jsonContent);
            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('DriveSettings: Erro ao salvar credenciais. Erro: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar credenciais: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Testar conexão com Google Drive
     */
    public function testConnection()
    {
        $companyId = auth()->user()->company_id;

        try {
            $result = $this->service->testConnection($companyId);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('DriveSettings: Erro ao testar conexão. Erro: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao testar conexão: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemOption $option)
    {
        $companyId = auth()->user()->company_id;

        try {
            $this->service->deleteCredentials($companyId);

            return response()->json([
                'success' => true,
                'message' => 'Credenciais removidas com sucesso!',
            ]);

        } catch (\Exception $e) {
            Log::error('DriveSettings: Erro ao deletar credenciais. Erro: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover credenciais.',
            ], 500);
        }
    }
}