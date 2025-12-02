<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class OcamController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 3; // Servicios para Personas Mayores (OCAM)

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

    public function asesoriasAction()
    {
        return $this->getTramiteView('asesorias');
    }

    public function atencionkinesicaAction()
    {
        return $this->getTramiteView('atencionkinesica');
    }

    public function atencionprofesoresAction()
    {
        return $this->getTramiteView('atencionprofesores');
    }

    public function atencionpsicologicaAction()
    {
        return $this->getTramiteView('atencionpsicologica');
    }

    public function atencionsalonesAction()
    {
        return $this->getTramiteView('atencionsalones');
    }

    public function atencionsocialAction()
    {
        return $this->getTramiteView('atencionsocial');
    }

    public function atencionesocialAction()
    {
        return $this->getTramiteView('atencionsocial');
    }

    public function clasespersonalizadasAction()
    {
        return $this->getTramiteView('clasespersonalizadas');
    }

    public function gestoresenterrenoAction()
    {
        return $this->getTramiteView('gestoresenterreno');
    }
}
