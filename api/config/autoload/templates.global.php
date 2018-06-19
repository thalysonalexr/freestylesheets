<?php

declare(strict_types=1);

return [
    'templates' => [
        'extension' => 'html.twig',
        'paths'     => [
            'app'    => ['resources/templates/app'],
            'pages'  => ['resources/templates/pages'],
            'layout' => ['resources/templates/layout'],
            'error'  => ['resources/templates/error'],
            'xml'    => ['resources/templates/xml'],
        ],
    ],
    'twig' => [
        // 'cache_dir'      => 'data/cache/twig',
        'assets_url'     => '/',
        // 'assets_version' => '',
        'extensions'     => [],
        'globals'        => [],
    ],
];
