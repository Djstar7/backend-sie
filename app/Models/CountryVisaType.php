<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'status_mat',
        'min_age',
        'max_age',
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

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id', 'id');
    }

    public function requiredDocuments()
    {
        return $this->belongsToMany(
            RequiredDocument::class,
            'country_visa_type_required_document',
            'country_visa_type_id',
            'required_document_id'
        )->withTimestamps();
    }

    /**
     * Creer une ou plusieurs eligibilites avec leurs documents
     */
    public static function createEligibilities(array $data): array
    {
        $country = Country::where('name', $data['country_name'])->firstOrFail();
        $visaType = VisaType::where('name', $data['visa_type_name'])->firstOrFail();

        $statusMatList = is_array($data['status_mat']) ? $data['status_mat'] : [$data['status_mat']];
        $createdIds = [];

        foreach ($statusMatList as $statusMat) {
            $eligibility = self::firstOrCreate(
                [
                    'country_id' => $country->id,
                    'visa_type_id' => $visaType->id,
                    'status_mat' => $statusMat,
                    'min_age' => $data['min_age'],
                    'max_age' => $data['max_age'],
                ],
                [
                    'price_base' => $data['price_base'],
                    'price_per_child' => $data['price_per_child'] ?? 0,
                    'processing_duration_min' => $data['processing_duration_min'],
                    'processing_duration_max' => $data['processing_duration_max'],
                ]
            );

            // Synchroniser les documents
            $documentIds = [];
            foreach ($data['documents'] as $docName) {
                $doc = RequiredDocument::firstOrCreate(['name' => $docName]);
                $documentIds[] = $doc->id;
            }
            $eligibility->requiredDocuments()->sync($documentIds);

            $createdIds[] = $eligibility->id;
        }

        return $createdIds;
    }

    /**
     * Mettre a jour une eligibilite
     */
    public function updateEligibility(array $data): void
    {
        if (isset($data['country_name'])) {
            $this->country_id = Country::where('name', $data['country_name'])->firstOrFail()->id;
        }
        if (isset($data['visa_type_name'])) {
            $this->visa_type_id = VisaType::where('name', $data['visa_type_name'])->firstOrFail()->id;
        }

        $this->fill([
            'price_base' => $data['price_base'] ?? $this->price_base,
            'price_per_child' => $data['price_per_child'] ?? $this->price_per_child,
            'processing_duration_min' => $data['processing_duration_min'] ?? $this->processing_duration_min,
            'processing_duration_max' => $data['processing_duration_max'] ?? $this->processing_duration_max,
            'status_mat' => is_array($data['status_mat'] ?? null) ? ($data['status_mat'][0] ?? $this->status_mat) : ($data['status_mat'] ?? $this->status_mat),
            'min_age' => $data['min_age'] ?? $this->min_age,
            'max_age' => $data['max_age'] ?? $this->max_age,
        ]);

        $this->save();

        if (isset($data['documents'])) {
            $documentIds = [];
            foreach ($data['documents'] as $docName) {
                $doc = RequiredDocument::firstOrCreate(['name' => $docName]);
                $documentIds[] = $doc->id;
            }
            $this->requiredDocuments()->sync($documentIds);
        }
    }
}
