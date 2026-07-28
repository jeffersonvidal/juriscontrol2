<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Tag extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    /**
     * Campos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'company_id',
        'name',
        'name_slug',
        'color',
        'bg_color',
        'is_active',
    ];

    /**
     * Campos que devem ser castados
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot do model para eventos
     * Gera slug automaticamente antes de salvar
     */
    protected static function boot()
    {
        parent::boot();

        // Gera slug automaticamente ao salvar
        static::saving(function ($tag) {
            if (empty($tag->name_slug)) {
                $tag->name_slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Relacionamento com Company (tenant)
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Configuração de Activity Log
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'color', 'bg_color', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}