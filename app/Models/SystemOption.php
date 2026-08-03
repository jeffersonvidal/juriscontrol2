<?php

namespace App\Models;

use App\Modules\Core\Scopes\SystemOptionScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemOption extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable explícito para segurança contra atribuição em massa
    protected $fillable = [
        'company_id',
        'option_name',
        'option_value',
        'option_description',
        'option_status',
    ];

    // Casts para garantir tipagem correta
    protected $casts = [
        'option_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Aplica o escopo global de multi-tenancy automaticamente em todas as queries
    protected static function booted(): void
    {
        static::addGlobalScope(new SystemOptionScope());
    }
}