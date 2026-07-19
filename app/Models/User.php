<?php

namespace App\Models;

use App\Modules\Core\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Modules\Rbac\Traits\HasPermissionsHelper;

/**
 * User
 * --------------------------------------------------------
 * Model de usuário com multi-tenancy e RBAC.
 * Usa HasCompany para isolamento por empresa.
 * Usa HasRoles (spatie/laravel-permission) para permissões.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasCompany, HasPermissionsHelper;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',   // Vínculo multi-tenant
        'status',       // Ativo/Inativo
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relacionamento: usuário pertence a uma empresa.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Verifica se é super-admin (acesso cross-tenant).
     */
    public function isSuperAdmin(): bool
    {
        return empty($this->company_id) && $this->hasRole('super-admin');
    }
}