<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EligibilityDocument extends Model
{
    protected $table = 'country_visa_type_required_document';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'country_visa_type_id',
        'required_document_id',
        'status_mat',
        'min_age',
        'max_age',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function countryVisaType()
    {
        return $this->belongsTo(CountryVisaType::class, 'country_visa_type_id');
    }

    public function requiredDocument()
    {
        return $this->belongsTo(RequiredDocument::class, 'required_document_id');
    }

    /**
     * Creer ou mettre a jour les eligibilites pour un CountryVisaType
     */
    public static function syncEligibilities(
        string $countryVisaTypeId,
        array $documents,
        array $statusMatList,
        ?int $minAge,
        ?int $maxAge
    ): void {
        // Supprimer les anciennes eligibilites pour ces criteres
        foreach ($statusMatList as $statusMat) {
            self::where('country_visa_type_id', $countryVisaTypeId)
                ->where('status_mat', $statusMat)
                ->where('min_age', $minAge)
                ->where('max_age', $maxAge)
                ->delete();
        }

        // Creer les nouvelles eligibilites
        foreach ($documents as $docName) {
            $document = RequiredDocument::firstOrCreate(['name' => $docName]);

            foreach ($statusMatList as $statusMat) {
                self::firstOrCreate([
                    'country_visa_type_id' => $countryVisaTypeId,
                    'required_document_id' => $document->id,
                    'status_mat' => $statusMat,
                    'min_age' => $minAge,
                    'max_age' => $maxAge,
                ]);
            }
        }
    }

    /**
     * Ajouter des eligibilites sans supprimer les existantes
     */
    public static function addEligibilities(
        string $countryVisaTypeId,
        array $documents,
        array $statusMatList,
        ?int $minAge,
        ?int $maxAge
    ): void {
        foreach ($documents as $docName) {
            $document = RequiredDocument::firstOrCreate(['name' => $docName]);

            foreach ($statusMatList as $statusMat) {
                self::firstOrCreate([
                    'country_visa_type_id' => $countryVisaTypeId,
                    'required_document_id' => $document->id,
                    'status_mat' => $statusMat,
                    'min_age' => $minAge,
                    'max_age' => $maxAge,
                ]);
            }
        }
    }
}
