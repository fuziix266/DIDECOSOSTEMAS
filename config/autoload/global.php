<?php

return [
    // Esta configuración es para otros módulos que no sean Departamentos
    'db' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=conaricl_dideco;host=localhost;charset=utf8',
        'username' => 'conaricl_dideco',
        'password' => 'didecoadmin123',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    // Configuración para el módulo Departamentos
    'db_departamentos' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=conaricl_dideco;host=localhost;charset=utf8',
        'username' => 'conaricl_dideco',
        'password' => 'didecoadmin123',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    // Configuración para el módulo Radio
    'db_radio' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=conaricl_dideco;host=localhost;charset=utf8',
        'username' => 'conaricl_dideco',
        'password' => 'didecoadmin123',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    // Configuración para el módulo Vehiculos
    'db_vehiculos' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=conaricl_dideco;host=localhost;charset=utf8',
        'username' => 'conaricl_dideco',
        'password' => 'didecoadmin123',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    'service_manager' => [
        'factories' => [
            Laminas\Db\Adapter\Adapter::class => Laminas\Db\Adapter\AdapterServiceFactory::class,
            Laminas\Db\Adapter\AdapterInterface::class => Laminas\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],

];
