<?php

namespace Departamentos\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;

class TramiteModel
{
    private $dbAdapter;
    private $sql;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->sql = new Sql($dbAdapter);
    }

    /**
     * Obtiene un trámite por su slug y departamento
     */
    public function getTramiteBySlug($slug, $departamentoId)
    {
        $select = $this->sql->select('tramites');
        $select->where([
            'slug' => $slug,
            'departamento_id' => $departamentoId,
            'activo' => 1
        ]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Obtiene todos los trámites de un departamento
     */
    public function getTramitesByDepartamento($departamentoId)
    {
        $select = $this->sql->select('tramites');
        $select->where([
            'departamento_id' => $departamentoId,
            'activo' => 1
        ]);
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $tramites = [];
        foreach ($result as $row) {
            $tramites[] = $row;
        }
        
        return $tramites;
    }

    /**
     * Obtiene información completa de un trámite usando departamento_id y slug
     */
    public function getTramiteCompleto($departamentoId, $tramiteSlug)
    {
        $select = $this->sql->select('tramites');
        $select->where([
            'departamento_id' => $departamentoId,
            'slug' => $tramiteSlug
        ]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Obtiene todos los trámites completos de un departamento
     */
    public function getTramitesCompletosByDepartamento($departamentoId)
    {
        $select = $this->sql->select('v_tramites_completos');
        $select->where(['departamento_id' => $departamentoId]);
        $select->order('tramite_orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $tramites = [];
        foreach ($result as $row) {
            $tramites[] = $row;
        }
        
        return $tramites;
    }

    /**
     * Obtiene los documentos de un trámite
     */
    public function getDocumentosByTramite($tramiteId)
    {
        $select = $this->sql->select('documentos_tramite');
        $select->where(['tramite_id' => $tramiteId]);
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $documentos = [];
        foreach ($result as $row) {
            $documentos[] = $row;
        }
        
        return $documentos;
    }

    /**
     * Obtiene los requisitos de un trámite
     */
    public function getRequisitosByTramite($tramiteId)
    {
        $select = $this->sql->select('requisitos_tramite');
        $select->where(['tramite_id' => $tramiteId]);
        $select->order('orden ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $requisitos = [];
        foreach ($result as $row) {
            $requisitos[] = $row;
        }
        
        return $requisitos;
    }

    /**
     * Obtiene los pasos de un trámite
     */
    public function getPasosByTramite($tramiteId)
    {
        $select = $this->sql->select('pasos_tramite');
        $select->where(['tramite_id' => $tramiteId]);
        $select->order('numero_paso ASC');
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $pasos = [];
        foreach ($result as $row) {
            $pasos[] = $row;
        }
        
        return $pasos;
    }

    /**
     * Obtiene todos los trámites - para administración
     */
    public function getAllTramites()
    {
        $select = $this->sql->select('tramites');
        $select->join(
            'departamentos',
            'tramites.departamento_id = departamentos.id',
            ['departamento_nombre' => 'nombre']
        );
        $select->order(['departamentos.orden' => 'ASC', 'tramites.orden' => 'ASC']);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        $tramites = [];
        foreach ($result as $row) {
            $tramites[] = $row;
        }
        
        return $tramites;
    }

    /**
     * Obtiene un trámite por ID - para administración
     */
    public function getTramiteById($id)
    {
        $select = $this->sql->select('tramites');
        $select->where(['id' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        
        return $result->current();
    }

    /**
     * Actualiza un trámite
     */
    public function updateTramite($id, array $data)
    {
        $update = $this->sql->update('tramites');
        $update->set($data);
        $update->where(['id' => $id]);
        
        $statement = $this->sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();
        
        return $result->getAffectedRows() > 0;
    }

    /**
     * Alias para updateTramite
     */
    public function update($id, array $data)
    {
        return $this->updateTramite($id, $data);
    }

    /**
     * Alias para getTramiteById
     */
    public function getById($id)
    {
        return $this->getTramiteById($id);
    }

    /**
     * Obtiene trámites por departamento (alias)
     */
    public function getByDepartamento($departamentoId)
    {
        return $this->getTramitesByDepartamento($departamentoId);
    }

    /**
     * Cuenta todos los trámites
     */
    public function countAll()
    {
        $select = $this->sql->select('tramites');
        $select->columns(['total' => new \Laminas\Db\Sql\Expression('COUNT(*)')]);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $row = $result->current();
        
        return $row ? (int) $row['total'] : 0;
    }
}
