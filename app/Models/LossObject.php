<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LossObject
 *
 * @property int                             $id
 * @property int                             $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereUserId($value)
 * @mixin \Eloquent
 * @property string                          $name
 * @property string                          $description
 * @property string|null                     $type
 * @property string|null                     $pet_type
 * @property string|null                     $regime
 * @property string                          $date_of_losing
 * @property string|null                     $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereDateOfLosing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject wherePetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereRegime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereType($value)
 */
class LossObject extends Model
{
    protected $table = 'objects';

    protected $fillable = [
        'name',
        'description',
        'type',
        'pet_type',
        'regime',
        'date_of_losing',
        'image',
    ];
}
