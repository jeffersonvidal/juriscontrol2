<?php

namespace App\Modules\Companies\Services;

use App\Models\Company;
use App\Modules\Companies\Repositories\CompanyRepository;
use App\Modules\Companies\Repositories\CompanyAddressRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * CompanyService
 * --------------------------------------------------------
 * Camada de aplicação. Orquestra os repositórios.
 * Regra do playbook: "Controller NUNCA contém regras de negócio".
 * 
 * ALTERAÇÕES:
 *  - Gerenciamento transacional de empresa + endereços
 *  - Preenchimento automático de user_id com usuário logado
 *  - Geração automática de slug
 */
class CompanyService
{
    public function __construct(
        private CompanyRepository $repository,
        private CompanyAddressRepository $addressRepository
    ) {}

    /**
     * Lista paginada de empresas.
     */
    public function getList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Busca uma empresa por ID com endereços.
     */
    public function getById(int $id): ?Company
    {
        return $this->repository->find($id);
    }

    /**
     * Cria uma nova empresa com endereços (transação).
     */
    public function store(array $data): Company
    {
        return DB::transaction(function () use ($data) {
            // Preenche user_id com o usuário logado (responsável pelo cadastro)
            $data['user_id'] = auth()->id();

            // Cria a empresa
            $company = $this->repository->create($data);

            // Cria os endereços (se houver)
            if (!empty($data['addresses'])) {
                $this->syncAddresses($company, $data['addresses']);
            }

            return $company->load(['addresses', 'responsible']);
        });
    }

    /**
     * Atualiza uma empresa com endereços (transação).
     */
    public function update(Company $company, array $data): bool
    {
        return DB::transaction(function () use ($company, $data) {
            // Atualiza a empresa
            $this->repository->update($company, $data);

            // Sincroniza os endereços (cria, atualiza, exclui)
            if (isset($data['addresses'])) {
                $this->syncAddresses($company, $data['addresses']);
            }

            return true;
        });
    }

    /**
     * Exclui uma empresa e seus endereços (cascade).
     */
    public function destroy(Company $company): bool
    {
        return DB::transaction(function () use ($company) {
            // Exclui a empresa (cascadeOnDelete exclui os endereços automaticamente)
            return $this->repository->delete($company);
        });
    }

    /**
     * Sincroniza endereços da empresa (cria, atualiza, exclui).
     * 
     * Lógica:
     *  - Se o endereço tem 'id': atualiza
     *  - Se o endereço tem '_destroy': exclui
     *  - Se não tem 'id': cria novo
     */
    private function syncAddresses(Company $company, array $addresses): void
    {
        $keepIds = [];

        foreach ($addresses as $addressData) {
            // Guard Clause: marcar para exclusão
            if (!empty($addressData['_destroy'])) {
                if (!empty($addressData['id'])) {
                    $address = CompanyAddress::find($addressData['id']);
                    if ($address) {
                        $address->delete();
                    }
                }
                continue;
            }

            // Guard Clause: endereço sem dados mínimos
            if (empty($addressData['street']) || empty($addressData['city'])) {
                continue;
            }

            // Adiciona company_id
            $addressData['company_id'] = $company->id;

            if (!empty($addressData['id'])) {
                // Atualiza endereço existente
                $address = CompanyAddress::find($addressData['id']);
                if ($address) {
                    $this->addressRepository->update($address, $addressData);
                    $keepIds[] = $address->id;
                }
            } else {
                // Cria novo endereço
                unset($addressData['id']);
                $newAddress = $this->addressRepository->create($addressData);
                $keepIds[] = $newAddress->id;
            }
        }

        // Remove endereços que não foram enviados (foram excluídos no frontend)
        if (!empty($keepIds)) {
            $this->addressRepository->deleteExcept($company->id, $keepIds);
        } else {
            // Se não há endereços para manter, exclui todos
            CompanyAddress::where('company_id', $company->id)->delete();
        }
    }
}