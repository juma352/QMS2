<?php

return [
    'questions' => [
        1 => [
            ['id' => 'facility_name', 'label' => 'Name of institution', 'type' => 'text'],
            ['id' => 'specialist_training', 'label' => 'Specialist Training provided', 'type' => 'text'],
            ['id' => 'qualification_awarded', 'label' => 'Qualification Awarded', 'type' => 'text'],
            ['id' => 'awarding_institution', 'label' => 'Qualification Awarding Institution', 'type' => 'text'],
            ['id' => 'facility_ownership', 'label' => 'Facility Ownership', 'type' => 'text'],
            ['id' => 'registration_number', 'label' => 'Registration Number', 'type' => 'text'],
            ['id' => 'license_number', 'label' => 'License Number', 'type' => 'text'],
            ['id' => 'total_bed_capacity', 'label' => 'Total Bed Capacity', 'type' => 'number'],
            ['id' => 'bed_occupancy_rate', 'label' => 'Bed Occupancy Rate (%)', 'type' => 'number'],
            ['id' => 'daily_outpatient_turnover', 'label' => 'Daily Outpatient Turnover', 'type' => 'number']
        ],
        2 => [
            ['id' => 'vision_statement', 'label' => 'Vision Statement', 'type' => 'textarea'],
            ['id' => 'mission_statement', 'label' => 'Mission Statement', 'type' => 'textarea'],
            ['id' => 'governance_structure', 'label' => 'Governance Structure', 'type' => 'textarea'],
            ['id' => 'academic_dean', 'label' => 'Academic Dean/President', 'type' => 'text'],
            ['id' => 'hod_count', 'label' => 'Number of Heads of Departments', 'type' => 'number'],
            ['id' => 'program_director', 'label' => 'Program Director', 'type' => 'text'],
            ['id' => 'curriculum_committee', 'label' => 'Curriculum Committee', 'type' => 'textarea'],
            ['id' => 'student_representation', 'label' => 'Student Representation', 'type' => 'textarea']
        ],
        3 => [
            ['id' => 'degree_title', 'label' => 'Degree/Diploma/Fellowship Title', 'type' => 'text'],
            ['id' => 'curriculum_approved', 'label' => 'Approved Training Curriculum', 'type' => 'textarea'],
            ['id' => 'admission_requirements', 'label' => 'Minimum Admission Requirements', 'type' => 'textarea'],
            ['id' => 'program_duration', 'label' => 'Program Duration (months)', 'type' => 'number'],
            ['id' => 'apprentice_based', 'label' => 'Apprentice Based Training', 'type' => 'select', 'options' => ['Yes', 'No']],
            ['id' => 'partnerships', 'label' => 'Partnerships and Collaborations', 'type' => 'textarea'],
            ['id' => 'academic_support', 'label' => 'Academic Support Policies', 'type' => 'textarea']
        ],
        4 => [
            ['id' => 'administrative_offices', 'label' => 'Administrative Offices', 'type' => 'textarea'],
            ['id' => 'teaching_rooms', 'label' => 'Teaching and Tutorial Rooms', 'type' => 'textarea'],
            ['id' => 'laboratories', 'label' => 'Equipped Technical Laboratories', 'type' => 'textarea'],
            ['id' => 'learning_resources', 'label' => 'Learning Resource Centre', 'type' => 'textarea'],
            ['id' => 'internet_connectivity', 'label' => 'Internet Connectivity', 'type' => 'textarea'],
            ['id' => 'printing_facilities', 'label' => 'Printing and Photocopying Facilities', 'type' => 'textarea']
        ],
        5 => [
            ['id' => 'staff_policy', 'label' => 'Staff Policy Document', 'type' => 'textarea', 'requires_upload' => true],
            ['id' => 'qualified_staff', 'label' => 'Qualified Staff Available', 'type' => 'textarea'],
            ['id' => 'full_time_staff', 'label' => 'Full Time Academic Staff', 'type' => 'textarea'],
            ['id' => 'registration_licensure', 'label' => 'Registration and Licensure', 'type' => 'textarea', 'requires_upload' => true],
            ['id' => 'staff_development', 'label' => 'Staff Development Programs', 'type' => 'textarea'],
            ['id' => 'faculty_ratio', 'label' => 'Faculty to Student Ratio', 'type' => 'number'],
            ['id' => 'part_time_staff', 'label' => 'Part Time/Adjunct Staff', 'type' => 'textarea'],
            ['id' => 'staff_welfare', 'label' => 'Staff Welfare Policy', 'type' => 'textarea']
        ],
        6 => [
            ['id' => 'student_support', 'label' => 'Student Support Mechanisms', 'type' => 'textarea'],
            ['id' => 'mentorship_program', 'label' => 'Mentorship and Academic Support', 'type' => 'textarea'],
            ['id' => 'code_of_conduct', 'label' => 'Code of Conduct/Dress Code', 'type' => 'textarea', 'requires_upload' => true],
            ['id' => 'student_grievance', 'label' => 'Student Grievance Mechanism', 'type' => 'textarea'],
            ['id' => 'accommodation_facilities', 'label' => 'Accommodation Facilities', 'type' => 'textarea'],
            ['id' => 'disciplinary_procedures', 'label' => 'Disciplinary Procedures', 'type' => 'textarea'],
            ['id' => 'professional_associations', 'label' => 'Professional Associations Membership', 'type' => 'textarea']
        ],
        7 => [
            ['id' => 'quality_assurance', 'label' => 'Quality Assurance Policy', 'type' => 'textarea', 'requires_upload' => true],
            ['id' => 'curriculum_management', 'label' => 'Curriculum Management System', 'type' => 'textarea'],
            ['id' => 'feedback_mechanism', 'label' => 'Feedback Mechanism', 'type' => 'textarea'],
            ['id' => 'monitoring_evaluation', 'label' => 'Monitoring and Evaluation', 'type' => 'textarea']
        ],
        8 => [
            ['id' => 'research_policy', 'label' => 'Research Policy', 'type' => 'textarea', 'requires_upload' => true],
            ['id' => 'research_funding', 'label' => 'Research Funding (2% of budget)', 'type' => 'textarea'],
            ['id' => 'research_outputs', 'label' => 'Research Outputs Documentation', 'type' => 'textarea'],
            ['id' => 'innovation_support', 'label' => 'Innovation Support', 'type' => 'textarea']
        ]
    ]
];
