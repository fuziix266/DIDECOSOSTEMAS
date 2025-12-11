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

    // Configuración para el módulo Radio
    'db_radio' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=' . (getenv('DB_NAME') ?: 'radio_app') . ';host=' . (getenv('DB_HOST') ?: 'localhost') . ';charset=utf8',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
        'driver_options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],

    // Configuración para el módulo Vehiculos
    'db_vehiculos' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=' . (getenv('DB_NAME') ?: 'qr_vehiculos_municipal') . ';host=' . (getenv('DB_HOST') ?: 'localhost') . ';charset=utf8',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASS') ?: '',
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
