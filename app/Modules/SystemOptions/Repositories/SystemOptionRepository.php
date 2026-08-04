<?php
namespace App\Modules\SystemOptions\Repositories;

use App\Models\SystemOption;
use Illuminate\Database\Eloquent\Builder;

class SystemOptionRepository
{
    public function __construct(protected SystemOption $model) {}

    /**
     * Busca opções da empresa com filtros e paginação
     * Utiliza os índices compostos: ['company_id', 'option_name']
     */
    public function getByCompanyId(int $companyId, array $filters = [], int $perPage = 10)
    {
        $query = $this->model->where('company_id', $companyId);

        // Filtro por option_name (chave) - usa índice composto
        if (!empty($filters['option_name'])) {
            $query->where('option_name', 'like', '%' . $filters['option_name'] . '%');
        }

        // Filtro por option_value (valor)
        if (!empty($filters['option_value'])) {
            $query->where('option_value', 'like', '%' . $filters['option_value'] . '%');
        }

        // Filtro por option_status (ativo/inativo)
        if (isset($filters['option_status']) && $filters['option_status'] !== '') {
            $query->where('option_status', $filters['option_status']);
        }

        // Ordenação padrão pelo índice composto (otimizado)
        $query->orderBy('option_name', 'asc');

        return $query->paginate($perPage);
    }

    public function findByIdAndCompany(int $id, int $companyId): ?SystemOption
    {
        return $this->model->where('id', $id)->where('company_id', $companyId)->first();
    }

    public function updateOption(int $id, int $companyId, array $data): ?SystemOption
    {
        $option = $this->findByIdAndCompany($id, $companyId);
        if ($option) {
            $option->update($data);
        }
        return $option;
    }
}