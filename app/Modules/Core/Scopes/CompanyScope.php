<?php

namespace App\Modules\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

/**
 * CompanyScope
 * --------------------------------------------------------
 * Scope global que filtra TODAS as queries pelo company_id.
 * 
 * CORREÇÃO CRÍTICA DE LOOP INFINITO:
 * Lê o ID do usuário DIRETAMENTE da sessão (ex: session('web')).
 * NUNCA use Auth::id() ou Auth::user() aqui dentro, pois isso 
 * pode disparar a carga do model User, aplicando este mesmo scope
 * recursivamente e estourando a memória (Allowed memory size exhausted).
 */
class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // 1. Lê o ID do usuário diretamente da sessão (Zero Eloquent)
        $guard = app('auth')->getDefaultDriver(); // geralmente 'web'
        $userId = session($guard);

        // Guard Clause: sem usuário na sessão
        if (! $userId) {
            return;
        }

        // 2. Busca o company_id via Query Builder puro (bypass total do Eloquent)
        $companyId = DB::table('users')->where('id', $userId)->value('company_id');

        // 3. Super-admin (company_id nulo) vê todos os registros
        if (empty($companyId)) {
            return;
        }

        // 4. Aplica o filtro de isolamento multi-tenant
        $builder->where($model->qualifyColumn('company_id'), $companyId);
    }
}