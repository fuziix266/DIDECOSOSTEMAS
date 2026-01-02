<?php

namespace Radio\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout()->setTemplate('layout/radio_public');
        return new ViewModel();
    }
}
