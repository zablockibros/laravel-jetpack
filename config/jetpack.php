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
    | Roles and permissions
    |--------------------------------------------------------------------------
    |
    | Role and permission settings
    |
    */
    'roles' => [
        'enable' => true,

        'roles' => [
            'owner',
            'admin' => [
                // permissions (optional)
                'invite users',
            ],
            'member',
        ],

        'permissions' => [
            'invite users',
            'delete users',
        ],
    ],
];
