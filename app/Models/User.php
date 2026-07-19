<?php

namespace App\Models;

use App\Modules\Core\Traits\HasCompany;
use App\Modules\Rbac\Traits\HasPermissionsHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * User
 * --------------------------------------------------------
 * NOTA: O método company() NÃO deve ser declarado aqui, 
 * pois já é fornecido pela trait HasCompany.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasCompany, HasPermissionsHelper;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'status',
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

    public function isSuperAdmin(): bool
    {
        return empty($this->company_id) && $this->hasRole('super-admin');
    }
}