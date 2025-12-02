<?php

namespace Departamentos;

use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            // Página raíz (inicio del sitio) apunta a IndexController del módulo Departamentos
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta general para el controlador Index
            'departamentos' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador ServicioSocial
            'departamentos-serviciosocial' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/serviciosocial[/:action]',
                    'defaults' => [
                        'controller' => Controller\ServiciosocialController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Odima
            'departamentos-odima' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/odima[/:action]',
                    'defaults' => [
                        'controller' => Controller\OdimaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para mantención del sistema
            'departamentos-mantencion' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/mantencion[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\MantencionController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
            // Ruta para el controlador Enlacenorte
            'departamentos-enlacenorte' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/enlacenorte[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\EnlacenorteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Oln
            'departamentos-oln' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/oln[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\OlnController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Mujer y Equidad
            'departamentos-mujeryequidad' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/mujeryequidad[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\MujeryequidadController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Afrodescendientes
            'departamentos-afrodescendientes    ' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/afrodescendientes[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\AfrodescendientesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Discapacidad
            'departamentos-discapacidad' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/discapacidad[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\DiscapacidadController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Rsh
            'departamentos-rsh' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/rsh[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\RshController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Subsidioypensiones
            'departamentos-subsidioypensiones' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/subsidioypensiones[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\SubsidioypensionesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Vivienda
            'departamentos-gestionhabitacional' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/gestionhabitacional[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\GestionhabitacionalController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Derechos Humanos
            'departamentos-derechoshumanos' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/derechoshumanos[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\DerechoshumanosController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Comodato
            'departamentos-comodato' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/comodato[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\ComodatoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Omil
            'departamentos-omil' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/omil[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\OmilController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Defensoriaciudadana
            'departamentos-defensoriaciudadana' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/defensoriaciudadana[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\DefensoriaciudadanaController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Juventud
            'departamentos-juventud' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/juventud[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\JuventudController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Ocam
            'departamentos-ocam' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/ocam[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\OcamController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Presupuestoparticipativo
            'departamentos-presupuestoparticipativo' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/presupuestoparticipativo[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\PresupuestoparticipativoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            // Ruta para el controlador Evaluacion
            'departamentos-evaluacion' => [
                'type'    => \Laminas\Router\Http\Segment::class,
                'options' => [
                    'route'    => '/departamentos/evaluacion[/:action]',
                    'defaults' => [
                        'controller' => \Departamentos\Controller\EvaluacionController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\ServiciosocialController::class => Factory\ServiciosocialControllerFactory::class,
            Controller\OdimaController::class => Factory\OdimaControllerFactory::class,
            Controller\EnlacenorteController::class => Factory\EnlacenorteControllerFactory::class,
            Controller\OlnController::class => Factory\OlnControllerFactory::class,
            Controller\MujeryequidadController::class => Factory\MujeryequidadControllerFactory::class,
            Controller\AfrodescendientesController::class => \Departamentos\Factory\AfrodescendientesControllerFactory::class,
            Controller\DiscapacidadController::class => Factory\DiscapacidadControllerFactory::class,
            Controller\RshController::class => Factory\RshControllerFactory::class,
            Controller\SubsidioypensionesController::class => Factory\SubsidioypensionesControllerFactory::class,
            Controller\GestionhabitacionalController::class => Factory\GestionhabitacionalControllerFactory::class,
            Controller\DerechoshumanosController::class => Factory\DerechoshumanosControllerFactory::class,
            Controller\ComodatoController::class => Factory\ComodatoControllerFactory::class,
            Controller\DefensoriaciudadanaController::class => Factory\DefensoriaciudadanaControllerFactory::class,
            Controller\OmilController::class => Factory\OmilControllerFactory::class,
            Controller\JuventudController::class => Factory\JuventudControllerFactory::class,
            Controller\OcamController::class => Factory\OcamControllerFactory::class,
            Controller\PresupuestoparticipativoController::class => Factory\PresupuestoparticipativoControllerFactory::class,
            Controller\EvaluacionController::class => \Departamentos\Factory\EvaluacionControllerFactory::class,
            Controller\MantencionController::class => Factory\MantencionControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'departamentos' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

    // Servicios
    'service_manager' => [
        'factories' => [
            \Departamentos\Model\EvaluacionModel::class => \Departamentos\Factory\EvaluacionModelFactory::class,
            \Departamentos\Model\DepartamentoModel::class => \Departamentos\Factory\DepartamentoModelFactory::class,
            \Departamentos\Model\TramiteModel::class => \Departamentos\Factory\TramiteModelFactory::class,
            'DepartamentosDbAdapter' => \Departamentos\Factory\DepartamentosDbAdapterFactory::class,
        ],
    ],



];
