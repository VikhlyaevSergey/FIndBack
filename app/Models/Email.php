<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Email
 *
 * @property int $id
 * @property int $user_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Email whereUserId($value)
 * @mixin \Eloquent
 */
class Email extends Model
{
    protected $fillable = ['email'];
}
