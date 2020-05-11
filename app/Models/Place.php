<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Place
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Place whereUserId($value)
 * @mixin \Eloquent
 */
class Place extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude'];
    
}
