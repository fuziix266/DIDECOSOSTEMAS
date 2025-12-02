<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class EnlacenorteController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 1; // Enlace Norte

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

    //001
    public function subsidiofamiliarAction()
    {
        return $this->getTramiteView('subsidiofamiliar');
    }

    //002
    public function subsidiomaternalAction()
    {
        return $this->getTramiteView('subsidiomaternal');
    }

    //003
    public function subsidioreciennacidoAction()
    {
        return $this->getTramiteView('subsidioreciennacido');
    }

    //004
    public function subsidiocesantiamenoresAction()
    {
        return $this->getTramiteView('subsidiocesantiamenores');
    }

    //005
    public function subsidiofamiliarduploAction()
    {
        return $this->getTramiteView('subsidiofamiliarduplo');
    }

    //006
    public function subsidiomadreAction()
    {
        return $this->getTramiteView('subsidiomadre');
    }

    //007
    public function pensionesAction()
    {
        return $this->getTramiteView('pensiones');
    }

    //008
    public function pensionuniversalAction()
    {
        return $this->getTramiteView('pensionuniversal');
    }

    //009
    public function pensioninvalidezAction()
    {
        return $this->getTramiteView('pensioninvalidez');
    }

    //010
    public function bonoporhijoAction()
    {
        return $this->getTramiteView('bonoporhijo');
    }

    //011
    public function juntasvecinalesAction()
    {
        return $this->getTramiteView('juntasvecinales');
    }

    //012
    public function organizacionesfuncionalesAction()
    {
        return $this->getTramiteView('organizacionesfuncionales');
    }

    //013
    public function territorialesAction()
    {
        return $this->getTramiteView('territoriales');
    }

    //014
    public function aporteinvalidezAction()
    {
        return $this->getTramiteView('aporteinvalidez');
    }

    //015
    public function emergenciasAction()
    {
        return $this->getTramiteView('emergencias');
    }

    //016
    public function comodatoAction()
    {
        return $this->getTramiteView('comodato');
    }

    //017
    public function sapruralAction()
    {
        return $this->getTramiteView('saprural');
    }

    //018
    public function sapurbanoAction()
    {
        return $this->getTramiteView('sapurbano');
    }

    //019
    public function subsidioaguaAction()
    {
        return $this->getTramiteView('subsidioagua');
    }

    //020
    public function subsidiodiscapacidadAction()
    {
        return $this->getTramiteView('subsidiodiscapacidad');
    }

    //021
    public function orientacionlegalAction()
    {
        return $this->getTramiteView('orientacionlegal');
    }
}