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
                'permissions' => null,
            ],
            [
                'name' => 'Management',
                'code' => 'management',
                'description' => 'This is management role with management permission.',
                'created_at' => date('Y-m-d H:i:s'),
                'permissions' => json_encode([
                    'screens' => [
                        'home' => ['create', 'read', 'update'],
                        'settings.show' => ['read'],
                    ],
                    'actions' => ['create', 'read', 'update', 'delete'],
                ])
            ],
            [
                'name' => 'Restaurant',
                'code' => 'restaurant',
                'description' => 'This is restaurant role only for restaurant permission.',
                'created_at' => date('Y-m-d H:i:s'),
                'permissions' => json_encode([
                    'screens' => [
                        'home' => ['read'],
                        'settings.*' => ['read', 'create', 'update'],
                    ],
                    'actions' => ['create', 'read', 'update'],
                ])
            ],
            [
                'name' => 'Shop',
                'code' => 'shop',
                'description' => 'This is shop role only for shop permission.',
                'created_at' => date('Y-m-d H:i:s'),
                'permissions' => json_encode([
                    'screens' => [
                        'home' => ['create', 'read'],
                        'settings.index' => ['read'],
                        'settings.show' => ['read'],
                        'settings.update' => ['read'],
                        'settings.destroy' => ['read', 'delete'],
                    ],
                    'actions' => ['create', 'read', 'update'],
                ])
            ],
        ]);

        // Assign roles to users
        for ($i=0; $i < 500; $i++) {
            $mapping = [
                497 => [1001, 1],
                498 => [1002, 1],
                499 => [1003, 2],
            ];
            DB::table('role_users')->insert([
                'user_id' => !isset($mapping[$i]) ? $faker->numberBetween(1, 1000) : $mapping[$i][0],
                'role_id' => !isset($mapping[$i]) ? $faker->numberBetween(1, 4) : $mapping[$i][1],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // Insert screens
        DB::table('screens')->insert([
            [
                'name' => 'Home',
                'route' => 'home',
                'parent' => null,
                'description' => 'This is home screen.',
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
                'name' => 'Setting List',
                'route' => 'settings.index',
                'parent' => null,
                'description' => 'This is settings screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Setting Detail',
                'route' => 'settings.show',
                'parent' => null,
                'description' => 'This is settings screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Setting Edit',
                'route' => 'settings.update',
                'parent' => null,
                'description' => 'This is settings screen.',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Setting Delete',
                'route' => 'settings.destroy',
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
