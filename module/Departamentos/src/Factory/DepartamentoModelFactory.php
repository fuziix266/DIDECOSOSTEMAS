<?php

namespace Departamentos\Factory;

use Departamentos\Model\DepartamentoModel;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DepartamentoModelFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Usar el adaptador específico de Departamentos
        $dbAdapter = $container->get('DepartamentosDbAdapter');
        return new DepartamentoModel($dbAdapter);
    }
}
