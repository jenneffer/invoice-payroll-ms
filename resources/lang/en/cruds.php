<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission'     => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => '',
            'title'             => 'Title',
            'title_helper'      => '',
            'created_at'        => 'Created at',
            'created_at_helper' => '',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => '',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => '',
        ],
    ],
    'role'           => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => '',
            'title'              => 'Title',
            'title_helper'       => '',
            'permissions'        => 'Permissions',
            'permissions_helper' => '',
            'created_at'         => 'Created at',
            'created_at_helper'  => '',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => '',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => '',
        ],
    ],
    'user'           => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => '',
            'name'                     => 'Name',
            'name_helper'              => '',
            'email'                    => 'Email',
            'email_helper'             => '',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => '',
            'password'                 => 'Password',
            'password_helper'          => '',
            'roles'                    => 'Roles',
            'roles_helper'             => '',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => '',
            'created_at'               => 'Created at',
            'created_at_helper'        => '',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => '',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => '',
        ],
    ],
    'student'        => [
        'title'          => 'Students',
        'title_singular' => 'Student',
        'fields'         => [
            'id'                     => 'ID',
            'id_helper'              => '',
            'first_name'             => 'First Name',
            'first_name_helper'      => '',
            'last_name'              => 'Last Name',
            'last_name_helper'       => '',
            'email'                  => 'Email',
            'email_helper'           => '',
            'billing_address'        => 'Billing Address',
            'billing_address_helper' => '',
            'created_at'             => 'Created at',
            'created_at_helper'      => '',
            'updated_at'             => 'Updated at',
            'updated_at_helper'      => '',
            'deleted_at'             => 'Deleted at',
            'deleted_at_helper'      => '',
        ],
    ],
    'attendance'     => [
        'title'          => 'Attendance',
        'title_singular' => 'Attendance',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => '',
            'event_date'        => 'Event Date',
            'event_date_helper' => '',
            'student'           => 'Student',
            'student_helper'    => '',
            'created_at'        => 'Created at',
            'created_at_helper' => '',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => '',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => '',
        ],
    ],
    'invoice'        => [
        'title'          => 'Invoices',
        'title_singular' => 'Invoice',
        'fields'         => [
            'id'                    => 'ID',
            'id_helper'             => '',
            'student'               => 'Student',
            'student_helper'        => '',
            'period_from'           => 'Period From',
            'period_from_helper'    => '',
            'period_to'             => 'Period To',
            'period_to_helper'      => '',
            'invoice_number'        => 'Invoice Number',
            'invoice_number_helper' => '',
            'total_amount'          => 'Total Amount',
            'total_amount_helper'   => '',
            'paid_at'               => 'Paid At',
            'paid_at_helper'        => '',
            'created_at'            => 'Created at',
            'created_at_helper'     => '',
            'updated_at'            => 'Updated at',
            'updated_at_helper'     => '',
            'deleted_at'            => 'Deleted at',
            'deleted_at_helper'     => '',
        ],
    ],
];
