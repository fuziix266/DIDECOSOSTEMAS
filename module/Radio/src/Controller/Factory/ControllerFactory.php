<?php

namespace Radio\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Db\Adapter\Adapter;

class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Get the specific Radio DB Adapter directly
        $dbAdapter = $container->get('RadioDbAdapter');
        return new $requestedName($dbAdapter);
    }
}
