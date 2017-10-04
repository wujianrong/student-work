<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password = 'admin';
    static $college_id = 1;

    $college_id <= 15 ?: $college_id = 1;

    return [
        'name' => $faker->unique()->userName,
        'email' => $faker->unique()->freeEmail,
        'password' => bcrypt($password),
        'college_id' => $college_id++,
        'picture' => $faker->imageUrl(),//$faker->image(public_path('uploads'), 480, 480, 'people', false),
        'gender' => $faker->boolean,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3),
        'remember_token' => null,
    ];
});

$factory->define(App\Models\Task::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'detail' =>$faker->text(500),
        'work_type_id' =>  random_int(1,4),
        'department_id' => random_int(1,5),
        'end_time' => Carbon::tomorrow()->addDays(10),
        'status' => 'draft',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()->addDays(3)
    ];
});
