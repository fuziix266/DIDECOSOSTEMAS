<?php

namespace Departamentos\Factory;

use Departamentos\Controller\MujeryequidadController;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MujeryequidadControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $departamentoModel = $container->get(DepartamentoModel::class);
        $tramiteModel = $container->get(TramiteModel::class);
        
        return new MujeryequidadController($departamentoModel, $tramiteModel);
    }
}
