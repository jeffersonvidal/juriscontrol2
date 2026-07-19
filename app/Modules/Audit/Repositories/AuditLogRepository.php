<?php

namespace App\Modules\Audit\Repositories;

use App\Modules\Audit\Models\AuditLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * AuditLogRepository
 * --------------------------------------------------------
 * Centraliza consultas de logs de auditoria.
 * Regra do playbook: "Usar Services, Repositories".
 *
 * Princípios:
 *  - Single Responsibility (apenas consulta)
 *  - Eager Loading (evita N+1)
 *  - Lazy loading controlado
 */
class AuditLogRepository
{
    /**
     * Lista paginada de logs com filtros.
     *
     * @param  array  $filters  Filtros opcionais
     * @return LengthAwarePaginator
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = AuditLog::with(['user', 'company', 'auditable']);

        // Aplica filtros dinamicamente
        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['auditable_type'])) {
            $query->ofType($filters['auditable_type']);
        }

        if (! empty($filters['event'])) {
            $query->ofEvent($filters['event']);
        }

        if (! empty($filters['user_id'])) {
            $query->byUser((int) $filters['user_id']);
        }

        if (! empty($filters['from']) && ! empty($filters['to'])) {
            $query->inPeriod($filters['from'], $filters['to']);
        }

        // Ordenação padrão: mais recente primeiro
        return $query->orderByDesc('created_at')
                     ->paginate($perPage);
    }

    /**
     * Busca um log específico por ID.
     * Guard Clause: retorna null se não existir.
     */
    public function find(int $id): ?AuditLog
    {
        return AuditLog::with(['user', 'company'])
                       ->find($id);
    }

    /**
     * Conta logs por evento (para dashboard/gráficos).
     */
    public function countByEvent(): Collection
    {
        return AuditLog::selectRaw('event, COUNT(*) as total')
                       ->groupBy('event')
                       ->get();
    }

    /**
     * Conta logs por tipo de model (para dashboard).
     */
    public function countByType(): Collection
    {
        return AuditLog::selectRaw('auditable_type, COUNT(*) as total')
                       ->groupBy('auditable_type')
                       ->get();
    }
}