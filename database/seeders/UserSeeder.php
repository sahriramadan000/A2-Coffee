<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $role = Role::whereName('super-admin')->first();

            $user = User::whereUsername('superadmin')->first();
            if (!$user) {
                // Create new user
                $user = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                    'fullname' => 'Super Admin',
                    'username' => 'superadmin',
                    'password' => Hash::make('superadmin')
                ]);
            }

            $permissions = Permission::pluck('id')->all();
            $role->syncPermissions($permissions);
            $user->assignRole([$role->id]);
            echo 'Admin user created' . PHP_EOL;

        } catch (\Throwable $th) {
            // Create message with echo
            echo 'Error : ' . $th->getMessage();
        }
    }
}
