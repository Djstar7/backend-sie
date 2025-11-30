<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Str;

class Message extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $table = 'messages';

    protected $fillable = [
        'user_id',
        'visa_request_id',
        'content',
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

    public function markAsRead(): void
    {
        if ($this->status !== 'read') {
            $this->update(['status' => 'read']);
        }
    }

    function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    function visaRequest()
    {
        return $this->belongsTo(VisaRequest::class, 'visa_request_id', 'id');
    }
}
