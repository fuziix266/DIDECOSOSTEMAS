<?php

return [
    // Esta configuración es para otros módulos que no sean Departamentos
    'db' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=' . (getenv('DB_NAME') ?: 'conaricl_dideco') . ';host=' . (getenv('DB_HOST') ?: 'localhost') . ';charset=utf8',
        'username' => getenv('DB_USER') ?: 'conaricl_dideco',
        'password' => getenv('DB_PASS') ?: 'Ladronaso@266',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    // Configuración para el módulo Departamentos
    'db_departamentos' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=' . (getenv('DB_NAME') ?: 'conaricl_dideco') . ';host=' . (getenv('DB_HOST') ?: 'localhost') . ';charset=utf8',
        'username' => getenv('DB_USER') ?: 'conaricl_dideco',
        'password' => getenv('DB_PASS') ?: 'Ladronaso@266',
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
