<?php

use App\Models\User\Employee;
use App\Models\User\Student;

return [
    'user_types_rules' => [
        'loan' => [
            Student::EDU_LEVEL_BACHELOR => env('UNDERGRADUATE_STUDENTS_LOAN', 5),
            Student::EDU_LEVEL_MASTERS => env('MASTER_PHD_STUDENTS_LOAN', 5),
            Student::EDU_LEVEL_DOCTORATE => env('MASTER_PHD_STUDENTS_LOAN', 5),
            Student::EDU_LEVEL_CAREER_DEVELOPMENT => env('MASTER_PHD_STUDENTS_LOAN', 5),
            Student::EDU_LEVEL_DISSERTATE => env('MASTER_PHD_STUDENTS_LOAN', 5),

            Employee::POSITION_TYPE_ACADEMICIAN => env('TEACHING_STUFF_LOAN', 5),
            Employee::POSITION_TYPE_ACADEMIC => env('TEACHING_STUFF_LOAN', 5),
            Employee::POSITION_TYPE_ADMINISTRATIVE => env('UNIVERSITY_STUFF_LOAN', 5),
            Employee::POSITION_TYPE_ASSISTANT => env('UNIVERSITY_STUFF_LOAN', 5),
        ],
        'borrow' => [
            Student::EDU_LEVEL_BACHELOR => env('UNDERGRADUATE_STUDENTS_BORROW', 21),
            Student::EDU_LEVEL_MASTERS => env('MASTER_PHD_STUDENTS_BORROW', 21),
            Student::EDU_LEVEL_DOCTORATE => env('MASTER_PHD_STUDENTS_BORROW', 21),
            Student::EDU_LEVEL_CAREER_DEVELOPMENT => env('MASTER_PHD_STUDENTS_BORROW', 21),
            Student::EDU_LEVEL_DISSERTATE => env('MASTER_PHD_STUDENTS_BORROW', 21),

            Employee::POSITION_TYPE_ACADEMICIAN => env('TEACHING_STUFF_BORROW', 21),
            Employee::POSITION_TYPE_ACADEMIC => env('TEACHING_STUFF_BORROW', 21),
            Employee::POSITION_TYPE_ADMINISTRATIVE => env('UNIVERSITY_STUFF_BORROW', 21),
            Employee::POSITION_TYPE_ASSISTANT => env('UNIVERSITY_STUFF_BORROW', 21),
        ],
        'prolong' => [
            Student::EDU_LEVEL_BACHELOR => env('UNDERGRADUATE_STUDENTS_PROLONG'),
            Student::EDU_LEVEL_MASTERS => env('MASTER_PHD_STUDENTS_PROLONG'),
            Student::EDU_LEVEL_DOCTORATE => env('MASTER_PHD_STUDENTS_PROLONG'),
            Student::EDU_LEVEL_CAREER_DEVELOPMENT => env('MASTER_PHD_STUDENTS_PROLONG'),
            Student::EDU_LEVEL_DISSERTATE => env('MASTER_PHD_STUDENTS_PROLONG'),

            Employee::POSITION_TYPE_ACADEMICIAN => env('TEACHING_STUFF_PROLONG'),
            Employee::POSITION_TYPE_ACADEMIC => env('TEACHING_STUFF_PROLONG'),
            Employee::POSITION_TYPE_ADMINISTRATIVE => env('UNIVERSITY_STUFF_PROLONG'),
            Employee::POSITION_TYPE_ASSISTANT => env('UNIVERSITY_STUFF_PROLONG'),
        ],
    ],
];
