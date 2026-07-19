<?php

namespace App\Modules\Rbac\Traits;

use App\Modules\Rbac\Enums\Permission;
use App\Modules\Rbac\Enums\Role;

/**
 * Trait HasPermissionsHelper
 * --------------------------------------------------------
 * Métodos de conveniência para o model User.
 * Evita espalhar lógica de permissão pelo código.
 */
trait HasPermissionsHelper
{
    /**
     * Verifica se o usuário possui um role específico (via enum).
     */
    public function hasRoleEnum(Role $role): bool
    {
        return $this->hasRole($role->value);
    }

    /**
     * Verifica se o usuário possui uma permissão específica (via enum).
     */
    public function hasPermissionEnum(Permission $permission): bool
    {
        return $this->can($permission->value);
    }

    /**
     * Verifica se o usuário tem TODAS as permissões listadas.
     */
    public function hasAllPermissionsEnum(Permission ...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $this->can($permission->value)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verifica se o usuário tem ALGUMA das permissões listadas.
     */
    public function hasAnyPermissionEnum(Permission ...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->can($permission->value)) {
                return true;
            }
        }
        return false;
    }
}