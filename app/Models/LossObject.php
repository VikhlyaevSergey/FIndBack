<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LossObject
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\LossObject whereUserId($value)
 * @mixin \Eloquent
 */
class LossObject extends Model
{
    protected $table = 'objects';
}
