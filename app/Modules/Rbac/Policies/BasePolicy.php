<?php

namespace App\Modules\Rbac\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

/**
 * BasePolicy
 * --------------------------------------------------------
 * Policy base que TODAS as policies do sistema devem estender.
 *
 * Regras do playbook:
 *  - "Policies do Laravel para autorização por recurso"
 *  - "Método authorize() em TODO Form Request"
 *
 * Princípios aplicados:
 *  - Guard Clauses (Fail Fast)
 *  - DRY (lógica centralizada)
 *  - Single Responsibility
 */
abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Verifica se o usuário pode acessar o recurso dentro do seu tenant.
     * Guard Clause: bloqueia acesso cross-tenant.
     */
    protected function belongsToTenant(User $user, Model $model): bool
    {
        // Super-admin vê tudo (cross-tenant)
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Guard Clause: recurso deve pertencer ao mesmo tenant do usuário
        return $user->company_id === $model->company_id;
    }

    /**
     * Helper: verifica permissão + pertencimento ao tenant.
     * Reduz boilerplate nas policies filhas.
     */
    protected function check(User $user, string $permission, ?Model $model = null): bool
    {
        // Guard Clause: usuário precisa ter a permissão
        if (! $user->can($permission)) {
            return false;
        }

        // Se há um model, valida pertencimento ao tenant
        if ($model !== null && ! $this->belongsToTenant($user, $model)) {
            return false;
        }

        return true;
    }
}