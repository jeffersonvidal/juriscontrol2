<?php

namespace App\Modules\Core\Traits;

use App\Modules\Core\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait HasCompany
 * --------------------------------------------------------
 * Deve ser usada em TODOS os models do sistema.
 * Garante isolamento multi-tenant automático em todas as queries.
 * Regra do playbook: "TODO model tem company_id" e
 * "TODO query usa CompanyScope automaticamente".
 */
trait HasCompany
{
    /**
     * Boot da trait: registra o CompanyScope global.
     * Toda query feita no model será automaticamente filtrada
     * pelo company_id do usuário autenticado.
     */
    protected static function bootHasCompany(): void
    {
        // Adiciona o scope global ANTES de qualquer query
        static::addGlobalScope(new CompanyScope());

        // Hook: antes de criar, injeta o company_id automaticamente
        static::creating(function (Model $model): void {
            // Guard Clause: só preenche se ainda não estiver definido
            if (empty($model->company_id) && auth()->check()) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    /**
     * Relacionamento: o model pertence a uma Company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    /**
     * Scope auxiliar para buscar SEM o filtro de tenant
     * (uso restrito a super-admin / contexto system).
     */
    public function scopeWithoutCompanyScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(CompanyScope::class);
    }
}