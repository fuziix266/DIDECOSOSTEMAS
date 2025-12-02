<?php

declare(strict_types=1);

namespace Aplicaciones\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FirmaController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function firmaAction()
    {
        $this->layout()->setTemplate('layout/layoutfirma');
        return new ViewModel();
    }
}
