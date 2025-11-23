<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'sanctum';

        // Créer le rôle admin s'il n'existe pas
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => $guard,
        ]);

        // Créer ou récupérer l'utilisateur admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('Staeldjune7@'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Assigner le rôle à l'utilisateur (si non déjà assigné)
        if (! $admin->hasRole($adminRole->name)) {
            $admin->assignRole($adminRole);
        }

        // Créer automatiquement les permissions depuis les routes
        foreach (Route::getRoutes() as $route) {
            $routeName = $route->getName();

            if ($routeName) {
                // Créer la permission si elle n'existe pas déjà
                $permission = Permission::firstOrCreate([
                    'name' => $routeName,
                    'guard_name' => $guard,
                ]);

                // Donner la permission au rôle admin et à l'utilisateur admin
                if (! $adminRole->hasPermissionTo($permission)) {
                    $adminRole->givePermissionTo($permission);
                }

                if (! $admin->hasPermissionTo($permission)) {
                    $admin->givePermissionTo($permission);
                }
            }
        }

        $this->command?->info('Admin user created: admin@gmail.com / Staeldjune7@');
    }
}
