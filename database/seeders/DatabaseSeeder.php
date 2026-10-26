<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@distribuidora.test'],
            ['name' => 'Administrador', 'password' => 'cambiar-esta-clave']
        );

        $this->call(DatosEjemploSeeder::class);
    }
}
