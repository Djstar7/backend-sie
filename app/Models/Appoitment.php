<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Appoitment extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = "appoitments";
    protected $fillable = [
        'visa_request_id',
        'scheduled_at',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
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
    public function visaRequest()
    {
        return $this->belongsTo(VisaRequest::class, 'visa_request_id', 'id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'appoitment_id', 'id');
    }
}
