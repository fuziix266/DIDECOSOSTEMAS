<?php

namespace Radio;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            // Dashboard
            'radio' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/radio',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            // Web Admin Routes
            'radio-news' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/radio/news[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\Web\NewsController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                ],
            ],
            'radio-programs' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/radio/programs[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\Web\ProgramsController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                ],
            ],
            'radio-team' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/radio/team[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\Web\TeamController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                ],
            ],

            // API Routes
            'api-radio-news' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/radio/news[/:id]',
                    'defaults' => [
                        'controller' => Controller\Api\NewsController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            'api-radio-programs' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/radio/programs[/:id]',
                    'defaults' => [
                        'controller' => Controller\Api\ProgramsController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            'api-radio-team' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/radio/team[/:id]',
                    'defaults' => [
                        'controller' => Controller\Api\TeamController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            // Rutas de Autenticación
            'radio-auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/radio/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],

            // Rutas de Administración
            'radio-admin' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/radio/admin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'radio-admin-usuarios' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/radio/admin/usuarios',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'usuarios',
                    ],
                ],
            ],
            'radio-admin-guardar-usuario' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/radio/admin/guardar-usuario',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'guardar-usuario',
                    ],
                ],
            ],
            'radio-admin-eliminar-usuario' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/radio/admin/eliminar-usuario/:id',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'eliminar-usuario',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\Web\NewsController::class => Controller\Factory\ControllerFactory::class,
            Controller\Web\ProgramsController::class => Controller\Factory\ControllerFactory::class,
            Controller\Web\TeamController::class => Controller\Factory\ControllerFactory::class,
            Controller\Api\NewsController::class => Controller\Factory\ControllerFactory::class,
            Controller\Api\ProgramsController::class => Controller\Factory\ControllerFactory::class,
            Controller\Api\TeamController::class => Controller\Factory\ControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'Radio\Db\Adapter' => 'RadioDbAdapter',
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_map' => [
            'layout/radio' => __DIR__ . '/../view/layout/radio.phtml',
        ],
        'template_path_stack' => [
            'radio' => __DIR__ . '/../view',
        ],
    ],
];
