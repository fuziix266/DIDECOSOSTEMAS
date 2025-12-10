<?php

namespace Radio;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig(): array
    {
        return [
            'factories' => [
                // Adaptador específico para Radio
                'RadioDbAdapter' => function ($container) {
                    return new \Laminas\Db\Adapter\Adapter([
                        'driver'   => 'Pdo',
                        'dsn'      => 'mysql:dbname=' . (getenv('DB_NAME') ?: 'radio_app') . ';host=' . (getenv('DB_HOST') ?: 'localhost') . ';charset=utf8',
                        'username' => getenv('DB_USER') ?: 'root',
                        'password' => getenv('DB_PASS') ?: '',
                        'driver_options' => [
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        ],
                    ]);
                },

                // TableGateways
                'RadioNewsTableGateway' => function ($container) {
                    $dbAdapter = $container->get('RadioDbAdapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },
                'RadioProgramsTableGateway' => function ($container) {
                    $dbAdapter = $container->get('RadioDbAdapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('programs', $dbAdapter, null, $resultSetPrototype);
                },
                'RadioTeamTableGateway' => function ($container) {
                    $dbAdapter = $container->get('RadioDbAdapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('team', $dbAdapter, null, $resultSetPrototype);
                },
                'RadioUsersTableGateway' => function ($container) {
                    $dbAdapter = $container->get('RadioDbAdapter');
                    $resultSetPrototype = new ResultSet();
                    return new TableGateway('users', $dbAdapter, null, $resultSetPrototype);
                },

                // Repositories
                Repository\UserRepository::class => function ($container) {
                    return new Repository\UserRepository(
                        $container->get('RadioUsersTableGateway')
                    );
                },

                // Services
                Service\AuthService::class => function ($container) {
                    return new Service\AuthService(
                        $container->get(Repository\UserRepository::class)
                    );
                },
            ],
        ];
    }

    public function getControllerConfig(): array
    {
        return [
            'factories' => [
                Controller\AuthController::class => function ($container) {
                    return new Controller\AuthController(
                        $container->get(Service\AuthService::class)
                    );
                },
                Controller\AdminController::class => function ($container) {
                    return new Controller\AdminController(
                        $container->get(Service\AuthService::class),
                        $container->get(Repository\UserRepository::class)
                    );
                },
            ],
        ];
    }

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
        $em  = $app->getEventManager();
        $em->attach(\Laminas\Mvc\MvcEvent::EVENT_DISPATCH, [$this, 'setLayout'], 100);
    }

    public function setLayout($e)
    {
        $matches = $e->getRouteMatch();
        if (!$matches) {
            return;
        }

        $controller = $matches->getParam('controller');

        // Use radio layout for Web controllers in Radio namespace
        if (
            strpos($controller, 'Radio\Controller\Web') === 0 ||
            strpos($controller, 'Radio\Controller\Dashboard') === 0 ||
            strpos($controller, 'Radio\Controller\Admin') === 0 ||
            strpos($controller, 'Radio\Controller\Auth') === 0
        ) {
            $viewModel = $e->getViewModel();
            $viewModel->setTemplate('layout/radio');
        }
    }
}
