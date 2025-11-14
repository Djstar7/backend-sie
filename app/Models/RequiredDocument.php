<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class RequiredDocument extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'required_documents';


    protected $fillable = [
        'name',
        'status_mat',
        'min_age',
        'max_age',
    ];

    /**
     * Le statut matrimonial, tu peux aussi créer une constante pour les valeurs possibles.
     */
    const STATUS_MAT_SINGLE = 'single';
    const STATUS_MAT_MARRIED = 'married';
    const STATUS_MAT_DIVORCED = 'divorced';
    const STATUS_MAT_WIDOWED = 'widowed';
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
    public function countryVisaTypes()
    {
        return $this->belongsToMany(CountryVisaType::class, 'country_visa_type_required_document', 'required_document_id', 'country_visa_type_id');
    }
}
