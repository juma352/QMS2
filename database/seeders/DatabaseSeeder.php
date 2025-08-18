<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            ChecklistSeeder::class,
            DynamicChecklistSeeder::class,
            ProgramStructureSeeder::class,
            MedicalSpecialistChecklistSeeder::class,
        ]);
    }
}
