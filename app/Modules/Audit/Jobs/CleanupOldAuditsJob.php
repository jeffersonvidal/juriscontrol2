<?php

namespace App\Modules\Audit\Jobs;

use App\Modules\Audit\Models\AuditLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * CleanupOldAuditsJob
 * --------------------------------------------------------
 * Remove logs de auditoria mais antigos que X dias.
 * Regra do playbook: "Async First (filas para tarefas pesadas)".
 *
 * Princípios:
 *  - Idempotente (pode rodar N vezes sem efeito colateral)
 *  - Chunked deletion (evita travar o banco)
 *  - ShouldQueue (execução assíncrona)
 */
class CleanupOldAuditsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout de execução (segundos).
     */
    public int $timeout = 300;

    /**
     * Número máximo de tentativas.
     */
    public int $tries = 3;

    /**
     * Construtor: define a fila e parâmetros do job.
     * 
     * IMPORTANTE: NÃO redefinir a propriedade $queue (já existe na trait Queueable).
     * Usar o método onQueue() para definir a fila dinamicamente.
     */
    public function __construct(
        public int $retentionDays = 365
    ) {
        // Define a fila "maintenance" para este job
        $this->onQueue('maintenance');
    }

    /**
     * Executa a limpeza em chunks para não travar o banco.
     */
    public function handle(): void
    {
        $cutoffDate = now()->subDays($this->retentionDays);
        $deleted = 0;

        // Deleta em chunks de 1000 registros (evita lock prolongado)
        do {
            $chunkDeleted = AuditLog::withoutGlobalScopes() // precisa ver todos os tenants
                                    ->where('created_at', '<', $cutoffDate)
                                    ->take(1000)
                                    ->get();

            foreach ($chunkDeleted as $audit) {
                $audit->forceDelete();
                $deleted++;
            }
        } while ($chunkDeleted->count() === 1000);

        info("CleanupOldAuditsJob: {$deleted} logs removidos (cutoff: {$cutoffDate})");
    }
}