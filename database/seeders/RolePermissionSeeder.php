<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Disperindag',
            'Pangkalan LPG',
            'Agen LPG',
            'Pengawas',
            'Publik'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
            
            $username = strtolower(str_replace(' ', '', $roleName));
            
            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $roleName,
                    'password' => Hash::make('password') // Default password
                ]
            );
            
            $user->assignRole($roleName);
        }
    }
}
