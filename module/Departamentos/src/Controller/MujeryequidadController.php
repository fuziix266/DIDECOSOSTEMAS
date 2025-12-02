<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class MujeryequidadController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 7; // Servicios Oficina de la Mujer

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

    public function emprendimientoAction()
    {
        return $this->getTramiteView('emprendimiento');
    }

    public function capacitacionAction()
    {
        return $this->getTramiteView('capacitacion');
    }

    public function pervencionAction()
    {
        return $this->getTramiteView('pervencion');
    }

    public function terapiasAction()
    {
        return $this->getTramiteView('terapias');
    }

    public function escritosAction()
    {
        return $this->getTramiteView('escritos');
    }

    public function orientacionAction()
    {
        return $this->getTramiteView('orientacion');
    }

    public function causasjudicialesAction()
    {
        return $this->getTramiteView('causasjudiciales');
    }

    public function denunciasAction()
    {
        return $this->getTramiteView('denuncias');
    }

    public function autorizacionesAction()
    {
        return $this->getTramiteView('autorizaciones');
    }

    public function festivalAction()
    {
        return $this->getTramiteView('festival');
    }

    public function denunciaAction()
    {
        return $this->getTramiteView('denuncia');
    }

    public function deudorespensionAction()
    {
        return $this->getTramiteView('deudorespension');
    }

}