<?php



namespace Departamentos\Factory;



use Laminas\Db\Adapter\AdapterInterface;

use Psr\Container\ContainerInterface;

use Laminas\ServiceManager\Factory\FactoryInterface;

use Departamentos\Model\EvaluacionModel;



class EvaluacionModelFactory implements FactoryInterface

{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)

    {

        // Usar el adaptador específico de Departamentos (BD dideco local)

        $adapter = $container->get('DepartamentosDbAdapter');

        return new EvaluacionModel($adapter);

    }

}

