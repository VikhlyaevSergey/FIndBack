<?php

namespace App\Models;

use App\Components\Phone as PhoneHelper;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fullName',
        'image',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function (self $user) {
                $user->date = now()->toDateString();
            });
    }

    /**
     * получить профиль
     *
     * @return array
     */
    public function getProfile()
    {
        return [
            'id'                   => $this->id,
            'profileMainBlock'     => $this->makeHidden(['places', 'emails', 'phones']),
            'profileContactsBlock' => [
                'emails'       => $this->emails->pluck('email'),
                'phoneNumbers' => $this->phones->pluck('phone'),
            ],
            'places'               => $this->places,

            'profileFavouriteLossObjects' => $this->relationLoaded('objects') ? $this->objects->pluck('id') :
                $this->objects()->select('objects.id')->get(),

            'profileLossObjects' => $this->relationLoaded('favorite_objects') ? $this->favoriteObjects->pluck('id') :
                $this->favoriteObjects()->select('objects.id')->get(),
        ];
    }

    /**
     * номера телефонов
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    /**
     * e-mails
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    /**
     * места
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function places()
    {
        return $this->hasMany(Place::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objects()
    {
        return $this->hasMany(LossObject::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteObjects()
    {
        return $this->belongsToMany(LossObject::class, 'object_favorite_user', 'user_id', 'object_id');
    }

    /**
     * получить пользователя по телефону
     *
     * @param Builder $query
     * @param string  $phone
     *
     * @return Builder
     */
    public function scopeByPhone(Builder $query, string $phone)
    {
        $phone = PhoneHelper::create($phone);

        return $query->whereHas(
            'phones', function ($subQuery) use ($phone) {
            $subQuery->where('phone', $phone);
        });
    }
}
