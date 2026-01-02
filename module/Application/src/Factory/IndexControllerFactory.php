<?php

namespace Application\Factory;

use Application\Controller\IndexController;
use Application\Model\TramiteModel;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $tramiteModel = $container->get(TramiteModel::class);
        return new IndexController($tramiteModel);
    }
}
