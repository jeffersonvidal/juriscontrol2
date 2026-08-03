<?php

namespace App\Modules\Core\Observers;

use OwenIt\Auditing\Models\Audit;

/**
 * Observer global para injetar company_id em todas as auditorias.
 * Garante isolamento multi-tenant na tabela de logs.
 */
class AuditObserver
{
    /**
     * Antes de salvar a auditoria, injeta o company_id.
     */
    public function saving(Audit $audit): void
    {
        // Se o audit já tem um company_id, mantém. Caso contrário, pega do usuário logado.
        if (!$audit->company_id) {
            $audit->company_id = auth()->user()?->company_id;
        }
    }
}