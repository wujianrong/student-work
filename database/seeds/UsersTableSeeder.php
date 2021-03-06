<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class   UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'xsc',
                'password' => bcrypt('xsc'),
                'email' => app(Faker\Generator::class)->freeEmail,
                'college_id' => null,
                'picture' => 'images/picture.jpg',//app(Faker\Generator::class)->imageUrl(),
                'gender' => app(Faker\Generator::class)->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'name' => 'jsj',
                'password' => bcrypt('jsj'),
                'email' => app(Faker\Generator::class)->freeEmail,
                'college_id' => 9,
                'picture' => app(Faker\Generator::class)->imageUrl(),
                'gender' => app(Faker\Generator::class)->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], [
                'name' => 'sunlong',
                'password' => bcrypt('sunlong'),
                'email' => app(Faker\Generator::class)->freeEmail,
                'college_id' => 9,
                'picture' => app(Faker\Generator::class)->imageUrl(),
                'gender' => app(Faker\Generator::class)->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]);

    }
}