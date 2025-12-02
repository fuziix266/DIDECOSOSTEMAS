<?php

namespace Departamentos\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Departamentos\Controller\MantencionController;
use Departamentos\Model\UsuarioModel;
use Departamentos\Model\AuditoriaModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class MantencionControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $dbAdapter = $container->get('DepartamentosDbAdapter');
        
        $usuarioModel = new UsuarioModel($dbAdapter);
        $auditoriaModel = new AuditoriaModel($dbAdapter);
        $departamentoModel = $container->get(DepartamentoModel::class);
        $tramiteModel = $container->get(TramiteModel::class);
        
        return new MantencionController(
            $usuarioModel,
            $auditoriaModel,
            $departamentoModel,
            $tramiteModel
        );
    }
}
