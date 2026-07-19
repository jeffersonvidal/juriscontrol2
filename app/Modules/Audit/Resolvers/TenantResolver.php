<?php

namespace App\Modules\Audit\Resolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;

/**
 * TenantResolver
 * --------------------------------------------------------
 * Resolver customizado do laravel-auditing que injeta
 * automaticamente o company_id em cada log de auditoria.
 *
 * Regra do playbook: "TODO model tem company_id"
 * (audit logs precisam rastrear qual tenant originou a alteração)
 *
 * Princípio: Single Responsibility — apenas resolve o tenant.
 */
class TenantResolver implements Resolver
{
    /**
     * Resolve o company_id do usuário autenticado.
     *
     * @param  Auditable  $auditable  Model que está sendo auditado
     * @return int|null               company_id ou null (system-level)
     */
    public static function resolve(Auditable $auditable)
    {
        // Guard Clause: sem usuário autenticado = evento system-level
        if (! auth()->check()) {
            return null;
        }

        // Retorna o company_id do usuário (null para super-admin)
        return auth()->user()->company_id;
    }
}