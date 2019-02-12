<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | Configure model settings
    |
    */
    'models' => [
        'directory' => 'Models', // default Laravel is ''
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    |
    | Configure auth settings
    |
    */
    'auth' => [
        'ownership' => [
            'enabled' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Modules
    |--------------------------------------------------------------------------
    |
    | Should Jetpack use ownership
    |
    */
    'modules' => [
        /**
         * Roles and permissions
         */
        'roles_and_permissions' => [
            'enable' => true,

            'roles' => [
                [
                    'name'        => 'owner',
                    'display'     => 'Owner',
                    'description' => '',
                ],
                [
                    'name'        => 'admin',
                    'display'     => 'Admin',
                    'description' => '',
                ],
                [
                    'name'        => 'member',
                    'display'     => 'Member',
                    'description' => '',
                ],
            ],

            'permissions' => [
                [
                    'name'        => 'create-',
                    'display'     => 'Owner',
                    'description' => '',
                ],
            ],
        ],
    ],
];
