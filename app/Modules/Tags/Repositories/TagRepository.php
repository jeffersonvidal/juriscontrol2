<?php

namespace App\Modules\Tags\Repositories;

use App\Models\Tag;
use Illuminate\Pagination\LengthAwarePaginator;

class TagRepository
{
    public function __construct(protected Tag $model) {}

    /**
     * Retorna tags paginadas com filtros opcionais.
     * O CompanyScope global já filtra por company_id automaticamente.
     */
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    /**
     * Busca uma tag pelo ID ou lança exceção.
     */
    public function findOrFail(int $id): Tag
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Cria uma nova tag.
     */
    public function create(array $data): Tag
    {
        return $this->model->create($data);
    }

    /**
     * Atualiza uma tag existente.
     */
    public function update(Tag $tag, array $data): bool
    {
        return $tag->update($data);
    }

    /**
     * Remove uma tag (soft delete).
     */
    public function delete(Tag $tag): bool
    {
        return $tag->delete();
    }
}