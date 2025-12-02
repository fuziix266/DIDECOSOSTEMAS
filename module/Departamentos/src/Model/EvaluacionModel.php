<?php

namespace Departamentos\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;

class EvaluacionModel
{
    protected $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    // Insertar una nueva respuesta
    public function guardar(array $data): bool
    {
        $sql = new Sql($this->adapter);
        $insert = $sql->insert('respuestas');
        $insert->values([
            'nombre'        => $data['nombre'],
            'telefono'      => $data['telefono'],
            'correo'        => $data['correo'],
            'mensaje'       => $data['mensaje'],
            'evaluacion_id' => $data['evaluacion_id'],
            'oficina' => $data['nombreOficina'],
        ]);

        try {
            $statement = $sql->prepareStatementForSqlObject($insert);
            $statement->execute();
            return true;
        } catch (\Exception $e) {
            // log o manejo de error si se requiere
            return false;
        }
    }

    // Obtener todas las respuestas con descripción de evaluación
    public function obtenerTodas(): array
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(['r' => 'respuestas'])
            ->columns(['id', 'nombre', 'telefono', 'correo', 'mensaje', 'fecha'])
            ->join(['e' => 'evaluaciones'], 'r.evaluacion_id = e.id', ['evaluacion' => 'descripcion'], 'left')
            ->order('r.fecha DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $datos = [];
        foreach ($result as $row) {
            $datos[] = $row;
        }

        return $datos;
    }
}
