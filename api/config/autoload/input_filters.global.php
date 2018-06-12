<?php

declare(strict_types=1);

return [
    'input_filter_specs' => [
        \App\Middleware\InputFilter\UserInputFilter::class => [
            0 => [
                'name' => 'name',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 255]
                    ]
                ],
                'description' => 'Your full name',
                'allow_empty' => false
            ],
            1 => [
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    ['name' => 'EmailAddress'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 255]
                    ]
                ],
                'description' => 'Valid email address',
                'allow_empty' => false
            ],
            2 => [
                'name' => 'password',
                'required' => true,
                'filters' => [],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 255
                        ],
                        'break_chain_on_failure' => true,
                    ]
                ],
                'description' => 'Password in this account',
                'allow_empty' => false
            ],
            3 => [
                'name' => 'admin',
                'required' => true,
                'filters' => [],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'Between',
                        'options' => [
                            'min' => 0,
                            'max' => 1
                        ]
                    ]
                ],
                'description' => '1 for admin, 0 for user',
                'allow_empty' => false
            ],
        ],
        \App\Middleware\InputFilter\LoginInputFilter::class => [
            0 => [
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    ['name' => 'EmailAddress'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 255]
                    ]
                ],
                'description' => 'Valid email address',
                'allow_empty' => false
            ],
            1 => [
                'name' => 'password',
                'required' => true,
                'filters' => [],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 255
                        ],
                        'break_chain_on_failure' => true,
                    ]
                ],
                'description' => 'Password in this account',
                'allow_empty' => false
            ],
        ],
        \App\Middleware\InputFilter\EmailInputFilter::class => [
            0 => [
                'name' => 'email',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    ['name' => 'EmailAddress'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 255]
                    ]
                ],
                'description' => 'Valid email address',
                'allow_empty' => false
            ],
        ],
        \App\Middleware\InputFilter\PasswordInputFilter::class => [
            0 => [
                'name' => 'password',
                'required' => true,
                'filters' => [],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => [
                            'min' => 5,
                            'max' => 255
                        ],
                        'break_chain_on_failure' => true,
                    ]
                ],
                'description' => 'Password in this account',
                'allow_empty' => false
            ],
        ],
    ],
];
