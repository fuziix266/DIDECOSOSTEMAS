<?php

namespace Vehiculos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Vehiculos\Service\AuthService;

class AuthController extends AbstractActionController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login de inspectores y administradores
     */
    public function loginAction()
    {
        $this->layout()->setVariable('visible-hero', 0);

        // Si ya está autenticado, redirigir
        if ($this->authService->isAuthenticated()) {
            if ($this->authService->isAdmin()) {
                return $this->redirect()->toRoute('vehiculos-admin');
            }
            return $this->redirect()->toRoute('home');
        }

        $error = '';
        $redirect = $this->params()->fromQuery('redirect', '');

        if ($this->getRequest()->isPost()) {
            $correo = $this->getRequest()->getPost('correo');
            $password = $this->getRequest()->getPost('password');

            // DEBUG: Registrar intento de login
            error_log("DEBUG LOGIN - Correo: " . $correo);
            error_log("DEBUG LOGIN - Password length: " . strlen($password));

            $resultado = $this->authService->login($correo, $password);

            // DEBUG: Registrar resultado
            error_log("DEBUG LOGIN - Resultado: " . json_encode($resultado));

            if ($resultado['success']) {
                // Redirigir según parámetro o según rol
                if ($redirect) {
                    return $this->redirect()->toUrl($redirect);
                }

                if ($resultado['usuario']['rol'] === 'ADMIN') {
                    return $this->redirect()->toRoute('vehiculos-admin');
                }
                return $this->redirect()->toRoute('vehiculos');
            }

            $error = $resultado['error'];
        }

        $view = new ViewModel([
            'error' => $error,
            'redirect' => $redirect,
        ]);
        $view->setTemplate('vehiculos/auth/login');
        return $view;
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        $this->authService->logout();
        return $this->redirect()->toRoute('vehiculos-login');
    }
}
