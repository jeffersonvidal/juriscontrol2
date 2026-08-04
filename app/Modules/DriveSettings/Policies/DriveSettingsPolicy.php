<?php

namespace App\Modules\DriveSettings\Policies;

use App\Models\User;
use App\Models\SystemOption;
use Illuminate\Auth\Access\HandlesAuthorization;

class DriveSettingsPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar se o usuário pode visualizar qualquer configuração
     */
    public function viewAny(User $user): bool
    {
        // Para a listagem (index), verificamos apenas a permissão global
        //return $user->hasPermissionTo('drive_settings.view');
        return true;
    }

    /**
     * Determinar se o usuário pode visualizar uma configuração específica
     */
    public function view(User $user, SystemOption $option): bool
    {
        // Verificar se pertence à mesma empresa
        if ($user->company_id !== $option->company_id) {
            return false;
        }

        return $user->hasPermissionTo('drive_settings.view');
    }

    /**
     * Determinar se o usuário pode criar configurações
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('drive_settings.create');
    }

    /**
     * Determinar se o usuário pode editar configurações
     */
    public function update(User $user, SystemOption $option): bool
    {
        // Verificar se pertence à mesma empresa
        if ($user->company_id !== $option->company_id) {
            return false;
        }

        return $user->hasPermissionTo('drive_settings.edit');
    }

    /**
     * Determinar se o usuário pode deletar configurações
     */
    public function delete(User $user, SystemOption $option): bool
    {
        // Verificar se pertence à mesma empresa
        if ($user->company_id !== $option->company_id) {
            return false;
        }

        return $user->hasPermissionTo('drive_settings.delete');
    }
}