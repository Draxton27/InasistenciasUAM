<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // You can still use factories for bulk data if needed
        // User::factory(10)->create();

        // Seed a default test user (optional)
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Run our explicit test users seeder (admins & profesores)
        $this->call([
            TestUsersSeeder::class,
        ]);
    }
}
