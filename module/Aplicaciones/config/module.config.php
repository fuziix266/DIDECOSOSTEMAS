<?php

declare(strict_types=1);

namespace Aplicaciones;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'aplicaciones' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/aplicaciones[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'aplicaciones-firma' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/aplicaciones/firma[/:action]',
                    'defaults' => [
                        'controller' => Controller\FirmaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'aplicaciones-bingo' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/aplicaciones/bingo[/:action]',
                    'defaults' => [
                        'controller' => Controller\BingoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\FirmaController::class => InvokableFactory::class,
            Controller\BingoController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'aplicaciones' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_map' => [
            'layout/layout_bingo' => __DIR__ . '/../view/layout/layout_bingo.phtml',
        ],
    ],
];
