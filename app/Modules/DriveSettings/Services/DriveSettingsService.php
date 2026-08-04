<?php

namespace App\Modules\DriveSettings\Services;

use App\Modules\DriveSettings\Repositories\DriveSettingsRepository;
use Illuminate\Support\Facades\Log;
use Exception;

class DriveSettingsService
{
    private DriveSettingsRepository $repository;

    public function __construct(DriveSettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByOptionName(string $optionName, int $companyId)
    {
        return $this->repository->findByOptionName($optionName, $companyId);
    }

    /**
     * Salvar credenciais do Google Drive (JSON completo)
     */
    public function saveCredentials(int $companyId, string $jsonContent): array
    {
        try {
            // 1. Validar se é um JSON válido
            $decoded = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('O arquivo não é um JSON válido.');
            }

            // 2. Validar se é uma Service Account
            if (!isset($decoded['type']) || $decoded['type'] !== 'service_account') {
                throw new Exception('O arquivo JSON deve ser de uma "Service Account" do Google Cloud.');
            }

            // 3. Validar campos obrigatórios
            $requiredFields = ['project_id', 'private_key_id', 'private_key', 'client_email', 'client_id'];
            foreach ($requiredFields as $field) {
                if (!isset($decoded[$field]) || empty($decoded[$field])) {
                    throw new Exception("Campo obrigatório ausente no JSON: {$field}");
                }
            }

            // 4. Criptografar o JSON antes de salvar (segurança)
            $encryptedContent = encrypt($jsonContent);

            // 5. Salvar no banco usando os campos corretos do seu Model
            $option = $this->repository->updateOrCreate(
                $companyId,
                'google_drive_credentials', // option_name
                [
                    'option_value' => $encryptedContent,
                    'option_description' => 'Credenciais da Service Account do Google Drive',
                    'option_status' => true,
                ]
            );

            Log::info("Google Drive: Credenciais salvas com sucesso para company_id {$companyId}");

            return [
                'success' => true,
                'message' => 'Credenciais salvas e criptografadas com sucesso!',
                'dados' => [
                    'id' => $option->id,
                    'client_email' => $decoded['client_email'],
                ]
            ];

        } catch (Exception $e) {
            Log::error("Google Drive: Erro ao salvar credenciais para company_id {$companyId}. Erro: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Testar conexão com Google Drive
     */
    public function testConnection(int $companyId): array
    {
        try {
            $option = $this->repository->findByOptionName('google_drive_credentials', $companyId);
            
            if (!$option || !$option->option_value) {
                throw new Exception('Credenciais não configuradas para esta empresa.');
            }

            // Descriptografar o option_value
            $credentialsJson = decrypt($option->option_value);
            $credentialsArray = json_decode($credentialsJson, true);

            // Inicializar cliente Google
            $client = new \Google\Client();
            $client->setAuthConfig($credentialsArray);
            $client->addScope(\Google\Service\Drive::DRIVE);
            
            $drive = new \Google\Service\Drive($client);
            $about = $drive->about->get(['fields' => 'user']);
            
            return [
                'success' => true,
                'message' => 'Conexão testada com sucesso!',
                'dados' => [
                    'email' => $about->getUser()->getEmailAddress(),
                ]
            ];

        } catch (Exception $e) {
            Log::error("Google Drive: Erro ao testar conexão para company_id {$companyId}. Erro: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Falha na conexão: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Deletar credenciais (Soft Delete)
     */
    public function deleteCredentials(int $companyId): bool
    {
        try {
            $option = $this->repository->findByOptionName('google_drive_credentials', $companyId);
            
            if ($option) {
                $this->repository->delete($option);
                Log::info("Google Drive: Credenciais removidas (soft delete) para company_id {$companyId}");
            }
            
            return true;
        } catch (Exception $e) {
            Log::error("Google Drive: Erro ao deletar credenciais para company_id {$companyId}. Erro: " . $e->getMessage());
            throw $e;
        }
    }
}