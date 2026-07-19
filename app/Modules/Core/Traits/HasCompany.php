<?php

namespace App\Modules\Core\Traits;

use App\Modules\Core\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Trait HasCompany
 * --------------------------------------------------------
 * Deve ser usada em TODOS os models do sistema (exceto Company).
 * Garante isolamento multi-tenant automático e preenchimento do company_id.
 */
trait HasCompany
{
    protected static function bootHasCompany(): void
    {
        // Adiciona o scope global ANTES de qualquer query
        static::addGlobalScope(new CompanyScope());

        // Hook: antes de criar, injeta o company_id automaticamente
        static::creating(function (Model $model): void {
            // Guard Clause: só preenche se ainda não estiver definido
            if (empty($model->company_id)) {
                // Lê da sessão para evitar loop infinito
                $guard = app('auth')->getDefaultDriver();
                $userId = session($guard);
                
                if ($userId) {
                    $companyId = DB::table('users')->where('id', $userId)->value('company_id');
                    if (! empty($companyId)) {
                        $model->company_id = $companyId;
                    }
                }
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
     * Scope auxiliar para buscar SEM o filtro de tenant.
     */
    public function scopeWithoutCompanyScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(CompanyScope::class);
    }
}