<?php

namespace Vehiculos\Service;

use Vehiculos\Repository\QrUsuariosRepository;
use Laminas\Session\Container as SessionContainer;

class AuthService
{
    private QrUsuariosRepository $usuariosRepo;
    private SessionContainer $session;

    public function __construct(QrUsuariosRepository $usuariosRepo)
    {
        $this->usuariosRepo = $usuariosRepo;
        $this->session = new SessionContainer('vehiculos_qr_auth');
    }

    /**
     * Intentar login
     */
    public function login(string $correo, string $password): array
    {
        // DEBUG
        error_log("AuthService::login - Correo: " . $correo);
        
        // Validar dominio
        if (!str_ends_with($correo, '@municipalidadarica.cl')) {
            error_log("AuthService::login - Dominio inválido");
            return ['success' => false, 'error' => 'Solo se permiten correos @municipalidadarica.cl'];
        }
        
        $usuario = $this->usuariosRepo->findByCorreo($correo);
        
        error_log("AuthService::login - Usuario encontrado: " . ($usuario ? 'SI' : 'NO'));
        
        if (!$usuario) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        error_log("AuthService::login - Usuario activo: " . ($usuario['activo'] ?? 'NULL'));
        
        if ($usuario['activo'] != 1) {
            return ['success' => false, 'error' => 'Usuario inactivo'];
        }
        
        $passwordVerify = password_verify($password, $usuario['password_hash']);
        error_log("AuthService::login - Password verify: " . ($passwordVerify ? 'SI' : 'NO'));
        
        if (!$passwordVerify) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }
        
        // Guardar en sesión
        $this->session->user_id = $usuario['id'];
        $this->session->nombre = $usuario['nombre'];
        $this->session->correo = $usuario['correo'];
        $this->session->rol = $usuario['rol'];
        
        error_log("AuthService::login - Login exitoso para: " . $correo);
        
        return [
            'success' => true,
            'usuario' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
                'rol' => $usuario['rol'],
            ]
        ];
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        $this->session->getManager()->destroy();
    }

    /**
     * Verificar si está autenticado
     */
    public function isAuthenticated(): bool
    {
        return isset($this->session->user_id);
    }

    /**
     * Obtener usuario actual
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $this->session->user_id,
            'nombre' => $this->session->nombre,
            'correo' => $this->session->correo,
            'rol' => $this->session->rol,
        ];
    }

    /**
     * Verificar si es admin
     */
    public function isAdmin(): bool
    {
        return $this->isAuthenticated() && $this->session->rol === 'ADMIN';
    }

    /**
     * Verificar si es inspector
     */
    public function isInspector(): bool
    {
        return $this->isAuthenticated() && $this->session->rol === 'INSPECTOR';
    }
}
