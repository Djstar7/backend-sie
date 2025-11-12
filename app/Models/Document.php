<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Document extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'documents';

    // Champs remplissables
    protected $fillable = [
        'visa_request_id',
        'name',
        'file_path',
        'is_validated',
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
    function visaRequest()
    {
        return $this->belongsTo(VisaRequest::class, 'visa_request_id', 'id');
    }
}
