<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * La base de datos se inicializa con database/schema_init.sql al levantar
     * Docker por primera vez. No se usan seeders para datos iniciales.
     */
    public function run(): void
    {
        //
    }
}
