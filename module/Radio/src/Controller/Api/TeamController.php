<?php
namespace Radio\Controller\Api;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Db\Adapter\Adapter;

class TeamController extends AbstractRestfulController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getList()
    {
        $sql = 'SELECT * FROM team_members ORDER BY id ASC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();

        $data = [];
        foreach ($results as $row) {
            $data[] = $row;
        }

        return new JsonModel($data);
    }
}
