<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class FaqChabot extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'faq_chabot';

    protected $fillable = [
        'question',
        'answer',
        'category',
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
