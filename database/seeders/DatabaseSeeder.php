<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'test',
            'password' => bcrypt('testtest'),
            'email' => 'test@example.com',
            'cuit' => '20-12345678-9',
            'direccion' => 'Calle Falsa 123',
            'provincia' => 'Buenos Aires',
            'localidad' => 'La Plata',
            'telefono' => '01123456789',
            'lista' => 0,
        ]);

        Admin::factory()->create([
            'name' => 'pablo',
            'password' => bcrypt('pablopablo'),
        ]);
    }
}
