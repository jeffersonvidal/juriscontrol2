<?php

namespace App\Modules\GoogleDrive\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use App\Models\SystemOption; // Assumindo que você tem este model
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleDriveService
{
    private Client $client;
    private Drive $service;
    private int $companyId;
    private ?string $rootFolderId = null;

    public function __construct()
    {
        // Isola pelo tenant atual
        $this->companyId = auth()->user()->company_id;
        $this->initGoogleClient();
    }

    /**
     * Inicializa o cliente do Google buscando as credenciais do tenant
     */
    private function initGoogleClient(): void
    {
        // Busca as credenciais na tabela system_options filtrando pelo company_id
        $credentialsJson = SystemOption::where('company_id', $this->companyId)
            ->where('key', 'google_drive_credentials')
            ->value('value');

        if (!$credentialsJson) {
            throw new Exception('Credenciais do Google Drive não configuradas para esta empresa.');
        }

        $this->client = new Client();
        $this->client->setAuthConfig(json_decode($credentialsJson, true));
        $this->client->addScope(Drive::DRIVE);
        
        $this->service = new Drive($this->client);
    }

    /**
     * Busca ou cria a pasta raiz "Clientes" no Drive
     */
    private function getRootFolderId(): string
    {
        if ($this->rootFolderId) {
            return $this->rootFolderId;
        }

        // Verifica se a pasta raiz já existe
        $response = $this->service->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.folder' and name='Clientes' and trashed=false"
        ]);

        if (count($response->getFiles()) > 0) {
            $this->rootFolderId = $response->getFiles()[0]->id;
        } else {
            // Cria a pasta raiz se não existir
            $folderMetadata = new DriveFile([
                'name' => 'Clientes',
                'mimeType' => 'application/vnd.google-apps.folder'
            ]);
            $folder = $this->service->files->create($folderMetadata, ['fields' => 'id']);
            $this->rootFolderId = $folder->id;
        }

        return $this->rootFolderId;
    }

    /**
     * Cria uma pasta para o cliente dentro da pasta raiz "Clientes"
     */
    public function createClientFolder(string $clientName): string
    {
        try {
            $folderMetadata = new DriveFile([
                'name' => $clientName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$this->getRootFolderId()]
            ]);

            $folder = $this->service->files->create($folderMetadata, ['fields' => 'id']);
            return $folder->id;
        } catch (Exception $e) {
            Log::error("Erro ao criar pasta no Drive: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Lista arquivos de uma pasta específica
     */
    public function listFiles(string $folderId): array
    {
        try {
            $response = $this->service->files->listFiles([
                'q' => "'{$folderId}' in parents and trashed=false",
                'fields' => 'files(id, name, mimeType, size, modifiedTime, webViewLink)',
                'orderBy' => 'folder,name'
            ]);
            return $response->getFiles();
        } catch (Exception $e) {
            Log::error("Erro ao listar arquivos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Envia um arquivo para a pasta do cliente
     */
    public function uploadFile(string $filePath, string $fileName, string $folderId): ?DriveFile
    {
        try {
            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($filePath);
            
            return $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields' => 'id, name, mimeType, size, modifiedTime, webViewLink'
            ]);
        } catch (Exception $e) {
            Log::error("Erro ao enviar arquivo: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Renomeia um arquivo
     */
    public function renameFile(string $fileId, string $newName): bool
    {
        try {
            $file = new DriveFile(['name' => $newName]);
            $this->service->files->update($fileId, $file);
            return true;
        } catch (Exception $e) {
            Log::error("Erro ao renomear arquivo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Exclui um arquivo (move para lixeira)
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->service->files->update($fileId, new DriveFile(['trashed' => true]));
            return true;
        } catch (Exception $e) {
            Log::error("Erro ao excluir arquivo: " . $e->getMessage());
            return false;
        }
    }
}