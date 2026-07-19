<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Company
 * --------------------------------------------------------
 * NÃO usa HasCompany (ela é a raiz do tenant, não tem company_id).
 * Representa a empresa/tenant do SaaS.
 */
class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'trade_name',
        'document',
        'email',
        'phone',
        'status',
        'trial_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Usuários da empresa.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}