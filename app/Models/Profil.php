<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Profil extends Model
{
    use HasFactory;
    protected $table = 'profils';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'status_mat',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'country_id',
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
    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}
