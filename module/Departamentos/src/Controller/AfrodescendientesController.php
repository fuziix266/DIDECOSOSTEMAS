<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class AfrodescendientesController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 9; // ID de Afrodescendientes en la BD

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

    public function organizacionesAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug('organizaciones', $this->departamentoId);
        
        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

    public function emprendimientoAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug('emprendimiento', $this->departamentoId);
        
        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

    public function catastroAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug('catastro', $this->departamentoId);
        
        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

    public function talleresAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug('talleres', $this->departamentoId);
        
        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

    public function capacitacionAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        return new ViewModel();
    }

    

}