<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Company
 * --------------------------------------------------------
 * Model raiz do Multi-Tenant.
 * 
 * CORREÇÃO: Garantir que 'is_active' está no array $casts como boolean.
 */
class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'trade_name',
        'trade_name_slug',
        'corporate_reason',
        'email',
        'cnpj_cpf',
        'phone',
        'user_id',
        'is_active',
    ];

    // CORREÇÃO CRÍTICA: is_active DEVE estar como boolean
    protected $casts = [
        'is_active' => 'boolean',
        'user_id'   => 'integer',
    ];

    /**
     * Boot do model: gera o slug automaticamente.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Company $company): void {
            if (empty($company->trade_name_slug) || $company->isDirty('trade_name')) {
                $company->trade_name_slug = Str::slug($company->trade_name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CompanyAddress::class);
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getDefaultAddressAttribute(): ?CompanyAddress
    {
        return $this->addresses()->where('is_default', true)->first();
    }
}