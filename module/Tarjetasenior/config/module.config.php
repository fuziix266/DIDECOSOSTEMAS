<?php

declare(strict_types=1);

namespace Tarjetasenior;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'tarjetasenior' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/tarjetasenior[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'tarjetasenior' => __DIR__ . '/../view',
        ],
    ],
];
