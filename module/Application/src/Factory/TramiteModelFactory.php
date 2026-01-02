<?php

namespace Application\Factory;

use Application\Model\TramiteModel;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class TramiteModelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        // Usar el adaptador db_departamentos para acceder a la tabla tramites
        $dbAdapter = $container->get('db_departamentos');
        return new TramiteModel($dbAdapter);
    }
}
