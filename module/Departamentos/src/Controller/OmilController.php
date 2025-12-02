<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class OmilController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 14; // Oficina Municipal de Intermediación Laboral (OMIL)

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

    public function certificadocesantiaAction()
    {
        return $this->getTramiteView('certificadocesantia');
    }

    public function certificadoinscripcionAction()
    {
        return $this->getTramiteView('certificadoinscripcion');
    }

    public function cesantiasolidariaAction()
    {
        return $this->getTramiteView('cesantiasolidaria');
    }

    public function inscripcionAction()
    {
        return $this->getTramiteView('inscripcion');
    }

    public function postulacionAction()
    {
        return $this->getTramiteView('postulacion');
    }

    public function requerimientoAction()
    {
        return $this->getTramiteView('requerimiento');
    }

    public function solicituddiscapacidadAction()
    {
        return $this->getTramiteView('solicituddiscapacidad');
    }

}