<?php

namespace Radio\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DashboardController extends AbstractActionController
{
    public function indexAction()
    {
        // Layout is set by Module.php
        return new ViewModel([
            'mensaje' => 'Bienvenido al Panel de Edición',
        ]);
    }
}
