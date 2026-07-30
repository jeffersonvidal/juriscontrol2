<?php

namespace App\Modules\Tags\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TagPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('tags.view');
    }

    public function view(User $user, Tag $tag): bool
    {
        return $user->company_id === $tag->company_id && $user->hasPermissionTo('tags.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('tags.create');
    }

    /**
     * Determina se o usuário pode atualizar uma tag específica.
     */
    public function update(User $user, Tag $tag): bool
    {
        $isSameCompany = $user->company_id === $tag->company_id;
        $hasPermission = $user->hasPermissionTo('tags.edit');
        
        // LOG DE DIAGNÓSTICO: Se ainda der 403, este log nos dirá o motivo exato
        Log::info('🔍 TagPolicy@update: Verificação de Acesso', [
            'user_id' => $user->id,
            'user_company_id' => $user->company_id,
            'tag_id' => $tag->id,
            'tag_company_id' => $tag->company_id,
            'is_same_company' => $isSameCompany ? 'SIM' : 'NÃO',
            'has_edit_permission' => $hasPermission ? 'SIM' : 'NÃO',
            'resultado_final' => ($isSameCompany && $hasPermission) ? 'AUTORIZADO' : 'NEGADO'
        ]);

        return $isSameCompany && $hasPermission;
    }

    public function delete(User $user, Tag $tag): bool
    {
        return $user->company_id === $tag->company_id && $user->hasPermissionTo('tags.delete');
    }
}