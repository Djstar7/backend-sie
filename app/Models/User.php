<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,  HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image'
    ];
    protected $guard_name = 'sanctum';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Assigne automatique le role spatie a un utilsateur après sa création.
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            if ($user->role) {
                $user->assignRole($user->role);
            }
        });
    }


    function profil()
    {
        return $this->hasOne(Profil::class, 'user_id', 'id');
    }
    function logs()
    {
        return $this->hasMany(Log::class, 'user_id', 'id');
    }
    function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }
    function visaRequests()
    {
        return $this->hasMany(VisaRequest::class, 'user_id', 'id');
    }

    function avis()
    {
        return $this->hasMany(Avis::class, 'user_id', 'id');
    }
}
