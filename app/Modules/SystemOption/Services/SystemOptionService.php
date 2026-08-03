<?php

namespace App\Modules\SystemOption\Services;

use App\Modules\SystemOption\Repositories\SystemOptionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SystemOptionService
{
    // Injeção do Repository
    public function __construct(
        protected SystemOptionRepository $repository
    ) {
    }

    public function getAll(array $filters = [])
    {
        return $this->repository->paginate($filters);
    }

    public function getById(int $id)
    {
        return $this->repository->findOrFail($id);
    }

    // public function create(array $data)
    // {
    //     // Regra de negócio: Garantir isolamento multi-tenant
    //     $data['company_id'] = Auth::user()->company_id;

    //     return DB::transaction(function () use ($data) {
    //         return $this->repository->create($data);
    //     });
    // }

    public function create(array $data)
    {
        // Regra de negócio: Garantir isolamento multi-tenant
        $data['company_id'] = Auth::user()->company_id;

        // Salva apenas o option_name em maiúsculas
        if (isset($data['option_name'])) {
            $data['option_name'] = mb_strtoupper($data['option_name'], 'UTF-8');
        }

        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    // public function update(int $id, array $data)
    // {
    //     $option = $this->repository->findOrFail($id);

    //     // Regra de negócio: Impedir edição de opções globais (company_id null) por usuários de tenant
    //     // Ou permitir apenas se a opção já pertencer à empresa do usuário (garantido pelo Scope, mas reforçado aqui)
    //     if ($option->company_id !== Auth::user()->company_id) {
    //         throw new \Exception('Você não tem permissão para modificar esta opção do sistema.');
    //     }

    //     return DB::transaction(function () use ($option, $data) {
    //         return $this->repository->update($option, $data);
    //     });
    // }

    public function update(int $id, array $data)
    {
        $option = $this->repository->findOrFail($id);

        // Regra de negócio: Impedir edição de opções globais (company_id null)
        // ou de opções que não pertençam à empresa do usuário.
        if ($option->company_id !== Auth::user()->company_id) {
            throw new \Exception('Você não tem permissão para modificar esta opção do sistema.');
        }

        // Salva apenas o option_name em maiúsculas
        if (isset($data['option_name'])) {
            $data['option_name'] = mb_strtoupper($data['option_name'], 'UTF-8');
        }

        return DB::transaction(function () use ($option, $data) {
            return $this->repository->update($option, $data);
        });
    }

    public function delete(int $id)
    {
        $option = $this->repository->findOrFail($id);

        if ($option->company_id !== Auth::user()->company_id) {
            throw new \Exception('Você não tem permissão para excluir esta opção do sistema.');
        }

        return DB::transaction(function () use ($option) {
            return $this->repository->delete($option);
        });
    }
}