<?php

namespace Radio\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Radio\Service\AuthService;

class AuthController extends AbstractActionController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginAction()
    {
        $this->layout()->setTemplate('layout/radio');

        if ($this->authService->isAuthenticated()) {
            return $this->redirect()->toRoute('radio-admin');
        }

        $error = '';

        if ($this->getRequest()->isPost()) {
            $correo = $this->getRequest()->getPost('correo');
            $password = $this->getRequest()->getPost('password');

            $resultado = $this->authService->login($correo, $password);

            if ($resultado['success']) {
                return $this->redirect()->toRoute('radio-admin');
            }

            $error = $resultado['error'];
        }

        return new ViewModel([
            'error' => $error,
        ]);
    }

    public function logoutAction()
    {
        $this->authService->logout();
        return $this->redirect()->toRoute('radio-auth', ['action' => 'login']);
    }
}
