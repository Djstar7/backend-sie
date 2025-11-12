<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class VisaType extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = "visa_types";
    protected $fillable = [
        'name',
        'description',
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
    function countryVisaTypes()
    {
        return $this->hasMany(CountryVisaType::class, 'visa_type_id', 'id');
    }
    function visaRequests()
    {
        return $this->hasMany(VisaRequest::class, 'visa_type_id', 'id');
    }
}
