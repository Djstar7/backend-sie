<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Str;

class ListDocumentRequired extends Model
{
    use HasFactory;

    protected $table = 'list_document_requireds';

    protected $fillable = [
        'name',
        'guide',
        'category',
        'is_required',
        'file_types',
        'max_size_mb',
        'is_active',
    ];

    protected $casts = [
        'file_types' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Scope pour les documents actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par categorie
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Liste des categories disponibles
     */
    public static function getCategories(): array
    {
        return [
            'administratifs',
            'financiers',
            'voyage',
            'academiques_professionnels',
            'medicaux_judiciaires',
            'autres',
        ];
    }
}
