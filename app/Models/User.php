<?php

namespace App\Models;

use App\Components\Phone as PhoneHelper;
use App\Http\Resources\PlaceResource;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @property int                                                                                                            $id
 * @property string                                                                                                         $fullName
 * @property string|null                                                                                                    $image
 * @property string                                                                                                         $date
 * @property \Illuminate\Support\Carbon|null                                                                                $created_at
 * @property \Illuminate\Support\Carbon|null                                                                                $updated_at
 * @property \Illuminate\Support\Carbon|null                                                                                $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[]                                       $clients
 * @property-read int|null                                                                                                  $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Email[]                                              $emails
 * @property-read int|null                                                                                                  $emails_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LossObject[]                                         $favoriteObjects
 * @property-read int|null                                                                                                  $favorite_objects_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null                                                                                                  $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LossObject[]                                         $objects
 * @property-read int|null                                                                                                  $objects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Phone[]                                              $phones
 * @property-read int|null                                                                                                  $phones_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Place[]                                              $places
 * @property-read int|null                                                                                                  $places_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[]                                        $tokens
 * @property-read int|null                                                                                                  $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User byPhone($phone)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User withoutTrashed()
 * @mixin \Eloquent
 * @property string|null                                                                                                    $last_login
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastLogin($value)
 */
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
            'places'               => PlaceResource::collection($this->places),

            'profileFavouriteLossObjects' => $this->relationLoaded('favorite_objects') ? $this->objects->pluck('id') :
                $this->objects()->select('objects.id')->get(),

            'profileLossObjects' => $this->relationLoaded('objects') ? $this->favoriteObjects->pluck('id') :
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
