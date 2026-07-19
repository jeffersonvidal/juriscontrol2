<?php

namespace App\Modules\Audit\Models;

use App\Models\Company;
use App\Modules\Core\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Models\Audit as BaseAudit;

/**
 * AuditLog
 * --------------------------------------------------------
 * Extende o Audit padrão do laravel-auditing para:
 *  - Aplicar CompanyScope automaticamente (isolamento multi-tenant)
 *  - Adicionar scopes auxiliares para filtros comuns
 *  - Relacionar com Company e User
 *
 * Regra do playbook: "TODO query usa CompanyScope automaticamente"
 */
class AuditLog extends BaseAudit
{
    /**
     * Boot: aplica o CompanyScope global.
     * Garante que NENHUM log de outro tenant seja retornado.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope());
    }

    /**
     * Relacionamento: audit pertence a um tenant.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope: filtra por tipo de model auditado.
     * Exemplo: AuditLog::ofType(Client::class)
     */
    public function scopeOfType(Builder $query, string $modelClass): Builder
    {
        return $query->where('auditable_type', $modelClass);
    }

    /**
     * Scope: filtra por evento (created, updated, deleted, etc).
     */
    public function scopeOfEvent(Builder $query, string $event): Builder
    {
        return $query->where('event', $event);
    }

    /**
     * Scope: filtra por usuário que realizou a ação.
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: filtra por período (data inicial e final).
     */
    public function scopeInPeriod(Builder $query, $from, $to): Builder
    {
        return $query
            ->where('created_at', '>=', $from)
            ->where('created_at', '<=', $to);
    }

    /**
     * Scope: busca em campos antigos e novos (texto livre).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('old_values', 'LIKE', "%{$term}%")
              ->orWhere('new_values', 'LIKE', "%{$term}%")
              ->orWhere('url', 'LIKE', "%{$term}%")
              ->orWhere('ip_address', 'LIKE', "%{$term}%");
        });
    }
}