<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class CountryVisaType extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'country_visa_types';

    protected $fillable = [
        'country_id',
        'visa_type_id',
        'price_base',
        'price_per_child',
        'processing_duration_min',
        'processing_duration_max',
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
    function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id', 'id');
    }
    public function requiredDocuments()
    {
        return $this->belongsToMany(RequiredDocument::class, 'country_visa_type_required_document', 'country_visa_type_id', 'required_document_id');
    }
}
