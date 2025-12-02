<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class OlnController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 6; // Oficina Local de la Niñez

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

    private function getTramiteView($tramiteSlug)
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteCompleto($this->departamentoId, $tramiteSlug);
        
        if (!$tramite) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

    public function demandaAction()
    {
        return $this->getTramiteView('demanda');
    }

    public function proteccionuniversalAction()
    {
        return $this->getTramiteView('proteccionuniversal');
    }

    public function proteccionurgenciaAction()
    {
        return $this->getTramiteView('proteccionurgencia');
    }

    public function promocionderechosnnaAction()
    {
        return $this->getTramiteView('promocionderechosnna');
    }

}