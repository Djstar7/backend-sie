<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Notification extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'content',
        'status',
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
