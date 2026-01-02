<?php

namespace Application\Model;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\PredicateSet;

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
     * Busca trámites por nombre o descripción
     * 
     * @param string $query Término de búsqueda
     * @return array Array de trámites encontrados
     */
    public function searchTramites($query)
    {
        if (empty($query) || strlen(trim($query)) < 2) {
            return [];
        }

        $query = trim($query);
        $searchTerm = '%' . $query . '%';

        $select = $this->sql->select('tramites');
        $select->join(
            'departamentos',
            'tramites.departamento_id = departamentos.id',
            ['departamento_nombre' => 'nombre', 'departamento_slug' => 'slug']
        );

        // Buscar en nombre y descripción corta
        $where = new PredicateSet([
            new Like('tramites.nombre', $searchTerm),
            new Like('tramites.descripcion_corta', $searchTerm)
        ], PredicateSet::OP_OR);

        $select->where($where);
        $select->where(['tramites.activo' => 1, 'departamentos.activo' => 1]);
        $select->order('tramites.nombre ASC');
        $select->limit(10); // Limitar resultados

        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();

        $tramites = [];
        foreach ($result as $row) {
            $tramites[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'slug' => $row['slug'],
                'descripcion_corta' => $row['descripcion_corta'],
                'departamento_nombre' => $row['departamento_nombre'],
                'departamento_slug' => $row['departamento_slug'],
                'url' => '/didecosistemas/public/departamentos/' . $row['departamento_slug'] . '/' . $row['slug']
            ];
        }

        return $tramites;
    }
}
