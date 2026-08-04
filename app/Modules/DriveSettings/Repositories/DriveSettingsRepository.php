<?php

namespace App\Modules\DriveSettings\Repositories;

use App\Models\SystemOption;
use Illuminate\Pagination\LengthAwarePaginator;

class DriveSettingsRepository
{
    private SystemOption $model;

    public function __construct(SystemOption $model)
    {
        $this->model = $model;
    }

    public function paginate(int $companyId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->forCompany($companyId)
            ->orderBy('option_name', 'asc')
            ->paginate($perPage);
    }

    public function findOrFail(int $id, int $companyId): SystemOption
    {
        return $this->model->forCompany($companyId)->findOrFail($id);
    }

    public function findByOptionName(string $optionName, int $companyId): ?SystemOption
    {
        return $this->model->forCompany($companyId)
            ->byOptionName($optionName)
            ->first();
    }

        /**
     * Criar ou atualizar registro (lida corretamente com SoftDeletes e Unique Index)
     */
    public function updateOrCreate(int $companyId, string $optionName, array $data): SystemOption
    {
        // 1. Busca o registro, INCLUINDO os que foram soft deleted
        $option = $this->model->withTrashed()
            ->where('company_id', $companyId)
            ->where('option_name', $optionName)
            ->first();

        if ($option) {
            // 2. Se existe mas está deletado, restaura primeiro
            if ($option->trashed()) {
                $option->restore();
            }
            
            // 3. Atualiza os dados
            $option->update($data);
            return $option;
        }

        // 4. Se realmente não existe, cria um novo
        return $this->model->create(array_merge([
            'company_id' => $companyId,
            'option_name' => $optionName,
        ], $data));
    }

    public function delete(SystemOption $option): bool
    {
        return $option->delete(); // SoftDeletes cuidará disso
    }
}