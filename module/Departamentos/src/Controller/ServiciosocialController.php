<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class ServiciosocialController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 2; // Servicio Social

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

    public function alimentosAction()
    {
        return $this->getTramiteView('alimentos');
    }

    public function materialesconstruccionAction()
    {
        return $this->getTramiteView('materialesconstruccion');
    }

    public function piezasprefabricadasAction()
    {
        return $this->getTramiteView('piezasprefabricadas');
    }

    public function ayudasaludAction()
    {
        return $this->getTramiteView('ayudasalud');
    }

    public function pasajesAction()
    {
        return $this->getTramiteView('pasajes');
    }

    public function pagoserviciosAction()
    {
        return $this->getTramiteView('pagoservicios');
    }

    public function camionaljibeAction()
    {
        return $this->getTramiteView('camionaljibe');
    }

    public function canonarrendamientoAction()
    {
        return $this->getTramiteView('canonarrendamiento');
    }
}
