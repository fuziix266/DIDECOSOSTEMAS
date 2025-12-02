<?php

namespace Departamentos\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\ResultSet\ResultSet;

class DepartamentoModel
{
    private $dbAdapter;
    private $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
    }

    /**
     * Obtiene un departamento por su slug
     */
    public function getDepartamentoBySlug($slug)
    {
        $select = $this->sql->select('departamentos');
        $select->where(['slug' => $slug, 'activo' => 1]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Obtiene un departamento por su ID
     */
    public function getDepartamentoById($id)
    {
        $select = $this->sql->select('departamentos');
        $select->where(['id' => $id, 'activo' => 1]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Obtiene todos los departamentos activos ordenados
     */
    public function getAllDepartamentos()
    {
        $select = $this->sql->select('departamentos');
        $select->where(['activo' => 1]);
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $departamentos = [];
        foreach ($result as $row) {
            $departamentos[] = $row;
        }
        
        return $departamentos;
    }

    /**
     * Obtiene departamentos con sus trámites usando la vista SQL
     */
    public function getDepartamentosConTramites()
    {
        $select = $this->sql->select('v_departamentos_con_tramites');
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $departamentos = [];
        foreach ($result as $row) {
            $departamentos[] = $row;
        }
        
        return $departamentos;
    }

    /**
     * Obtiene todos los departamentos (incluso inactivos) - para administración
     */
    public function getAll()
    {
        $select = $this->sql->select('departamentos');
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $departamentos = [];
        foreach ($result as $row) {
            $departamentos[] = $row;
        }
        
        return $departamentos;
    }

    /**
     * Obtiene un departamento por ID (sin filtro de activo) - para administración
     */
    public function getById($id)
    {
        $select = $this->sql->select('departamentos');
        $select->where(['id' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Actualiza información de contacto del departamento
     */
    public function updateContactInfo($id, array $data)
    {
        $update = $this->sql->update('departamentos');
        $update->set([
            'email_contacto' => $data['email_contacto'] ?? null,
            'telefono_contacto' => $data['telefono_contacto'] ?? null,
            'horario_atencion' => $data['horario_atencion'] ?? null,
            'direccion' => $data['direccion'] ?? null,
        ]);
        $update->where(['id' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        
        return $result->getAffectedRows() > 0;
    }
}
