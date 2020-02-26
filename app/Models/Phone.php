<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Components\Phone as PhoneHelper;

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
