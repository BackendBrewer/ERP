<?php

return [
    'modules' => [
        'dashboard' => ['name' => 'Dashboard', 'permissions' => ['dashboard.view' => 'View dashboard']],
        'institute' => ['name' => 'Institute', 'permissions' => [
            'institute.profile.view' => 'View institute profile',
            'institute.profile.update' => 'Update institute profile',
        ]],
        'staff' => ['name' => 'Staff', 'permissions' => [
            'staff.view' => 'View institute staff',
            'staff.create' => 'Create institute staff',
            'staff.assign_role' => 'Assign approved staff roles',
            'staff.deactivate' => 'Deactivate institute staff',
        ]],
        'roles' => ['name' => 'Roles & permissions', 'permissions' => [
            'roles.view' => 'View roles and permissions',
        ]],
        'students' => ['name' => 'Students', 'permissions' => [
            'students.view_assigned' => 'View assigned students',
            'students.view_contact' => 'View student contact details',
            'students.manage' => 'Manage student profiles',
        ]],
        'admissions' => ['name' => 'Admissions', 'permissions' => [
            'admissions.view' => 'View admission inquiries',
            'admissions.manage' => 'Manage admission inquiries',
        ]],
        'academics' => ['name' => 'Academics', 'permissions' => [
            'academics.view_assigned' => 'View assigned academic groups',
            'academics.manage_assigned' => 'Manage assigned academic groups',
        ]],
        'attendance' => ['name' => 'Attendance', 'permissions' => [
            'attendance.view_assigned' => 'View assigned attendance',
            'attendance.mark' => 'Mark assigned attendance',
            'attendance.correct_assigned' => 'Correct assigned attendance',
        ]],
        'exams' => ['name' => 'Examinations', 'permissions' => [
            'exams.view_assigned' => 'View assigned examinations',
            'exams.enter_marks_assigned' => 'Enter marks for assigned examinations',
            'exams.manage_assigned' => 'Manage assigned examinations',
        ]],
        'fees' => ['name' => 'Fees', 'permissions' => [
            'fees.view' => 'View fee information',
            'fees.collect_receipt' => 'Collect fees and issue receipts',
        ]],
        'reports' => ['name' => 'Reports', 'permissions' => [
            'reports.academic' => 'View academic reports',
            'reports.financial' => 'View financial reports',
        ]],
        'audit' => ['name' => 'Audit', 'permissions' => [
            'audit.view' => 'View institute audit history',
        ]],
    ],

    'roles' => [
        'institute-admin' => '*',
        'teacher' => [
            'dashboard.view', 'students.view_assigned', 'academics.view_assigned',
            'attendance.view_assigned', 'attendance.mark', 'exams.view_assigned',
            'exams.enter_marks_assigned',
        ],
        'coordinator' => [
            'dashboard.view', 'students.view_assigned', 'academics.view_assigned',
            'academics.manage_assigned', 'attendance.view_assigned', 'attendance.correct_assigned',
            'exams.view_assigned', 'exams.manage_assigned', 'reports.academic',
        ],
        'receptionist' => [
            'dashboard.view', 'students.view_contact', 'admissions.view', 'admissions.manage',
        ],
    ],
];
