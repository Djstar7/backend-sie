<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Country extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'countrys';

    protected $fillable = [
        'name',
        'iso_code',
        'phone_code',
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

    function profil()
    {
        return $this->hasMany(Profil::class, 'country_id', 'id');
    }
    function visaRequests()
    {
        return $this->hasMany(VisaRequest::class, 'country_id', 'id');
    }
    function countryVisaTypes()
    {
        return $this->hasMany(CountryVisaType::class, 'country_id', 'id');
    }
}
