<?php
namespace App\Modules\SystemOptions\Policies;

use App\Models\User;
use App\Models\SystemOption;

class SystemOptionPolicy
{
    public function viewAny(User $user): bool
    {
        // DEBUG: Isso vai parar a tela e mostrar exatamente o que o Laravel está vendo
        dd([
            'user_id' => $user->id,
            'user_company_id' => $user->company_id,
            'has_permission' => $user->hasPermissionTo('system-options.view'),
            'all_permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
        ]);

        return $user->hasPermissionTo('system-options.view') && $user->company_id !== null;
    }

    public function view(User $user, SystemOption $systemOption): bool
    {
        return $user->hasPermissionTo('system-options.view')
            && $user->company_id === $systemOption->company_id;
    }

    public function update(User $user, SystemOption $systemOption): bool
    {
        return $user->hasPermissionTo('system-options.edit')
            && $user->company_id === $systemOption->company_id;
    }
}