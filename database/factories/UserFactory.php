<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            // 1. Sélection ou création d’un rôle aléatoire
            $role = Role::inRandomOrder()->first() ??
                Role::firstOrCreate([
                    'name' => 'custom',
                    'guard_name' => 'sanctum',
                ]);

            // 2. Attribution du rôle à l’utilisateur
            $user->assignRole($role);

            // 3. Création automatique des permissions basées sur les routes nommées
            foreach (Route::getRoutes() as $route) {
                $routeName = $route->getName();

                if ($routeName) {
                    $permission = Permission::firstOrCreate([
                        'name' => $routeName,
                        'guard_name' => 'sanctum',
                    ]);

                    // Donner la permission au rôle et à l’utilisateur
                    $role->givePermissionTo($permission);
                    $user->givePermissionTo($permission);
                }
            }
        });
    }
}
