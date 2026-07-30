<?php

namespace App\Models;

use App\Modules\Core\Traits\HasCompany; // Trait padrão do seu Core para Multi-tenancy
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes, HasCompany;

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

    // Relação polimórfica inversa (opcional, para saber onde a tag é usada)
    // public function tasks()
    // {
    //     return $this->morphedByMany(Task::class, 'taggable');
    // }

    // public function cases()
    // {
    //     return $this->morphedByMany(Case::class, 'taggable');
    // }
}





    

    