<?php

namespace App\Modules\Client\Services;

use App\Modules\Client\Repositories\ClientRepository;
use App\Modules\Core\Validators\CnpjValidator;
use Illuminate\Support\Facades\Log;
use Exception;

class ClientService
{
    public function __construct(
        protected ClientRepository $repository
    ) {}

    /**
     * Cria um novo cliente após validar o CNPJ.
     */
    public function create(array $data, int $companyId): array
    {
        // Guard Clause: Validação defensiva antes de tocar no repositório
        if (!CnpjValidator::isValid($data['cnpj'])) {
            throw new \InvalidArgumentException('Tentativa de criação com CNPJ inválido.');
        }

        try {
            // Adiciona o company_id para garantir o isolamento Multi-Tenant
            $data['company_id'] = $companyId;
            
            return $this->repository->create($data);
        } catch (Exception $e) {
            Log::error('Erro ao criar cliente', [
                'cnpj' => $data['cnpj'] ?? null,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}