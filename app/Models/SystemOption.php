<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class SystemOption extends Model implements AuditableContract
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'company_id',
        'option_name',
        'option_value',
        'option_description',
        'option_status',
    ];

    protected $casts = [
        'option_status' => 'boolean',
    ];

    // CRÍTICO: Conforme Auditoria.txt, garante que o company_id seja registrado no log de auditoria
    public function transformAudit(array $data): array
    {
        $data['company_id'] = auth()->user()?->company_id ?? $this->company_id;
        return $data;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Helper para obter o valor efetivo (prioriza o da empresa, fallback para o global)
    public static function getEffectiveValue(string $optionName, ?int $companyId): ?string
    {
        $option = self::where('option_name', $optionName)
            ->where(function ($query) use ($companyId) {
                $query->where('company_id', $companyId)
                      ->orWhereNull('company_id');
            })
            ->orderBy('company_id', 'desc') // Prioriza o que tem company_id (não null)
            ->first();

        return $option ? $option->option_value : null;
    }

    /**
     * Scope para filtrar por company_id (Padrão do Playbook)
     */
    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope para buscar por nome da opção
     */
    public function scopeByOptionName($query, string $optionName)
    {
        return $query->where('option_name', $optionName);
    }
}