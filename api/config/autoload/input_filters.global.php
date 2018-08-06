<?php

declare(strict_types=1);

return [
    'input_filter_specs' => [
        // users
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
        \App\Middleware\InputFilter\NameInputFilter::class => [
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
        \App\Middleware\InputFilter\NameAndEmailInputFilter::class => [
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
        ],
        // css
        \App\Middleware\InputFilter\CssInputFilter::class => [
            // fields
            // name description style tag_element tag_description category_name category_description
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
                'description' => 'Name of style',
                'allow_empty' => false
            ],
            1 => [
                'name' => 'description',
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
                'description' => 'Description of style',
                'allow_empty' => false
            ],
            2 => [
                'name' => 'style',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty']
                ],
                'description' => 'Style in text/css',
                'allow_empty' => false
            ],
            3 => [
                'name' => 'tag_element',
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 25]
                    ]
                ],
                'description' => 'Tag in HTML5',
                'allow_empty' => false
            ],
            4 => [
                'name' => 'tag_description',
                'required' => false,
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
                'description' => 'Tag in HTML5',
                'allow_empty' => false
            ],
            5 => [
                'name' => 'category_name',
                'required' => false,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 25]
                    ]
                ],
                'description' => 'Name category element',
                'allow_empty' => false
            ],
            6 => [
                'name' => 'category_description',
                'required' => false,
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
                'description' => 'Description category element',
                'allow_empty' => false
            ],
        ],
    ],
];
