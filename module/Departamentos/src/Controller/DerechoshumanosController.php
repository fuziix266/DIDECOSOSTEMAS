<?php

namespace Departamentos\Controller;

use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DerechoshumanosController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 15; // ID de Derechos Humanos en la BD

    public function __construct(DepartamentoModel $departamentoModel, TramiteModel $tramiteModel)
    {
        $this->departamentoModel = $departamentoModel;
        $this->tramiteModel = $tramiteModel;
    }

    public function indexAction()
    {
        $this->layout()->setTemplate('layout/menuservicios');

        $departamento = $this->departamentoModel->getDepartamentoById($this->departamentoId);
        $tramites = $this->tramiteModel->getTramitesByDepartamento($this->departamentoId);

        return new ViewModel([
            'departamento' => $departamento,
            'tramites' => $tramites
        ]);
    }

    private function getTramiteView($slug)
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug($slug, $this->departamentoId);
        
        if (!$tramite) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel(['tramite' => $tramite]);
    }

    public function capacitacionAction()
    {
        return $this->getTramiteView('capacitacion');
    }

    public function diversidadsexualAction()
    {
        return $this->getTramiteView('diversidadsexual');
    }

    public function lgbtAction()
    {
        return $this->getTramiteView('lgbt');
    }

    public function nacionalizacionAction()
    {
        return $this->getTramiteView('nacionalizacion');
    }

    public function permanenciatransitoriaAction()
    {
        return $this->getTramiteView('permanenciatransitoria');
    }

    public function residenciadefinitivaAction()
    {
        return $this->getTramiteView('residenciadefinitiva');
    }

    public function residenciatemporalAction()
    {
        return $this->getTramiteView('residenciatemporal');
    }

    public function vigenciacedulaAction()
    {
        return $this->getTramiteView('vigenciacedula');
    }
}