<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class RequiredDocument extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'required_documents';

    // Les documents ne contiennent que le nom
    // Les criteres d'eligibilite (status_mat, min_age, max_age) sont dans la table pivot
    protected $fillable = [
        'name',
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

    public function countryVisaTypes()
    {
        return $this->belongsToMany(CountryVisaType::class, 'country_visa_type_required_document', 'required_document_id', 'country_visa_type_id')
            ->withPivot(['id', 'status_mat', 'min_age', 'max_age'])
            ->withTimestamps();
    }
}
