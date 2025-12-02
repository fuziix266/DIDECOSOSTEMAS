<?php

namespace Radio\Repository;

use Laminas\Db\TableGateway\TableGatewayInterface;

class UserRepository
{
    private TableGatewayInterface $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function findById($id)
    {
        $rowset = $this->tableGateway->select(['id' => $id]);
        return $rowset->current();
    }

    public function findByCorreo($correo)
    {
        $rowset = $this->tableGateway->select(['correo' => $correo]);
        return $rowset->current();
    }

    public function saveUser(array $data)
    {
        $id = (int) ($data['id'] ?? 0);

        $userData = [
            'nombre' => $data['nombre'],
            'correo' => $data['correo'],
            'rol'    => $data['rol'],
            'activo' => $data['activo'] ?? 1,
        ];

        if (!empty($data['password_hash'])) {
            $userData['password_hash'] = $data['password_hash'];
        }

        if ($id === 0) {
            $this->tableGateway->insert($userData);
            return;
        }

        if ($this->findById($id)) {
            $this->tableGateway->update($userData, ['id' => $id]);
        } else {
            throw new \RuntimeException(sprintf(
                'Cannot update user with identifier %d; does not exist',
                $id
            ));
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
