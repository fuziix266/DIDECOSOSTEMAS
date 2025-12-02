<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class SubsidioypensionesController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 5; // Subsidios y Pensiones

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

    public function pensionesAction()
    {
        return $this->getTramiteView('pensiones');
    }

    public function aguapotableAction()
    {
        return $this->getTramiteView('aguapotable');
    }

    public function sapruralAction()
    {
        return $this->getTramiteView('saprural');
    }

    public function sapurbanoAction()
    {
        return $this->getTramiteView('sapurbano');
    }

    public function reciennacidoAction()
    {
        return $this->getTramiteView('reciennacido');
    }

    public function maternalAction()
    {
        return $this->getTramiteView('maternal');
    }

    public function bonoporhijoAction()
    {
        return $this->getTramiteView('bonoporhijo');
    }

    public function discapacidadAction()
    {
        return $this->getTramiteView('discapacidad');
    }

    public function apsiAction()
    {
        return $this->getTramiteView('apsi');
    }

    public function pbsiAction()
    {
        return $this->getTramiteView('pbsi');
    }

    public function pensioninvalidezAction()
    {
        return $this->getTramiteView('pensioninvalidez');
    }

    public function familiarduploAction()
    {
        return $this->getTramiteView('familiarduplo');
    }

    public function madreAction()
    {
        return $this->getTramiteView('madre');
    }

    public function menoresAction()
    {
        return $this->getTramiteView('menores');
    }

    public function pguAction()
    {
        return $this->getTramiteView('pgu');
    }

    public function sufAction()
    {
        return $this->getTramiteView('suf');
    }
}
