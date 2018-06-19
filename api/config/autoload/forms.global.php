<?php

declare(strict_types=1);

return [
    'input_filter_specs' => [
        \App\Handler\Middleware\InputFilter\ContactInputFilter::class => [
            0 => [
                'name' => 'firstName',
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
                'description' => 'Your first name',
                'allow_empty' => false
            ],
            1 => [
                'name' => 'lastName',
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
                'description' => 'Your last name',
                'allow_empty' => false
            ],
            2 => [
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
            3 => [
                'name' => 'subject',
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
                'description' => 'Subject this message',
                'allow_empty' => false
            ],
            4 => [
                'name' => 'message',
                'required' => true,
                'filters' => [
                    ['name' => 'StringTrim']
                ],
                'validators' => [
                    ['name' => 'NotEmpty'],
                    [
                        'name' => 'StringLength',
                        'options' => ['max' => 1500]
                    ]
                ],
                'description' => 'Your message',
                'allow_empty' => false
            ],
        ],
    ],
];
