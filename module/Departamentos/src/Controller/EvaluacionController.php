<?php

namespace Departamentos\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Departamentos\Model\EvaluacionModel;

class EvaluacionController extends AbstractActionController
{
    protected $evaluacionModel;

    public function __construct(\Departamentos\Model\EvaluacionModel $evaluacionModel)
    {
        $this->evaluacionModel = $evaluacionModel;
    }

    public function indexAction()
    {
        $this->layout()->setTemplate('layout/evaluacion');
        return new ViewModel();
    }

    public function evaluacionAction()
    {
        $this->layout()->setTemplate('layout/menuprincipal');
        return new ViewModel();
    }

    public function guardarAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return new JsonModel(['success' => false, 'message' => 'Método no permitido']);
        }

        $postData = $request->getPost()->toArray();

        $data = [
            'nombre'        => $postData['nombre'] ?? '',
            'correo'        => $postData['correo'] ?? '',
            'telefono'      => $postData['telefono'] ?? '',
            'mensaje'       => $postData['comentario'] ?? '',
            'evaluacion_id' => (int)($postData['rating'] ?? 0),
            'nombreOficina' => $postData['nombreOficina'] ?? '',
        ];

        $ok = $this->evaluacionModel->guardar($data);

        return new JsonModel([
            'success' => $ok,
            'message' => $ok ? 'Evaluación guardada correctamente' : 'Error al guardar evaluación',
        ]);
    }
}
