<?php

namespace Database\Seeders;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\ChecklistSubmission;
use App\Models\ChecklistProgress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicalSpecialistChecklistSeeder extends Seeder
{
    public function run()
    {
        $checklist = Checklist::updateOrCreate(
            ['slug' => Str::slug('Medical Specialist Training Institution Checklist')],
            [
                'title' => 'Medical Specialist Training Institution Checklist',
                'type' => 'medical_specialist',
                'description' => 'Comprehensive checklist for medical specialist training institutions based on COSECSA standards',
                'is_template' => true
            ]
        );

        // Create checklist items for each step
        $items = [
            // Step 1: Administrative Information
            ['section' => 'Facility Information', 'question_text' => 'Complete facility details and registration information'],
            ['section' => 'Registration and Licensure', 'question_text' => 'Valid registration and operational licenses', 'requires_upload' => true],
            ['section' => 'Physical Location', 'question_text' => 'Complete address and location details'],
            ['section' => 'Contact Information', 'question_text' => 'Complete contact details and personnel information'],
            
            // Step 2: Governance & Management
            ['section' => 'Vision and Mission', 'question_text' => 'Clear vision, mission and philosophy statements'],
            ['section' => 'Governance Structure', 'question_text' => 'Organizational structure and governance framework'],
            ['section' => 'Leadership Team', 'question_text' => 'Qualified leadership and management team'],
            ['section' => 'Committees', 'question_text' => 'Functional committees and governance bodies'],
            
            // Step 3: Academic Programme
            ['section' => 'Curriculum', 'question_text' => 'Approved and comprehensive curriculum'],
            ['section' => 'Admission Requirements', 'question_text' => 'Clear admission criteria and selection process'],
            ['section' => 'Program Duration', 'question_text' => 'Appropriate program duration and structure'],
            ['section' => 'Apprentice Training', 'question_text' => 'Apprentice-based training with clinical exposure'],
            
            // Step 4: Physical Infrastructure
            ['section' => 'Administrative Offices', 'question_text' => 'Adequate administrative and support facilities'],
            ['section' => 'Teaching Facilities', 'question_text' => 'Well-equipped teaching and tutorial rooms'],
            ['section' => 'Laboratories', 'question_text' => 'Equipped technical and skills laboratories'],
            ['section' => 'Learning Resources', 'question_text' => 'Comprehensive learning resource center'],
            
            // Step 5: Faculty/Trainers
            ['section' => 'Qualified Faculty', 'question_text' => 'Qualified and experienced faculty members'],
            ['section' => 'Staff Ratios', 'question_text' => 'Appropriate staff-to-student ratios'],
            ['section' => 'Registration & Licensure', 'question_text' => 'Valid registration and licensure of staff', 'requires_upload' => true],
            ['section' => 'Staff Development', 'question_text' => 'Continuous professional development programs'],

            // Step 6: Student Welfare & Support
            ['section' => 'Student Support', 'question_text' => 'Comprehensive student support services'],
            ['section' => 'Mentorship Program', 'question_text' => 'Structured mentorship and academic support'],
            ['section' => 'Student Policies', 'question_text' => 'Clear student welfare and support policies'],
            ['section' => 'Accommodation', 'question_text' => 'Adequate accommodation and facilities'],
            
            // Step 7: Programme Monitoring & Evaluation
            ['section' => 'Quality Assurance', 'question_text' => 'Quality assurance and monitoring systems', 'requires_upload' => true],
            ['section' => 'Curriculum Review', 'question_text' => 'Regular curriculum review and updates'],
            ['section' => 'Feedback Mechanisms', 'question_text' => 'Effective feedback and evaluation systems'],
            ['section' => 'Continuous Improvement', 'question_text' => 'Continuous improvement processes'],
            
            // Step 8: Research & Innovation
            ['section' => 'Research Policy', 'question_text' => 'Research policy and guidelines', 'requires_upload' => true],
            ['section' => 'Research Funding', 'question_text' => 'Adequate research funding and support'],
            ['section' => 'Research Outputs', 'question_text' => 'Research outputs and documentation'],
            ['section' => 'Innovation Support', 'question_text' => 'Innovation and development support']
        ];

        foreach ($items as $item) {
            $item['checklist_id'] = $checklist->id;
            ChecklistItem::updateOrCreate(
                [
                    'checklist_id' => $checklist->id,
                    'section' => $item['section'],
                    'question_text' => $item['question_text']
                ],
                $item
            );
        }

        $adminUser = User::where('email', 'admin@example.com')->first();

        if ($adminUser) {
            $submission = ChecklistSubmission::updateOrCreate(
                [
                    'checklist_id' => $checklist->id,
                    'user_id' => $adminUser->id,
                ],
                [
                    'status' => 'draft',
                    'department_name' => 'Medical Specialist Training'
                ]
            );

            ChecklistProgress::updateOrCreate(
                ['checklist_submission_id' => $submission->id],
                [
                    'current_step' => 1,
                    'completed_steps' => [],
                    'is_draft' => true
                ]
            );
        }
    }
}