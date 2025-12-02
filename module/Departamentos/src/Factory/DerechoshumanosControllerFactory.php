<?php

namespace Departamentos\Factory;



use Departamentos\Controller\DerechoshumanosController;

use Departamentos\Model\DepartamentoModel;

use Departamentos\Model\TramiteModel;

use Interop\Container\ContainerInterface;

use Laminas\ServiceManager\Factory\FactoryInterface;



class DerechoshumanosControllerFactory implements FactoryInterface

{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)

    {

        $departamentoModel = $container->get(DepartamentoModel::class);

        $tramiteModel = $container->get(TramiteModel::class);

        

        return new DerechoshumanosController($departamentoModel, $tramiteModel);

    }

}
