<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');
        $customers = [];

        for ($i=0; $i < 50; $i++) {
            $customers[] = [
                'name' => $faker->name,
                'birthday' => $faker->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
                'email' => $faker->unique()->safeEmail,
                'cellphone' => $faker->phoneNumber,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('customers')->insert($customers);
    }
}
