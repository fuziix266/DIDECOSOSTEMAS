<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout()->setTemplate('layout/menuprincipal');

        return new ViewModel();
    }

    public function radioAction()
    {
        $this->layout()->setTemplate('layout/radio');
        return new ViewModel();
    }

    public function correosAction()
    {
        $this->layout()->setTemplate('layout/correos');

        return new ViewModel();
    }

    public function enviarCorreoAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new \Laminas\View\Model\JsonModel([
                'success' => false,
                'message' => 'Método no permitido'
            ]);
        }

        // Obtener datos del formulario
        $nombre     = $request->getPost('nombre');
        $apellido   = $request->getPost('apellido');
        $email      = $request->getPost('email');
        $telefono   = $request->getPost('telefono');
        $asunto     = $request->getPost('asunto');
        $mensaje    = $request->getPost('mensaje');
        $destino    = $request->getPost('emailenviar');

        // Validación básica
        if (empty($nombre) || empty($apellido) || empty($email) || empty($mensaje) || empty($destino)) {
            return new \Laminas\View\Model\JsonModel([
                'success' => false,
                'message' => 'Faltan campos obligatorios.'
            ]);
        }

        // Armar cuerpo del mensaje
        $contenido = "Nombre: $nombre $apellido\n";
        $contenido .= "Correo: $email\n";
        $contenido .= "Teléfono: $telefono\n\n";
        $contenido .= "Mensaje:\n$mensaje\n";

        $headers  = "From: $nombre $apellido <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        $ok = mail($destino, $asunto, $contenido, $headers);

        if ($ok) {
            return new \Laminas\View\Model\JsonModel([
                'success' => true,
                'message' => 'Correo enviado correctamente'
            ]);
        } else {
            return new \Laminas\View\Model\JsonModel([
                'success' => false,
                'message' => 'No se pudo enviar el correo'
            ]);
        }
    }

    public function emergenciaAction()
    {
        $this->layout()->setTemplate('layout/radio');
        return new ViewModel();
    }
}
