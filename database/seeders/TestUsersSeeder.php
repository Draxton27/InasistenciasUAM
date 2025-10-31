<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profesor;
use Illuminate\Database\Seeder;

class TestUsersSeeder extends Seeder
{
    /**
     * Seed test users: admins and profesores.
     */
    public function run(): void
    {
        // Admins
        $admins = [
            [
                'name' => 'Admin Demo',
                'email' => 'admin@example.com',
                'password' => 'password',
            ],
        ];

        foreach ($admins as $admin) {
            $user = User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => $admin['password'], // cast to hashed in model
                    'role' => 'admin',
                ]
            );
        }

        // Profesores (users + profesores table entries)
        $profesores = [
            [
                'name' => 'Ana Pérez',
                'email' => 'ana.perez@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Luis Gómez',
                'email' => 'luis.gomez@example.com',
                'password' => 'password',
            ],
        ];

        foreach ($profesores as $prof) {
            $user = User::updateOrCreate(
                ['email' => $prof['email']],
                [
                    'name' => $prof['name'],
                    'password' => $prof['password'], // cast to hashed in model
                    'role' => 'profesor',
                ]
            );

            Profesor::updateOrCreate(
                ['email' => $prof['email']],
                [
                    'user_id' => $user->id,
                    'nombre' => $prof['name'],
                    'foto' => null,
                ]
            );
        }
    }
}
