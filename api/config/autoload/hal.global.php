<?php

declare(strict_types=1);

use App\Domain\Entity\Css;
use App\Domain\Entity\User;
use App\Infrastructure\Hydrators\CssCollection;
use App\Infrastructure\Hydrators\UsersCollection;
use App\Infrastructure\Hydrators\SerializeExtractor;
use Zend\Expressive\Hal\Metadata\MetadataMap;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;
use Zend\Expressive\Hal\Metadata\RouteBasedCollectionMetadata;
use Zend\Expressive\Hal\Renderer\XmlRenderer;

return [
    MetadataMap::class => [
        [
            '__class__' => RouteBasedResourceMetadata::class,
            'resource_class' => Css::class,
            'route' => 'css.get',
            'extractor' => SerializeExtractor::class,
        ],
        [
            '__class__' => RouteBasedCollectionMetadata::class,
            'collection_class' => CssCollection::class,
            'collection_relation' => 'css_details',
            'route' => 'css.all.get',
        ],
        [
            '__class__' => RouteBasedResourceMetadata::class,
            'resource_class' => User::class,
            'route' => 'user.get',
            'extractor' => SerializeExtractor::class,
        ],
        [
            '__class__' => RouteBasedCollectionMetadata::class,
            'collection_class' => UsersCollection::class,
            'collection_relation' => 'users_details',
            'route' => 'user.all.get',
        ],
    ]
];
