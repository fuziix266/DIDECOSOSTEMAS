<?php

namespace Geoportal\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function mapaAction()
    {
        // Establecer el layout personalizado para esta acción
        $this->layout('layout/layout_mapa');
        return new ViewModel();
    }
}
