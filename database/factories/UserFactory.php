<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Code;
use App\Models\Place;
use App\Models\User;
use App\Components\Phone;
use App\Models\Email;
use Faker\Generator as Faker;
use App\Models\Phone as PhoneModel;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(
    User::class, function (Faker $faker) {
    return [
        'fullName' => $faker->name,
        'image'    => $faker->word,
    ];
});

$factory->define(
    PhoneModel::class, function (Faker $faker) {
    return [
        'phone' => Phone::create($faker->unique()->numberBetween(10000000000, 99999999999)),
        'user_id' => factory(User::class)->create()
    ];
});

$factory->define(
    Email::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'user_id' => factory(User::class)->create()
    ];
});

$factory->define(
    Code::class, function (Faker $faker) {
    return [
        'phone' => Phone::create($faker->numberBetween(10000000000, 99999999999)),
        'code'  => $faker->numberBetween(1000, 9999),
    ];
});

$factory->define(
    Place::class, function (Faker $faker) {
    return [
        'name'      => $faker->word,
        'latitude'  => $faker->latitude,
        'longitude' => $faker->longitude,
        'user_id' => factory(User::class)->create()
    ];
});
