<?php

namespace App\Modules\Core\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SystemOptionScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        // Se houver usuário logado E ele tiver um company_id válido
        if ($user && !empty($user->company_id)) {
            $builder->where(function ($query) use ($user) {
                $query->where('company_id', $user->company_id)
                      ->orWhereNull('company_id'); // Permite ver opções globais do sistema
            });
        } else {
            // Se não houver usuário ou ele não tiver empresa, mostra apenas opções globais
            $builder->whereNull('company_id');
        }
    }
}