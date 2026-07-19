<?php

namespace App\Modules\Audit\Services;

use App\Modules\Audit\Repositories\AuditLogRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * AuditLogService
 * --------------------------------------------------------
 * Camada de aplicação para logs de auditoria.
 * Regra do playbook: "Usar Services, Repositories".
 *
 * Princípio: Controller NUNCA contém regras de negócio.
 * Toda lógica de consulta/transformação fica aqui.
 */
class AuditLogService
{
    public function __construct(
        private AuditLogRepository $repository
    ) {}

    /**
     * Lista logs paginados com filtros.
     */
    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    /**
     * Busca log específico.
     */
    public function find(int $id): ?array
    {
        $audit = $this->repository->find($id);

        // Guard Clause: não existe
        if (! $audit) {
            return null;
        }

        // Transforma para formato amigável ao frontend
        return [
            'id'             => $audit->id,
            'event'          => $audit->event,
            'auditable_type' => class_basename($audit->auditable_type),
            'auditable_id'   => $audit->auditable_id,
            'old_values'     => $audit->old_values,
            'new_values'     => $audit->new_values,
            'url'            => $audit->url,
            'ip_address'     => $audit->ip_address,
            'user_agent'     => $audit->user_agent,
            'user'           => $audit->user?->only(['id', 'name', 'email']),
            'company'        => $audit->company?->only(['id', 'trade_name']),
            'created_at'     => $audit->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Dados para dashboard (gráficos).
     */
    public function dashboardStats(): array
    {
        return [
            'by_event' => $this->repository->countByEvent(),
            'by_type'  => $this->repository->countByType(),
        ];
    }
}