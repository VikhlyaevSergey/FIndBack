<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Code
 *
 * @property int                             $id
 * @property string                          $phone
 * @property int                             $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Code whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Code extends Model
{
    protected $fillable = ['phone', 'code'];
}
