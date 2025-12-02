<?php

namespace Radio\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Radio\Service\AuthService;
use Radio\Repository\UserRepository;

class AdminController extends AbstractActionController
{
    private AuthService $authService;
    private UserRepository $userRepository;

    public function __construct(AuthService $authService, UserRepository $userRepository)
    {
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    public function onDispatch(\Laminas\Mvc\MvcEvent $e)
    {
        if (!$this->authService->isAuthenticated()) {
            return $this->redirect()->toRoute('radio-auth', ['action' => 'login']);
        }
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        return new ViewModel([
            'user' => $this->authService->getCurrentUser()
        ]);
    }

    public function usuariosAction()
    {
        if (!$this->authService->isAdmin()) {
            return $this->redirect()->toRoute('radio-admin');
        }

        $usuarios = $this->userRepository->fetchAll();

        return new ViewModel([
            'usuarios' => $usuarios
        ]);
    }

    public function guardarUsuarioAction()
    {
        if (!$this->authService->isAdmin()) {
            return $this->redirect()->toRoute('radio-admin');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();

            // Si viene password, hashearlo
            if (!empty($data['password'])) {
                $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            try {
                $this->userRepository->saveUser($data);
            } catch (\Exception $e) {
                // Manejar error
            }
        }

        return $this->redirect()->toRoute('radio-admin-usuarios');
    }

    public function eliminarUsuarioAction()
    {
        if (!$this->authService->isAdmin()) {
            return $this->redirect()->toRoute('radio-admin');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $this->userRepository->deleteUser($id);
        }

        return $this->redirect()->toRoute('radio-admin-usuarios');
    }
}
