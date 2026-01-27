<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['role' => 'Administrador'],
            ['role' => 'Cliente']
        ];

        foreach ($roles as $role) {
            Role::query()->create($role);
        }
    }
}
