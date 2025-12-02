<?php

namespace Departamentos\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class UsuarioModel
{
    private $dbAdapter;
    private $sql;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($this->dbAdapter);
    }

    public function findByEmail(string $email): ?array
    {
        $select = $this->sql->select('usuarios_sistema');
        $select->where(['email' => $email]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $row = $result->current();

        return $row ?: null;
    }

    public function getAll(): array
    {
        $select = $this->sql->select('usuarios_sistema');
        $select->order('fecha_creacion DESC');

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $usuarios = [];
        foreach ($result as $row) {
            $usuarios[] = $row;
        }

        return $usuarios;
    }

    public function getById(int $id): ?array
    {
        $select = $this->sql->select('usuarios_sistema');
        $select->where(['id' => $id]);

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $row = $result->current();

        return $row ?: null;
    }

    public function create(array $data): int
    {
        $insert = $this->sql->insert('usuarios_sistema');
        $insert->values([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'rol' => $data['rol'],
            'activo' => $data['activo'] ?? 1,
            'creado_por' => $data['creado_por'] ?? null,
        ]);

        $statement = $this->sql->prepareStatementForSqlObject($insert);
        $statement->execute();

        return (int) $this->dbAdapter->getDriver()->getLastGeneratedValue();
    }

    public function update(int $id, array $data): bool
    {
        $update = $this->sql->update('usuarios_sistema');
        
        $updateData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'rol' => $data['rol'],
            'activo' => $data['activo'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $update->set($updateData);
        $update->where(['id' => $id]);

        $statement = $this->sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        return $result->getAffectedRows() > 0;
    }

    public function updateLastAccess(int $id): void
    {
        $update = $this->sql->update('usuarios_sistema');
        $update->set(['ultimo_acceso' => date('Y-m-d H:i:s')]);
        $update->where(['id' => $id]);

        $statement = $this->sql->prepareStatementForSqlObject($update);
        $statement->execute();
    }

    public function isEmailUnique(string $email, ?int $excludeId = null): bool
    {
        $select = $this->sql->select('usuarios_sistema');
        $select->where(['email' => $email]);
        
        if ($excludeId !== null) {
            $select->where->notEqualTo('id', $excludeId);
        }

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        return !$result->current();
    }
}
