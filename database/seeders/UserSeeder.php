<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create new users
        \App\Models\User::factory(1000)->create();

        $faker = app()->make(Generator::class);

        // Default users
        DB::table('users')->insert([
            [
                'name' => 'Admin 1',
                'email' => 'phich82@gmail.com',
                'email_verified_at' => now(),
                'password' =>  Hash::make('12345678'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Admin 2',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' =>  Hash::make('12345678'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@gmail.com',
                'email_verified_at' => now(),
                'password' =>  Hash::make('12345678'),
                'remember_token' => Str::random(10),
            ],
        ]);
    }
}
