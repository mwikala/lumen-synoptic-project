<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Generate 10 User's with an attached card
        for ($i = 0; $i < 10; $i++) {
            $user = \App\User::create([
                'employee_id' => Str::random(16),
                'name' =>  $faker->name,
                'email' => $faker->email,
                'mobile_num' => $faker->phoneNumber,
                'pin' => rand(pow(10, 3), pow(10, 4) - 1)
            ]);

            \App\Card::create([
                'user_id' => $user->id,
                'card_id' => Str::random(16)
            ]);
        }
    }
}
