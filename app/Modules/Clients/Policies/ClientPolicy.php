<?php

namespace App\Modules\Clients\Policies;

use App\Models\User;
use App\Modules\Rbac\Enums\Permission;
use App\Modules\Rbac\Policies\BasePolicy;

/**
 * ClientPolicy
 * --------------------------------------------------------
 * Autorização por recurso seguindo o padrão do playbook.
 * Estende BasePolicy para reutilizar lógica de tenant.
 */
class ClientPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $this->check($user, Permission::CLIENTS_VIEW->value);
    }

    public function view(User $user, $client): bool
    {
        return $this->check($user, Permission::CLIENTS_VIEW->value, $client);
    }

    public function create(User $user): bool
    {
        return $this->check($user, Permission::CLIENTS_CREATE->value);
    }

    public function update(User $user, $client): bool
    {
        return $this->check($user, Permission::CLIENTS_UPDATE->value, $client);
    }

    public function delete(User $user, $client): bool
    {
        return $this->check($user, Permission::CLIENTS_DELETE->value, $client);
    }
}