<?php

namespace Departamentos\Factory;

use Departamentos\Controller\JuventudController;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class JuventudControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $departamentoModel = $container->get(DepartamentoModel::class);
        $tramiteModel = $container->get(TramiteModel::class);
        
        return new JuventudController($departamentoModel, $tramiteModel);
    }
}
