<?php

return [
    // Esta configuración es para otros módulos que no sean Departamentos
    'db' => [
        'driver'   => 'Pdo',
        'dsn'      => 'mysql:dbname=conaricl_dideco;host=localhost;charset=utf8',
        'username' => 'conaricl_dideco',
        'password' => 'Ladronaso@266',
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

