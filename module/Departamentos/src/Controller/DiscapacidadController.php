<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;

class DiscapacidadController extends AbstractActionController
{
    private $departamentoModel;
    private $tramiteModel;
    private $departamentoId = 11; // Oficina de la Discapacidad

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

    public function terapiafonoaudiologiaAction()
    {
        return $this->getTramiteView('terapiafonoaudiologia');
    }

    public function terapiakinesicaAction()
    {
        return $this->getTramiteView('terapiakinesica');
    }

    public function tallerdeportivoAction()
    {
        return $this->getTramiteView('tallerdeportivo');
    }

    public function ayudatecnicaAction()
    {
        return $this->getTramiteView('ayudatecnica');
    }

    public function devolucionayudatecnicaAction()
    {
        return $this->getTramiteView('devolucionayudatecnica');
    }

    public function informesocialyredesAction()
    {
        return $this->getTramiteView('informesocialyredes');
    }

    public function orasmiAction()
    {
        return $this->getTramiteView('orasmi');
    }

    public function ayudasocialAction()
    {
        return $this->getTramiteView('ayudasocial');
    }

    public function tribunalesAction()
    {
        return $this->getTramiteView('tribunales');
    }

    public function comprasayudastecnicasAction()
    {
        return $this->getTramiteView('comprasayudastecnicas');
    }

    public function subsidiomenoresAction()
    {
        return $this->getTramiteView('subsidiomenores');
    }

    public function tipendioAction()
    {
        return $this->getTramiteView('tipendio');
    }

    public function pensionbasicainvalidezAction()
    {
        return $this->getTramiteView('pensionbasicainvalidez');
    }

    public function beneficiossemestralesAction()
    {
        return $this->getTramiteView('beneficiossemestrales');
    }

    public function ivadecAction()
    {
        return $this->getTramiteView('ivadec');
    }

    public function senadisAction()
    {
        return $this->getTramiteView('senadis');
    }

    public function terapiaocupacionalAction()
    {
        return $this->getTramiteView('terapiaocupacional');
    }


}