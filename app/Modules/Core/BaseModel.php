<?php

namespace App\Modules\Core;

use App\Modules\Core\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * BaseModel
 * --------------------------------------------------------
 * Classe base para TODOS os models do sistema.
 * Já aplica:
 *  - Multi-tenancy (HasCompany)
 *  - SoftDeletes (regra do playbook)
 *  - Auditing (laravel-auditing já instalado)
 */
abstract class BaseModel extends Model implements AuditableContract
{
    use HasCompany;       // Isolamento multi-tenant automático
    use SoftDeletes;      // Soft deletes quando aplicável
    use Auditable;        // Auditoria de alterações

    /**
     * Formato padrão das datas (compatível com JS/frontend).
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Casts padrão para todos os models.
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];
}