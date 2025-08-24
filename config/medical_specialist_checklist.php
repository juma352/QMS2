<?php

return [
    'questions' => [
        1 => [
            ['id' => 'facility_name', 'label' => 'Name of institution', 'type' => 'text', 'required' => true],
            ['id' => 'programs', 'label' => 'Programs', 'type' => 'select_dynamic', 'model' => 'App\Models\Program', 'display_column' => 'program_name', 'required' => true],
            ['id' => 'qualification_awarded', 'label' => 'Qualification Awarded', 'type' => 'text', 'required' => true],
            ['id' => 'awarding_institution', 'label' => 'Qualification Awarding Institution', 'type' => 'text', 'required' => true],
            ['id' => 'facility_ownership', 'label' => 'Facility Ownership', 'type' => 'text', 'required' => true],
            ['id' => 'registration_number', 'label' => 'Registration Number', 'type' => 'text', 'required' => true],
            ['id' => 'license_number', 'label' => 'License Number', 'type' => 'text', 'required' => true],
            ['id' => 'total_bed_capacity', 'label' => 'Total Bed Capacity', 'type' => 'number', 'required' => true],
            ['id' => 'bed_occupancy_rate', 'label' => 'Bed Occupancy Rate (%)', 'type' => 'number', 'required' => true],
            ['id' => 'daily_outpatient_turnover', 'label' => 'Daily Outpatient Turnover', 'type' => 'number', 'required' => true]
        ],
        2 => [
            ['id' => 'vision_statement', 'label' => 'Vision Statement', 'type' => 'textarea', 'required' => true],
            ['id' => 'mission_statement', 'label' => 'Mission Statement', 'type' => 'textarea', 'required' => true],
            ['id' => 'governance_structure', 'label' => 'Governance Structure', 'type' => 'textarea', 'required' => true],
            ['id' => 'academic_dean', 'label' => 'Academic Dean/President', 'type' => 'text', 'required' => true],
            ['id' => 'hod_count', 'label' => 'Number of Heads of Departments', 'type' => 'number', 'required' => true],
            ['id' => 'program_director', 'label' => 'Program Director', 'type' => 'text', 'required' => true],
            ['id' => 'curriculum_committee', 'label' => 'Curriculum Committee', 'type' => 'textarea', 'required' => true],
            ['id' => 'student_representation', 'label' => 'Student Representation', 'type' => 'textarea', 'required' => true]
        ],
        3 => [
            ['id' => 'degree_title', 'label' => 'Degree/Diploma/Fellowship Title', 'type' => 'text', 'required' => true],
            ['id' => 'curriculum_approved', 'label' => 'Approved Training Curriculum', 'type' => 'textarea', 'required' => true],
            ['id' => 'admission_requirements', 'label' => 'Minimum Admission Requirements', 'type' => 'textarea', 'required' => true],
            ['id' => 'program_duration', 'label' => 'Program Duration (months)', 'type' => 'number', 'required' => true],
            ['id' => 'apprentice_based', 'label' => 'Apprentice Based Training', 'type' => 'select', 'options' => ['Yes', 'No'], 'required' => true],
            ['id' => 'partnerships', 'label' => 'Partnerships and Collaborations', 'type' => 'textarea', 'required' => true],
            ['id' => 'academic_support', 'label' => 'Academic Support Policies', 'type' => 'textarea', 'required' => true]
        ],
        4 => [
            ['id' => 'administrative_offices', 'label' => 'Administrative Offices', 'type' => 'textarea', 'required' => true],
            ['id' => 'teaching_rooms', 'label' => 'Teaching and Tutorial Rooms', 'type' => 'textarea', 'required' => true],
            ['id' => 'laboratories', 'label' => 'Equipped Technical Laboratories', 'type' => 'textarea', 'required' => true],
            ['id' => 'learning_resources', 'label' => 'Learning Resource Centre', 'type' => 'textarea', 'required' => true],
            ['id' => 'internet_connectivity', 'label' => 'Internet Connectivity', 'type' => 'textarea', 'required' => true],
            ['id' => 'printing_facilities', 'label' => 'Printing and Photocopying Facilities', 'type' => 'textarea', 'required' => true]
        ],
        5 => [
            ['id' => 'staff_policy', 'label' => 'Staff Policy Document', 'type' => 'textarea', 'requires_upload' => true, 'required' => true],
            ['id' => 'qualified_staff', 'label' => 'Qualified Staff Available', 'type' => 'textarea', 'required' => true],
            ['id' => 'full_time_staff', 'label' => 'Full Time Academic Staff', 'type' => 'textarea', 'required' => true],
            ['id' => 'registration_licensure', 'label' => 'Registration and Licensure', 'type' => 'textarea', 'requires_upload' => true, 'required' => true],
            ['id' => 'staff_development', 'label' => 'Staff Development Programs', 'type' => 'textarea', 'required' => true],
            ['id' => 'faculty_ratio', 'label' => 'Faculty to Student Ratio', 'type' => 'number', 'required' => true],
            ['id' => 'part_time_staff', 'label' => 'Part Time/Adjunct Staff', 'type' => 'textarea', 'required' => true],
            ['id' => 'staff_welfare', 'label' => 'Staff Welfare Policy', 'type' => 'textarea', 'required' => true]
        ],
        6 => [
            ['id' => 'student_support', 'label' => 'Student Support Mechanisms', 'type' => 'textarea', 'required' => true],
            ['id' => 'mentorship_program', 'label' => 'Mentorship and Academic Support', 'type' => 'textarea', 'required' => true],
            ['id' => 'code_of_conduct', 'label' => 'Code of Conduct/Dress Code', 'type' => 'textarea', 'requires_upload' => true, 'required' => true],
            ['id' => 'student_grievance', 'label' => 'Student Grievance Mechanism', 'type' => 'textarea', 'required' => true],
            ['id' => 'accommodation_facilities', 'label' => 'Accommodation Facilities', 'type' => 'textarea', 'required' => true],
            ['id' => 'disciplinary_procedures', 'label' => 'Disciplinary Procedures', 'type' => 'textarea', 'required' => true],
            ['id' => 'professional_associations', 'label' => 'Professional Associations Membership', 'type' => 'textarea', 'required' => true]
        ],
        7 => [
            ['id' => 'quality_assurance', 'label' => 'Quality Assurance Policy', 'type' => 'textarea', 'requires_upload' => true, 'required' => true],
            ['id' => 'curriculum_management', 'label' => 'Curriculum Management System', 'type' => 'textarea', 'required' => true],
            ['id' => 'feedback_mechanism', 'label' => 'Feedback Mechanism', 'type' => 'textarea', 'required' => true],
            ['id' => 'monitoring_evaluation', 'label' => 'Monitoring and Evaluation', 'type' => 'textarea', 'required' => true]
        ],
        8 => [
            ['id' => 'research_policy', 'label' => 'Research Policy', 'type' => 'textarea', 'requires_upload' => true, 'required' => true],
            ['id' => 'research_funding', 'label' => 'Research Funding (2% of budget)', 'type' => 'textarea', 'required' => true],
            ['id' => 'research_outputs', 'label' => 'Research Outputs Documentation', 'type' => 'textarea', 'required' => true],
            ['id' => 'innovation_support', 'label' => 'Innovation Support', 'type' => 'textarea', 'required' => true]
        ],
    ]
];
