<?php

namespace Departamentos\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Departamentos\Controller\EvaluacionController;
use Departamentos\Model\EvaluacionModel;

class EvaluacionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $evaluacionModel = $container->get(EvaluacionModel::class);
        return new EvaluacionController($evaluacionModel); 
    }
}
