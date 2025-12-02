<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class OdimaController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 8; // Servicios para Pueblos Indígenas (ODIMA)

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

    public function acreditacionindigenaAction()
    {
        return $this->getTramiteView('acreditacionindigena');
    }

    public function becaindigenaAction()
    {
        return $this->getTramiteView('becaindigena');
    }

    public function informacionasociacionindigenaAction()
    {
        return $this->getTramiteView('informacionasociacionindigena');
    }

    public function inscripcionasociacionindigenaAction()
    {
        return $this->getTramiteView('inscripcionasociacionindigena');
    }

    public function microemprendimientoAction()
    {
        return $this->getTramiteView('microemprendimiento');
    }

    public function subsidiohabitacionalAction()
    {
        return $this->getTramiteView('subsidiohabitacional');
    }

    public function actividadestematicasAction()
    {
        return $this->getTramiteView('actividadestematicas');
    }

}