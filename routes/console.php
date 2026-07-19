<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Modules\Audit\Jobs\CleanupOldAuditsJob;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/**
 * Console Routes
 * --------------------------------------------------------
 * Agenda tarefas automáticas do sistema.
 * Regra do playbook: "Async First" + "Observabilidade".
 */

// Limpeza de logs antigos: todo domingo às 02:00
Schedule::job(new CleanupOldAuditsJob(365))
    ->weeklyOn(0, '02:00')     // 0 = domingo
    ->name('cleanup-old-audits')
    ->withoutOverlapping();    // evita sobreposição (job já é async via fila)