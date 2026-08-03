<?php

namespace App\Modules\SystemOption\Repositories;

use App\Models\SystemOption;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SystemOptionRepository
{
    // Injeção de dependência do Model
    public function __construct(
        protected SystemOption $model
    ) {}

    // Paginação com filtros opcionais
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('option_name', 'like', "%{$filters['search']}%")
                  ->orWhere('option_description', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['option_status']) && $filters['option_status'] !== '') {
            $query->where('option_status', (bool) $filters['option_status']);
        }

        return $query->orderBy('option_name', 'asc')->paginate($perPage);
    }

    public function findOrFail(int $id): SystemOption
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): SystemOption
    {
        return $this->model->create($data);
    }

    public function update(SystemOption $option, array $data): bool
    {
        return $option->update($data);
    }

    public function delete(SystemOption $option): bool
    {
        return $option->delete();
    }
}