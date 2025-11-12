<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Payment extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = "payments";

    protected $fillable = [
        'visa_request_id',
        'amount',
        'transaction_id',
        'method',
        'currency',
        'status',
        'meta'
    ];
    protected $casts = [
        'meta' => 'array'
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
    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'payment_id', 'id');
    }
}
