<?php

namespace App\Modules\Companies\Policies;

use App\Models\Company;
use App\Models\User;
use App\Modules\Rbac\Enums\Permission;

/**
 * CompanyPolicy
 * --------------------------------------------------------
 * Define quem pode executar ações no módulo Companies.
 * Regra do playbook: "Policies do Laravel para autorização por recurso".
 */
class CompanyPolicy
{
    /**
     * Determina se o usuário pode visualizar a lista.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::COMPANIES_VIEW->value);
    }

    /**
     * Determina se o usuário pode visualizar uma empresa específica.
     */
    public function view(User $user, Company $company): bool
    {
        return $user->can(Permission::COMPANIES_VIEW->value);
    }

    /**
     * Determina se o usuário pode criar uma empresa.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::COMPANIES_CREATE->value);
    }

    /**
     * Determina se o usuário pode atualizar uma empresa.
     */
    public function update(User $user, Company $company): bool
    {
        return $user->can(Permission::COMPANIES_UPDATE->value);
    }

    /**
     * Determina se o usuário pode excluir uma empresa.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->can(Permission::COMPANIES_DELETE->value);
    }
}