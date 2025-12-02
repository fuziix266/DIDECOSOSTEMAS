<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class GestionhabitacionalController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 12; // Gestión Habitacional

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

    public function adultomayorAction()
    {
        return $this->getTramiteView('adultomayor');
    }

    public function constitucioncomiteAction()
    {
        return $this->getTramiteView('constitucioncomite');
    }
    
    public function agrupacionculturalAction()
    {
        return $this->getTramiteView('agrupacioncltural');
    }
    
    public function agrupaciondeportivaAction()
    {
        return $this->getTramiteView('agrupaciondeportiva');
    }
    
    public function clubadultomayorAction()
    {
        return $this->getTramiteView('clubadultomayor');
    }

    public function comiteviviendaAction()
    {
        return $this->getTramiteView('comitevivienda');
    }

    public function fundacionAction()
    {
        return $this->getTramiteView('fundacion');
    }

    public function juntavecinalAction()
    {
        return $this->getTramiteView('juntavecinal');
    }

    public function ongAction()
    {
        return $this->getTramiteView('ong');
    }

    public function padresyapoderadosAction()
    {
        return $this->getTramiteView('padresyapoderados');
    }
    
}