<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkersSeeder extends Seeder
{
    public function run()
    {
        DB::table('projects')->insert([
            ['name' => 'Transformación Digital', 'description' => 'Modernización de procesos internos', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Portal Cliente', 'description' => 'Plataforma web para clientes externos', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ERP Interno', 'description' => 'Implementación ERP corporativo', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'App Móvil', 'description' => 'Aplicación móvil iOS/Android', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('employees')->insert([
            ['name' => 'Ana Torres', 'email' => 'ana.torres@empresa.com', 'position' => 'Desarrolladora Senior', 'phone' => '999111001', 'project_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bruno Quispe', 'email' => 'bruno.quispe@empresa.com', 'position' => 'Analista de Datos', 'phone' => '999111002', 'project_id' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Carmen Leal', 'email' => 'carmen.leal@empresa.com', 'position' => 'Diseñadora UX', 'phone' => '999111003', 'project_id' => 1, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diego Flores', 'email' => 'diego.flores@empresa.com', 'position' => 'Scrum Master', 'phone' => '999111004', 'project_id' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Elena Pacheco', 'email' => 'elena.pacheco@empresa.com', 'position' => 'Backend Developer', 'phone' => '999111005', 'project_id' => 2, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fernando Ríos', 'email' => 'fernando.rios@empresa.com', 'position' => 'QA Engineer', 'phone' => '999111006', 'project_id' => 4, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gabriela Soto', 'email' => 'gabriela.soto@empresa.com', 'position' => 'Project Manager', 'phone' => '999111007', 'project_id' => 3, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hugo Mendoza', 'email' => 'hugo.mendoza@empresa.com', 'position' => 'DevOps Engineer', 'phone' => '999111008', 'project_id' => 1, 'status' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('contracts')->insert([
            ['employee_id' => 1, 'project_id' => 1, 'start_date' => '2024-01-15', 'end_date' => '2025-12-31', 'salary' => 5500.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 2, 'project_id' => 2, 'start_date' => '2024-03-01', 'end_date' => null,         'salary' => 4800.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 3, 'project_id' => 1, 'start_date' => '2024-02-10', 'end_date' => '2025-08-31', 'salary' => 4200.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 4, 'project_id' => 3, 'start_date' => '2023-11-01', 'end_date' => '2025-06-30', 'salary' => 5000.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 5, 'project_id' => 2, 'start_date' => '2024-04-15', 'end_date' => null,         'salary' => 5200.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 6, 'project_id' => 4, 'start_date' => '2024-06-01', 'end_date' => '2025-11-30', 'salary' => 4500.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 7, 'project_id' => 3, 'start_date' => '2023-09-01', 'end_date' => null,         'salary' => 6000.00, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['employee_id' => 8, 'project_id' => 1, 'start_date' => '2023-05-01', 'end_date' => '2024-04-30', 'salary' => 4700.00, 'status' => 'finished', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
