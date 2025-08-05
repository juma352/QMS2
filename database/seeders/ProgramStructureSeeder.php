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
        Program::create(['school_id' => $nursing->id, 'program_name' => 'KRCHN']);
        Program::create(['school_id' => $nursing->id, 'program_name' => 'CCN']);
        // ... add other Nursing programs

        $clinical = School::create(['subdivision_id' => $kchs->id, 'name' => 'Clinical']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'KCMS']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'PECCCO']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'ECCCO']);
        Program::create(['school_id' => $clinical->id, 'program_name' => 'FHCO']);
        // ... add other Clinical programs

        $allied = School::create(['subdivision_id' => $kchs->id, 'name' => 'Allied Health']);
        Program::create(['school_id' => $allied->id, 'program_name' => 'POTT']);
        Program::create(['school_id' => $allied->id, 'program_name' => 'POTOT']);

        // GME Categories & Programs
        $internship = School::create(['subdivision_id' => $gme->id, 'name' => 'Internship']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'MOI']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'COI']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'NOI']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'Dental Health']);
        Program::create(['school_id' => $internship->id, 'program_name' => 'PGO']);

        // ... add other Internship programs
        $residency = School::create(['subdivision_id' => $gme->id, 'name' => 'Residency']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Gen Surgery']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Pediatrics ']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Obstetrics and Gynecology']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Anaesthisia']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Family Medicine']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Plastics']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Urology']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Neuro Surgery']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Paeds Surgery']);
        Program::create(['school_id' => $residency->id, 'program_name' => 'Orthopedics']);
        // ... add other Residency programs

        $shortTerm = School::create(['subdivision_id' => $gme->id, 'name' => 'Short term']);
        Program::create(['school_id' => $shortTerm->id, 'program_name' => 'Resident']);
        Program::create(['school_id' => $shortTerm->id, 'program_name' => 'Students']);

        // CPD Categories & Programs
        $shortCourses = School::create(['subdivision_id' => $cpd->id, 'name' => 'Short Courses']);
        $elearning = School::create(['subdivision_id' => $cpd->id, 'name' => 'E-Learning']);
        $simulation = School::create(['subdivision_id' => $cpd->id, 'name' => 'Simulation']);
        Program::create(['school_id' => $shortCourses->id, 'program_name' => 'AHA']);
        Program::create(['school_id' => $shortCourses->id, 'program_name' => 'TB']);
        Program::create(['school_id' => $shortCourses->id, 'program_name' => 'HIV']);
        Program::create(['school_id'=> $elearning->id, 'program_name' => 'E-Learning']);
        Program::create(['school_id' => $simulation->id, 'program_name' => 'Simulation']);
        // ... add other CPD programs
    }
}
