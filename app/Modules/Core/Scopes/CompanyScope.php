<?php

namespace App\Modules\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * CompanyScope
 * --------------------------------------------------------
 * Scope global que filtra TODAS as queries pelo company_id
 * do usuário autenticado. Garante que NENHUM registro de
 * outra empresa seja acessado (regra crítica do playbook).
 */
class CompanyScope implements Scope
{
    /**
     * Aplica o filtro de tenant na query.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Se não houver usuário autenticado, não aplica filtro
        // (evita quebrar commands/migrations/seeds)
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super-admin (sem company_id) vê todos os registros
        if (empty($user->company_id)) {
            return;
        }

        // Aplica o filtro: WHERE company_id = ?
        $builder->where($model->qualifyColumn('company_id'), $user->company_id);
    }
}