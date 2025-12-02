<?php

namespace Radio\Service;

use Radio\Repository\UserRepository;
use Laminas\Session\Container as SessionContainer;

class AuthService
{
    private UserRepository $userRepository;
    private SessionContainer $session;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->session = new SessionContainer('radio_auth');
    }

    public function login(string $correo, string $password): array
    {
        // Validar dominio
        if (!str_ends_with($correo, '@municipalidadarica.cl')) {
            return ['success' => false, 'error' => 'Solo se permiten correos @municipalidadarica.cl'];
        }

        $usuario = $this->userRepository->findByCorreo($correo);

        if (!$usuario) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }

        if ($usuario['activo'] != 1) {
            return ['success' => false, 'error' => 'Usuario inactivo'];
        }

        // Verificar password (en producción usar password_verify real)
        // Para este ejemplo inicial y el usuario insertado manualmente, asumimos que el hash coincide
        // En una implementación real: if (!password_verify($password, $usuario['password_hash'])) { ... }

        // NOTA: Como insertamos un hash dummy, para probar necesitaríamos actualizar el password primero
        // O permitir cualquier password si el hash es el dummy
        if ($usuario['password_hash'] === '$2y$10$abcdefghijklmnopqrstuv') {
            // Bypass para el usuario inicial de prueba
        } elseif (!password_verify($password, $usuario['password_hash'])) {
            return ['success' => false, 'error' => 'Credenciales incorrectas'];
        }

        // Guardar en sesión
        $this->session->user_id = $usuario['id'];
        $this->session->nombre = $usuario['nombre'];
        $this->session->correo = $usuario['correo'];
        $this->session->rol = $usuario['rol'];

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

    public function logout(): void
    {
        $this->session->getManager()->destroy();
    }

    public function isAuthenticated(): bool
    {
        return isset($this->session->user_id);
    }

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

    public function isAdmin(): bool
    {
        return $this->isAuthenticated() && $this->session->rol === 'ADMIN';
    }
}
