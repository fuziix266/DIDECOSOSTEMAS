<?php
namespace Radio\Controller\Api;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Db\Adapter\Adapter;

class ProgramsController extends AbstractRestfulController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getList()
    {
        // Get all active programs ordered by start time
        $sql = 'SELECT * FROM programs WHERE is_active = 1 ORDER BY start_time ASC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();
        
        $programs = [];
        foreach ($results as $row) {
            $programs[] = $row;
        }

        // Determine "Now Playing"
        $now = date('H:i:s');
        $currentProgram = null;
        
        foreach ($programs as $program) {
            if ($now >= $program['start_time'] && $now <= $program['end_time']) {
                $currentProgram = $program;
                break;
            }
        }

        return new JsonModel([
            'now_playing' => $currentProgram,
            'schedule' => $programs
        ]);
    }

    public function get($id)
    {
        $sql = 'SELECT * FROM programs WHERE id = ?';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute([$id]);
        $row = $results->current();

        if (!$row) {
            $this->response->setStatusCode(404);
            return new JsonModel(['error' => 'Program not found']);
        }

        return new JsonModel($row);
    }
}
