<?php

namespace App\Modules\GoogleDrive\Listeners;

use App\Modules\GoogleDrive\Events\ClientCreated;
use App\Modules\GoogleDrive\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;
use Exception;

class CreateClientDriveFolder
{
    private GoogleDriveService $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    public function handle(ClientCreated $event): void
    {
        $client = $event->client;

        // Se já tiver pasta, não faz nada
        if ($client->google_drive_folder_id) {
            return;
        }

        try {
            // Cria a pasta no Drive
            $folderId = $this->driveService->createClientFolder($client->name);
            
            // Atualiza o cliente com o ID da pasta
            $client->update(['google_drive_folder_id' => $folderId]);
            
        } catch (Exception $e) {
            // Falha silenciosa ou log, para não bloquear o cadastro do cliente
            Log::error("Falha ao criar pasta no Drive para o cliente {$client->id}: " . $e->getMessage());
        }
    }
}