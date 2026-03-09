<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::insert([
            ['name' => 'Primaria'],
            ['name' => 'Secundaria'],
            ['name' => 'Bachillerato Ciencias'],
            ['name' => 'Bachillerato Humanidades'],
            ['name' => 'Bachillerato Sociales'],
            ['name' => 'CFGM Informática'],
            ['name' => 'CFGM Administración'],
            ['name' => 'CFGS DAW'],
            ['name' => 'CFGS ASIR'],
            ['name' => 'CFGS DAM'],
            ['name' => 'Ingeniería Informática'],
            ['name' => 'Ingeniería de Sistemas'],
            ['name' => 'Administración de Empresas'],
            ['name' => 'Derecho'],
            ['name' => 'Medicina'],
            ['name' => 'Enfermería'],
            ['name' => 'Psicología'],
            ['name' => 'Educación'],
            ['name' => 'Arquitectura'],
            ['name' => 'Biotecnología'],
        ]);
    }
}
