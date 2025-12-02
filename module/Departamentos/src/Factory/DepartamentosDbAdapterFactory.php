<?php

namespace Departamentos\Factory;

use Laminas\Db\Adapter\Adapter;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DepartamentosDbAdapterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $dbConfig = $config['db_departamentos'];
        
        return new Adapter($dbConfig);
    }
}
