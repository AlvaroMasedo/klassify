<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Course ;

class SubjectSeeder extends Seeder

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $primaria = Course::where('name', 'Primaria')->first();
        Subject::insert([
            ['name' => 'Matemáticas', 'course_id' => $primaria->id],
            ['name' => 'Lengua', 'course_id' => $primaria->id],
            ['name' => 'Inglés', 'course_id' => $primaria->id],
            ['name' => 'Ciencias Naturales', 'course_id' => $primaria->id],
            ['name' => 'Ciencias Sociales', 'course_id' => $primaria->id],
        ]);

        $secundaria = Course::where('name', 'Secundaria')->first();
        Subject::insert([
            ['name' => 'Matemáticas', 'course_id' => $secundaria->id],
            ['name' => 'Lengua', 'course_id' => $secundaria->id],
            ['name' => 'Inglés', 'course_id' => $secundaria->id],
            ['name' => 'Física y Química', 'course_id' => $secundaria->id],
            ['name' => 'Biología', 'course_id' => $secundaria->id],
            ['name' => 'Historia', 'course_id' => $secundaria->id],
            ['name' => 'Geografía', 'course_id' => $secundaria->id],
        ]);

        $bachCiencias = Course::where('name', 'Bachillerato Ciencias')->first();
        Subject::insert([
            ['name' => 'Matemáticas II', 'course_id' => $bachCiencias->id],
            ['name' => 'Física', 'course_id' => $bachCiencias->id],
            ['name' => 'Química', 'course_id' => $bachCiencias->id],
            ['name' => 'Biología', 'course_id' => $bachCiencias->id],
        ]);

        $bachHumanidades = Course::where('name', 'Bachillerato Humanidades')->first();
        Subject::insert([
            ['name' => 'Historia', 'course_id' => $bachHumanidades->id],
            ['name' => 'Literatura', 'course_id' => $bachHumanidades->id],
            ['name' => 'Filosofía', 'course_id' => $bachHumanidades->id],
            ['name' => 'Geografía', 'course_id' => $bachHumanidades->id],
        ]);

        $bachSociales = Course::where('name', 'Bachillerato Sociales')->first();
        Subject::insert([
            ['name' => 'Economía', 'course_id' => $bachSociales->id],
            ['name' => 'Historia', 'course_id' => $bachSociales->id],
            ['name' => 'Geografía', 'course_id' => $bachSociales->id],
            ['name' => 'Matemáticas', 'course_id' => $bachSociales->id],
        ]);

        $cfgmInformatica = Course::where('name', 'CFGM Informática')->first();
        Subject::insert([
            ['name' => 'Sistemas Informáticos', 'course_id' => $cfgmInformatica->id],
            ['name' => 'Bases de Datos', 'course_id' => $cfgmInformatica->id],
            ['name' => 'Programación', 'course_id' => $cfgmInformatica->id],
        ]);

        $cfgmAdmin = Course::where('name', 'CFGM Administración')->first();
        Subject::insert([
            ['name' => 'Gestión Administrativa', 'course_id' => $cfgmAdmin->id],
            ['name' => 'Contabilidad', 'course_id' => $cfgmAdmin->id],
            ['name' => 'Fiscalidad', 'course_id' => $cfgmAdmin->id],
        ]);

        $cfgsDaw = Course::where('name', 'CFGS DAW')->first();
        Subject::insert([
            ['name' => 'Programación', 'course_id' => $cfgsDaw->id],
            ['name' => 'Lenguajes de Marcas', 'course_id' => $cfgsDaw->id],
            ['name' => 'Acceso a Datos', 'course_id' => $cfgsDaw->id],
            ['name' => 'Desarrollo Web', 'course_id' => $cfgsDaw->id],
        ]);

        $cfgsAsir = Course::where('name', 'CFGS ASIR')->first();
        Subject::insert([
            ['name' => 'Administración Sistemas', 'course_id' => $cfgsAsir->id],
            ['name' => 'Servicios en Red', 'course_id' => $cfgsAsir->id],
            ['name' => 'Seguridad Informática', 'course_id' => $cfgsAsir->id],
        ]);

        $cfgsDam = Course::where('name', 'CFGS DAM')->first();
        Subject::insert([
            ['name' => 'Programación', 'course_id' => $cfgsDam->id],
            ['name' => 'Acceso a Datos', 'course_id' => $cfgsDam->id],
            ['name' => 'Interfaces', 'course_id' => $cfgsDam->id],
        ]);

        $ingInformatica = Course::where('name', 'Ingeniería Informática')->first();
        Subject::insert([
            ['name' => 'Programación', 'course_id' => $ingInformatica->id],
            ['name' => 'Algoritmos', 'course_id' => $ingInformatica->id],
            ['name' => 'Bases de Datos', 'course_id' => $ingInformatica->id],
        ]);

        $ingS = Course::where('name', 'Ingeniería de Sistemas')->first();
        Subject::insert([
            ['name' => 'Programación Sistemas', 'course_id' => $ingS->id],
            ['name' => 'Redes', 'course_id' => $ingS->id],
            ['name' => 'Seguridad', 'course_id' => $ingS->id],
        ]);

        $admin = Course::where('name', 'Administración de Empresas')->first();
        Subject::insert([
            ['name' => 'Contabilidad', 'course_id' => $admin->id],
            ['name' => 'Marketing', 'course_id' => $admin->id],
            ['name' => 'Gestión Empresarial', 'course_id' => $admin->id],
        ]);

        $derecho = Course::where('name', 'Derecho')->first();
        Subject::insert([
            ['name' => 'Derecho Civil', 'course_id' => $derecho->id],
            ['name' => 'Derecho Penal', 'course_id' => $derecho->id],
            ['name' => 'Derecho Laboral', 'course_id' => $derecho->id],
        ]);

        $medicina = Course::where('name', 'Medicina')->first();
        Subject::insert([
            ['name' => 'Anatomía', 'course_id' => $medicina->id],
            ['name' => 'Fisiología', 'course_id' => $medicina->id],
            ['name' => 'Farmacología', 'course_id' => $medicina->id],
        ]);

        $enfermeria = Course::where('name', 'Enfermería')->first();
        Subject::insert([
            ['name' => 'Fundamentos Enfermería', 'course_id' => $enfermeria->id],
            ['name' => 'Fisiología', 'course_id' => $enfermeria->id],
            ['name' => 'Salud Pública', 'course_id' => $enfermeria->id],
        ]);

        $psicologia = Course::where('name', 'Psicología')->first();
        Subject::insert([
            ['name' => 'Psicología General', 'course_id' => $psicologia->id],
            ['name' => 'Psicología Social', 'course_id' => $psicologia->id],
            ['name' => 'Neuropsicología', 'course_id' => $psicologia->id],
        ]);

        $educacion = Course::where('name', 'Educación')->first();
        Subject::insert([
            ['name' => 'Didáctica General', 'course_id' => $educacion->id],
            ['name' => 'Psicología Educativa', 'course_id' => $educacion->id],
            ['name' => 'Orientación Educativa', 'course_id' => $educacion->id],
        ]);

        $arquitectura = Course::where('name', 'Arquitectura')->first();
        Subject::insert([
            ['name' => 'Teoría Arquitectura', 'course_id' => $arquitectura->id],
            ['name' => 'Proyectos', 'course_id' => $arquitectura->id],
            ['name' => 'Construcción', 'course_id' => $arquitectura->id],
        ]);

        $biotecnologia = Course::where('name', 'Biotecnología')->first();
        Subject::insert([
            ['name' => 'Biología Molecular', 'course_id' => $biotecnologia->id],
            ['name' => 'Genética', 'course_id' => $biotecnologia->id],
            ['name' => 'Bioinformática', 'course_id' => $biotecnologia->id],
        ]);
    }
}
