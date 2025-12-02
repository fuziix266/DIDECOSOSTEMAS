<?php
namespace Radio\Controller\Api;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Db\Adapter\Adapter;

class NewsController extends AbstractRestfulController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getList()
    {
        $sql = 'SELECT * FROM news WHERE is_active = 1 ORDER BY published_date DESC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();
        
        $data = [];
        foreach ($results as $row) {
            $data[] = $row;
        }

        return new JsonModel($data);
    }

    public function get($id)
    {
        $sql = 'SELECT * FROM news WHERE id = ?';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute([$id]);
        $row = $results->current();

        if (!$row) {
            $this->response->setStatusCode(404);
            return new JsonModel(['error' => 'News item not found']);
        }

        return new JsonModel($row);
    }
}
