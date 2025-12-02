<?php

namespace Departamentos\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class AuditoriaModel
{
    private $dbAdapter;
    private $sql;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($this->dbAdapter);
    }

    public function registrarCambio(array $data): int
    {
        $insert = $this->sql->insert('auditoria_cambios');
        $insert->values([
            'usuario_id' => $data['usuario_id'],
            'tabla_afectada' => $data['tabla'],
            'registro_id' => $data['registro_id'],
            'accion' => $data['accion'],
            'datos_anteriores' => $data['datos_anteriores'] ?? null,
            'datos_nuevos' => $data['datos_nuevos'] ?? null,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $statement = $this->sql->prepareStatementForSqlObject($insert);
        $statement->execute();

        return (int) $this->dbAdapter->getDriver()->getLastGeneratedValue();
    }

    public function getCambiosRecientes(int $limit = 50): array
    {
        $select = $this->sql->select('auditoria_cambios');
        $select->join(
            'usuarios_sistema',
            'auditoria_cambios.usuario_id = usuarios_sistema.id',
            ['usuario_nombre' => 'nombre', 'usuario_email' => 'email']
        );
        $select->order('fecha_cambio DESC');
        $select->limit($limit);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $cambios = [];
        foreach ($result as $row) {
            $cambios[] = $row;
        }

        return $cambios;
    }
}
