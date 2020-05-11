<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LossObject;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(
    LossObject::class, function (Faker $faker) {
    return [
        'name'           => $faker->word,
        'description'    => $faker->text,
        'date_of_losing' => $faker->date(),
        'user_id' => factory(User::class)
    ];
});
