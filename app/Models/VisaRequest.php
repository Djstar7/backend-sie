<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class VisaRequest extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'visa_requests';

    protected $fillable = [
        'user_id',
        'visa_type_id',
        'origin_country_id',
        'destination_country_id',
        'status',
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function visaType()
    {
        return $this->belongsTo(VisaType::class, 'visa_type_id', 'id');
    }
    public function originCountry()
    {
        return $this->belongsTo(Country::class, 'origin_country_id', 'id');
    }
    public function destinationCountry()
    {
        return $this->belongsTo(Country::class, 'destination_country_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'visa_request_id', 'id');
    }
    public function backups()
    {
        return $this->hasMany(Backup::class, 'visa_request_id', 'id');
    }
    public function appoitments()
    {
        return $this->hasMany(Appoitment::class, 'visa_request_id', 'id');
    }
    public function paymments()
    {
        return $this->hasMany(Payment::class, 'visa_request_id', 'id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'visa_request_id', 'id');
    }
}
