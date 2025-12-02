<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Departamentos\Model\UsuarioModel;
use Departamentos\Model\AuditoriaModel;
use Departamentos\Model\DepartamentoModel;
use Departamentos\Model\TramiteModel;
use Laminas\Session\Container as SessionContainer;

class MantencionController extends AbstractActionController
{
    private $usuarioModel;
    private $auditoriaModel;
    private $departamentoModel;
    private $tramiteModel;
    private $session;

    public function __construct(
        UsuarioModel $usuarioModel,
        AuditoriaModel $auditoriaModel,
        DepartamentoModel $departamentoModel,
        TramiteModel $tramiteModel
    ) {
        $this->usuarioModel = $usuarioModel;
        $this->auditoriaModel = $auditoriaModel;
        $this->departamentoModel = $departamentoModel;
        $this->tramiteModel = $tramiteModel;
        $this->session = new SessionContainer('mantencion_auth');
    }

    /**
     * Verificar autenticación
     */
    private function requireAuth()
    {
        if (!isset($this->session->user_id)) {
            return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'login']);
        }
        return null;
    }

    /**
     * Verificar rol de administrador
     */
    private function requireAdmin()
    {
        $redirect = $this->requireAuth();
        if ($redirect) return $redirect;

        if ($this->session->rol !== 'administrador') {
            return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'dashboard']);
        }
        return null;
    }

    /**
     * Página de login
     */
    public function loginAction()
    {
        // Si ya está autenticado, redirigir
        if (isset($this->session->user_id)) {
            return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'dashboard']);
        }

        $error = null;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            // Validar dominio
            if (!str_ends_with($email, '@municipalidadarica.cl')) {
                $error = 'Solo se permiten correos @municipalidadarica.cl';
            } else {
                $usuario = $this->usuarioModel->findByEmail($email);

                if (!$usuario) {
                    $error = 'Credenciales incorrectas';
                } elseif ($usuario['activo'] != 1) {
                    $error = 'Usuario inactivo';
                } elseif (!password_verify($password, $usuario['password'])) {
                    $error = 'Credenciales incorrectas';
                } else {
                    // Login exitoso
                    $this->session->user_id = $usuario['id'];
                    $this->session->nombre = $usuario['nombre'];
                    $this->session->email = $usuario['email'];
                    $this->session->rol = $usuario['rol'];

                    // Actualizar último acceso
                    $this->usuarioModel->updateLastAccess($usuario['id']);

                    return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'dashboard']);
                }
            }
        }

        return new ViewModel(['error' => $error]);
    }

    /**
     * Cerrar sesión
     */
    public function logoutAction()
    {
        $this->session->getManager()->destroy();
        return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'login']);
    }

    /**
     * Dashboard principal
     */
    public function dashboardAction()
    {
        $redirect = $this->requireAuth();
        if ($redirect) return $redirect;

        $usuario = [
            'id' => $this->session->user_id,
            'nombre' => $this->session->nombre,
            'email' => $this->session->email,
            'rol' => $this->session->rol,
        ];

        $departamentos = $this->departamentoModel->getAll();
        $totalTramites = $this->tramiteModel->countAll();

        return new ViewModel([
            'usuario' => $usuario,
            'departamentos' => $departamentos,
            'totalTramites' => $totalTramites,
        ]);
    }

    /**
     * Listar usuarios (solo admin)
     */
    public function usuariosAction()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;

        $usuarios = $this->usuarioModel->getAll();

        return new ViewModel([
            'usuario' => [
                'id' => $this->session->user_id,
                'nombre' => $this->session->nombre,
                'email' => $this->session->email,
                'rol' => $this->session->rol,
            ],
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Agregar usuario (solo admin)
     */
    public function agregarusuarioAction()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;

        $error = null;
        $success = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            // Validaciones
            if (!str_ends_with($data['email'], '@municipalidadarica.cl')) {
                $error = 'Solo se permiten correos @municipalidadarica.cl';
            } elseif (!$this->usuarioModel->isEmailUnique($data['email'])) {
                $error = 'El email ya está registrado';
            } elseif (strlen($data['password']) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $userId = $this->usuarioModel->create([
                    'nombre' => $data['nombre'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'rol' => $data['rol'],
                    'activo' => 1,
                    'creado_por' => $this->session->user_id,
                ]);

                // Registrar en auditoría
                $this->auditoriaModel->registrarCambio([
                    'usuario_id' => $this->session->user_id,
                    'tabla' => 'usuarios_sistema',
                    'registro_id' => $userId,
                    'accion' => 'crear',
                    'datos_nuevos' => json_encode(['nombre' => $data['nombre'], 'email' => $data['email'], 'rol' => $data['rol']]),
                ]);

                $success = true;
            }
        }

        return new ViewModel([
            'usuario' => [
                'id' => $this->session->user_id,
                'nombre' => $this->session->nombre,
                'email' => $this->session->email,
                'rol' => $this->session->rol,
            ],
            'error' => $error,
            'success' => $success,
        ]);
    }

    /**
     * Editar usuario (solo admin)
     */
    public function editarusuarioAction()
    {
        $redirect = $this->requireAdmin();
        if ($redirect) return $redirect;

        $id = (int) $this->params()->fromRoute('id', 0);
        $usuarioEditar = $this->usuarioModel->getById($id);

        if (!$usuarioEditar) {
            return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'usuarios']);
        }

        $error = null;
        $success = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            if (!str_ends_with($data['email'], '@municipalidadarica.cl')) {
                $error = 'Solo se permiten correos @municipalidadarica.cl';
            } elseif (!$this->usuarioModel->isEmailUnique($data['email'], $id)) {
                $error = 'El email ya está registrado';
            } else {
                $datosAnteriores = json_encode($usuarioEditar);

                $updateData = [
                    'nombre' => $data['nombre'],
                    'email' => $data['email'],
                    'rol' => $data['rol'],
                    'activo' => $data['activo'],
                ];

                if (!empty($data['password'])) {
                    $updateData['password'] = $data['password'];
                }

                $this->usuarioModel->update($id, $updateData);

                // Auditoría
                $this->auditoriaModel->registrarCambio([
                    'usuario_id' => $this->session->user_id,
                    'tabla' => 'usuarios_sistema',
                    'registro_id' => $id,
                    'accion' => 'editar',
                    'datos_anteriores' => $datosAnteriores,
                    'datos_nuevos' => json_encode($updateData),
                ]);

                $success = true;
                $usuarioEditar = $this->usuarioModel->getById($id);
            }
        }

        return new ViewModel([
            'usuario' => [
                'id' => $this->session->user_id,
                'nombre' => $this->session->nombre,
                'email' => $this->session->email,
                'rol' => $this->session->rol,
            ],
            'usuarioEditar' => $usuarioEditar,
            'error' => $error,
            'success' => $success,
        ]);
    }

    /**
     * Listar trámites para edición
     */
    public function tramitesAction()
    {
        $redirect = $this->requireAuth();
        if ($redirect) return $redirect;

        $departamentos = $this->departamentoModel->getAll();
        $departamentoId = (int) $this->params()->fromQuery('departamento', 0);

        $tramites = [];
        if ($departamentoId > 0) {
            $tramites = $this->tramiteModel->getByDepartamento($departamentoId);
        }

        return new ViewModel([
            'usuario' => [
                'id' => $this->session->user_id,
                'nombre' => $this->session->nombre,
                'email' => $this->session->email,
                'rol' => $this->session->rol,
            ],
            'departamentos' => $departamentos,
            'tramites' => $tramites,
            'departamentoId' => $departamentoId,
        ]);
    }

    /**
     * Editar trámite
     */
    public function editartramiteAction()
    {
        $redirect = $this->requireAuth();
        if ($redirect) return $redirect;

        $id = (int) $this->params()->fromRoute('id', 0);
        $tramite = $this->tramiteModel->getById($id);

        if (!$tramite) {
            return $this->redirect()->toRoute('departamentos-mantencion', ['action' => 'tramites']);
        }

        $error = null;
        $success = false;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $datosAnteriores = json_encode($tramite);

            $updateData = [
                'nombre' => $data['nombre'],
                'documentos_requeridos' => $data['documentos_requeridos'],
                'requisitos_usuario' => $data['requisitos_usuario'],
                'instrucciones_paso_paso' => $data['instrucciones_paso_paso'],
                'tiempo_estimado' => $data['tiempo_estimado'],
                'responsable_nombre' => $data['responsable_nombre'],
                'observaciones' => $data['observaciones'] ?? '',
            ];

            $this->tramiteModel->update($id, $updateData);

            // Auditoría
            $this->auditoriaModel->registrarCambio([
                'usuario_id' => $this->session->user_id,
                'tabla' => 'tramites',
                'registro_id' => $id,
                'accion' => 'editar',
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => json_encode($updateData),
            ]);

            $success = true;
            $tramite = $this->tramiteModel->getById($id);
        }

        return new ViewModel([
            'usuario' => [
                'id' => $this->session->user_id,
                'nombre' => $this->session->nombre,
                'email' => $this->session->email,
                'rol' => $this->session->rol,
            ],
            'tramite' => $tramite,
            'error' => $error,
            'success' => $success,
        ]);
    }
}
