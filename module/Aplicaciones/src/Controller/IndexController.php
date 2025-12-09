<?php

declare(strict_types=1);

namespace Aplicaciones\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout()->setVariable('heroTitle', 'Inicio');
        $this->layout()->setVariable('heroSubtitle', 'Lista de códigos registradssos.');

        // Usar layout específico para el index
        $this->layout('layout/layout_principal');

        return new ViewModel();
    }
}
