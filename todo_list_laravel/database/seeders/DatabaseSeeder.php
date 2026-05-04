<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tarea; 

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Insertamos los datos iniciales
        Tarea::create(['nombre' => 'Utilizar Ubuntu Server']);
        Tarea::create(['nombre' => 'Desplegar mi aplicación web']);
        Tarea::create(['nombre' => 'Desinstalar Windows']);
    }
}