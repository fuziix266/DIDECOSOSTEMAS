<?php

namespace Departamentos\Controller;

use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DefensoriaciudadanaController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 16; // ID de Defensoría Ciudadana en la BD

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

    public function orientacionlegalAction()
    {
        $this->layout()->setTemplate('layout/servicios');
        
        $tramite = $this->tramiteModel->getTramiteBySlug('orientacionlegal', $this->departamentoId);
        
        if (!$tramite) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'tramite' => $tramite
        ]);
    }

}