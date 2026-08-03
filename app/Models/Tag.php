<?php

namespace App\Models;

use App\Modules\Core\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Tag extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, HasCompany, Auditable;

    protected $fillable = [
        'company_id',
        'name',
        'name_slug',
        'color',
        'bg_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($tag) {
            if (!empty($tag->name)) {
                $tag->name_slug = Str::slug($tag->name);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected $auditInclude = [
        'name',
        'color',
        'bg_color',
        'is_active',
    ];

    protected $auditExclude = [
        'name_slug',
    ];

        /**
     * Injeta o company_id na auditoria via método nativo do pacote.
     */
       public function transformAudit(array $data): array
    {
        $data['company_id'] = auth()->user()?->company_id ?? $this->company_id;
        return $data;
    }
}