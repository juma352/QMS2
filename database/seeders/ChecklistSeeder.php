<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\Support\Facades\DB;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear old data from these tables to prevent duplicates when re-seeding
        DB::table('checklist_items')->delete();
        DB::table('checklists')->delete();

        $checklistsData = [
            'accreditation-compliance' => [
                'title' => 'Accreditation Compliance Checklist',
                'items' => [
                    'Accreditation Status' => ['Verify Current Accreditation Status', 'Review Accreditation Renewal Timelines'],
                    'Standards Met' => ['Are the programmatic standards met?', 'Check compliance with regional and national accreditation criteria'],
                    'Documentation' => ['Collect all relevant accreditation documents', 'Review any previous accreditation reports and recommendations'],
                ],
            ],
            'curriculum-review' => [
                'title' => 'Curriculum Review Checklist',
                'items' => [
                    'Program Objectives' => ['Are program objectives clearly defined?', 'Do objectives align with industry standards?'],
                    'Course Content' => ['Is the curriculum up-to-date with current healthcare practices?', 'Are core competencies and established?'],
                    'Evaluation Methods' => ['Are assessment methods effective and varied?', 'Do they measure student learning outcomes appropriately?'],
                ],
            ],
            'faculty-qualifications' => [
                'title' => 'Faculty Qualifications Checklist',
                'items' => [
                    'Credentials' => ['Verify educational qualifications of faculty members.', 'Check for relevant certifications and licenses.'],
                    'Professional Development' => ['Are faculty members engaged in ongoing professional development?', 'Document any teaching evaluations or peer reviews'],
                    'Workload' => ['Assess faculty workload in relation to teaching, research, and service'],
                ],
            ],
            'students-outcome' => [
                'title' => 'Students Outcome Checklist',
                'items' => [
                    'Graduation Rates' => ['Analyze graduation rates over the past few years.', 'Compare with national averages'],
                    'Licensure Exam Pass Rates' => ['Review pass rates for relevant licensure exams', 'Investigate trends and areas for improvement'],
                    'Employment rates' => ['Track employment rates of graduates within their field', 'Gather feedback from alumni on job preparedness'],
                ],
            ],
            'policies-and-procedures' => [
                'title' => 'Policies and Procedures Checklist',
                'items' => [
                    'Governance' => ['Review institutional policies regarding governance and decision-making.', 'Ensure policies are up-to-date and accessible'],
                    'Student Policies' => ['Verify student handbook policies on academic integrity, attendance, and grading', 'Check for clarity and fairness in disciplinary procedures'],
                    'Emergency Preparedness' => ['Assess policies related to safety and emergency response protocols', 'Ensure regular drills and training are conducted'],
                ],
            ],
            'financial-management' => [
                'title' => 'Financial Management Checklist',
                'items' => [
                    'Budget Review' => ['Examine the budget allocation for healthcare programs.', 'Assess any financial discrepancies or concerns'],
                    'Tuition and Fees' => ['Review the tuition structure and any changes over recent years', 'Ensure transparency in fee assessments'],
                    'Funding and grants for healthcare programs' => ['Document any grants for healthcare programs or funding received for healthcare programs', 'Review compliance with grant requirements'],
                ],
            ],
            
            // START: ADD YOUR NEW CHECKLISTS HERE
            
            'clinical-experience' => [
                'title' => 'Clinical Experience Checklist',
                'items' => [
                    // Replace these with your actual sections and questions
                    'Clinical Partnerships' => [
                        'Evaluate the quality and suitability of clinical partnership sites.',
                        'Review affiliation agreements with clinical sites.',
                    ],
                    'Student Supervision' => [
                        'Assess the adequacy and quality of student supervision.',
                        'Verify supervisor credentials and training.',
                    ],
                ],
            ],

            'exam-process' => [
                'title' => 'Exam Process Checklist',
                'items' => [
                    // Replace these with your actual sections and questions
                    'Exam Development' => [
                        'Review the process for creating and validating exam questions.',
                        'Ensure exams align with curriculum objectives.',
                    ],
                    'Exam Administration' => [
                        'Assess the security and integrity of the exam administration process.',
                        'Evaluate the procedure for handling exam-related issues or appeals.',
                    ],
                ],
            ],

            // END: ADD YOUR NEW CHECKLISTS HERE
        ];

        foreach ($checklistsData as $slug => $data) {
            $checklist = Checklist::create(['slug' => $slug, 'title' => $data['title']]);
            $order = 1;
            foreach ($data['items'] as $section => $questions) {
                foreach ($questions as $question) {
                    ChecklistItem::create([
                        'checklist_id' => $checklist->id,
                        'section' => $section,
                        'question_text' => $question,
                        'display_order' => $order++,
                    ]);
                }
            }
        }
    }
}