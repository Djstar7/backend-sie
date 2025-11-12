<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Documentation extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'documentation';

    protected $fillable = [
        'title',
        'content',
    ];
    // Cast content en array pour manipuler JSON facilement
    protected $casts = [
        'content' => 'array',
    ];
    public $incrementing = false; // pas d'auto-incrément
    protected $keyType = 'string'; // l'id est un string

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
