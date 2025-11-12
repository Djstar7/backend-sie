<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Backup extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'backups';

    protected $fillable = [
        'visa_request_id',
        'file_path',
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
        return $this->belongsTo(VisaRequest::class, 'visarequest_id', 'id');
    }
}
