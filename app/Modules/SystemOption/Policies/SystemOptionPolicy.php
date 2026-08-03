<?php

namespace App\Modules\SystemOption\Policies;

use App\Models\SystemOption;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SystemOptionPolicy
{
    // Verifica se pode ver a lista
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('system-option.view');
    }

    // Verifica se pode ver um registro específico
    public function view(User $user, SystemOption $option): bool
    {
        return $user->hasPermissionTo('system-option.view') && 
               ($option->company_id === $user->company_id || $option->company_id === null);
    }

    // Verifica se pode criar
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('system-option.create');
    }

    // Verifica se pode editar
    public function update(User $user, SystemOption $option): bool
    {
        return $user->hasPermissionTo('system-option.edit') && 
               $option->company_id === $user->company_id; // Bloqueia edição de globais
    }

    // Verifica se pode excluir
    public function delete(User $user, SystemOption $option): bool
    {
        return $user->hasPermissionTo('system-option.delete') && 
               $option->company_id === $user->company_id; // Bloqueia exclusão de globais
    }
}