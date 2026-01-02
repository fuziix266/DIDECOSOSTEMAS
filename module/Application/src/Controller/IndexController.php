<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Model\TramiteModel;

class IndexController extends AbstractActionController
{
    private $tramiteModel;

    public function __construct(TramiteModel $tramiteModel)
    {
        $this->tramiteModel = $tramiteModel;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    /**
     * Endpoint AJAX para búsqueda de trámites
     */
    public function searchAction()
    {
        $request = $this->getRequest();

        if (!$request->isXmlHttpRequest()) {
            return new JsonModel(['error' => 'Invalid request']);
        }

        $query = $request->getQuery('q', '');

        $results = $this->tramiteModel->searchTramites($query);

        return new JsonModel([
            'success' => true,
            'results' => $results
        ]);
    }
}
