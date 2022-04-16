<?php

namespace Database\Seeders;

use Faker\Generator;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            OrderSeeder::class,
        ]);

        $faker = app()->make(Generator::class);

        // Insert roles
        DB::table('roles')->insert([
            [
                'name' => 'Administrator',
                'code' => 'admin',
                'description' => 'This is administrator role with entire permissions.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Management',
                'code' => 'management',
                'description' => 'This is management role with management permission.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Restaurant',
                'code' => 'restaurant',
                'description' => 'This is restaurant role only for restaurant permission.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Shop',
                'code' => 'shop',
                'description' => 'This is shop role only for shop permission.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        // Assign roles to users
        for ($i=1; $i < 500; $i++) {
            DB::table('role_users')->insert([
                'user_id' => $faker->numberBetween(1, 1000),
                'role_id' =>  $faker->numberBetween(1, 4),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Insert screens
        DB::table('screens')->insert([
            [
                'name' => 'Dashboard',
                'route' => 'dashboard.index',
                'parent' => null,
                'description' => 'This is dashboard screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Orders',
                'route' => 'orders', //orders: [orders.index,orders.create,orders.edit,orders.delete]
                'parent' => null,
                'description' => 'This is orders screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Settings',
                'route' => 'settings.index',
                'parent' => null,
                'description' => 'This is settings screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        // Create actions
        DB::table('actions')->insert([
            [
                'name' => 'Create',
                'code' => 'create',
                'description' => 'This is an action for creating new data.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Read',
                'code' => 'read',
                'description' => 'This is an action for reading data.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Update',
                'code' => 'update',
                'description' => 'This is an action for updating data.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Delete',
                'code' => 'delete',
                'description' => 'This is an action for deleting data.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
