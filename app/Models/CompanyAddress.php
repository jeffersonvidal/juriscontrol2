<?php

namespace App\Models;

use App\Modules\Core\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * CompanyAddress
 * --------------------------------------------------------
 * Model de endereços das empresas.
 * Uma empresa pode ter múltiplos endereços (matriz, filiais, etc).
 * 
 * Regras do playbook:
 *  - Tem company_id (multi-tenancy)
 *  - Usa HasCompany trait para CompanyScope automático
 *  - Soft deletes quando aplicável
 */
class CompanyAddress extends Model
{
    use SoftDeletes, HasCompany;

    protected $table = 'company_addresses';

    protected $fillable = [
        'company_id',
        'label',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'zip_code',
        'country',
        'is_default',
    ];

    protected $casts = [
        'company_id' => 'integer',
        'is_default' => 'boolean',
    ];

    /**
     * Relacionamento: Endereço pertence a uma empresa.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Accessor: retorna o endereço formatado como string.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->number,
            $this->complement,
            $this->district,
            $this->city . ' - ' . $this->state,
            'CEP: ' . $this->zip_code,
        ]);

        return implode(', ', $parts);
    }
}