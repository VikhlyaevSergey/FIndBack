<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Phone as PhoneHelper;

/**
 * App\Models\Phone
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Phone whereUserId($value)
 * @mixin \Eloquent
 */
class Phone extends Model
{
    protected $fillable = ['phone'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (self $phone) {
            $phone->phone = PhoneHelper::create($phone->phone);
        });
    }
}
