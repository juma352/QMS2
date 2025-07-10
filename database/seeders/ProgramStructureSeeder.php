<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subdivision;
use App\Models\School;
use App\Models\Program;

class ProgramStructureSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid duplicates
        Program::query()->delete();
        School::query()->delete();
        Subdivision::query()->delete();

        // Subdivisions
        $kchs = Subdivision::create(['name' => 'KCHS']);
        $gme = Subdivision::create(['name' => 'GME']);
        $cpd = Subdivision::create(['name' => 'CPD']);
        $research = Subdivision::create(['name' => 'RESEARCH']);

        // KCHS Schools & Programs
        $nursing = School::create(['subdivision_id' => $kchs->id, 'name' => 'Nursing']);
        Program::create(['school_id' => $nursing->id, 'program_name' => 'KRNA']);
        Program::create(['school_id' => $nursing->id, 'program_name' => 'KRPON']);
        // ... add other Nursing programs

        $clinical = School::create(['subdivision_id' => $kchs->id, 'name' => 'Clinical']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'KCMS']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'PECCCO']);
        // ... add other Clinical programs

        $allied = School::create(['subdivision_id' => $kchs->id, 'name' => 'Allied Health']);
        Program::create(['school_id' => $allied->id, 'program_name' => 'POTT']);
        Program::create(['school_id' => $allied->id, 'program_name' => 'POTOT']);

        // GME Categories & Programs
        $internship = School::create(['subdivision_id' => $gme->id, 'name' => 'Internship']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'LMOI']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'LCOI']);
        // ... add other Internship programs

        $shortTerm = School::create(['subdivision_id' => $gme->id, 'name' => 'Short term']);
        Program::create(['school_id' => $shortTerm->id, 'program_name' => 'Resident']);
        Program::create(['school_id' => $shortTerm->id, 'program_name' => 'Students']);

        // CPD Categories & Programs
        $shortCourses = School::create(['subdivision_id' => $cpd->id, 'name' => 'Short Courses']);
        Program::create(['school_id' => $shortCourses->id, 'program_name' => 'AHA']);
        Program::create(['school_id' => $shortCourses->id, 'program_name' => 'TB']);
        // ... add other CPD programs
    }
}
