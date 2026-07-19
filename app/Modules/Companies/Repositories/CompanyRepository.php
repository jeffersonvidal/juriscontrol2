<?php

namespace App\Modules\Companies\Repositories;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * CompanyRepository
 * --------------------------------------------------------
 * Centraliza o acesso a dados do módulo Companies.
 * Regra do playbook: "Usar Services, Repositories".
 * 
 * ALTERAÇÕES:
 *  - Filtros ajustados para novos campos (trade_name, corporate_reason, cnpj_cpf, is_active)
 *  - Eager loading de addresses e responsible
 */
class CompanyRepository
{
    /**
     * Lista paginada com filtros de busca.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Company::with(['addresses', 'responsible']);

        // Filtro por nome fantasia, razão social ou documento
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('trade_name', 'LIKE', "%{$search}%")
                  ->orWhere('corporate_reason', 'LIKE', "%{$search}%")
                  ->orWhere('cnpj_cpf', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por status (is_active)
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    /**
     * Encontra uma empresa pelo ID com relacionamentos.
     */
    public function find(int $id): ?Company
    {
        return Company::with(['addresses', 'responsible'])->find($id);
    }

    /**
     * Cria uma nova empresa.
     */
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    /**
     * Atualiza uma empresa existente.
     */
    public function update(Company $company, array $data): bool
    {
        return $company->update($data);
    }

    /**
     * Exclui uma empresa (hard delete, pois não tem soft deletes).
     */
    public function delete(Company $company): bool
    {
        return $company->delete();
    }
}