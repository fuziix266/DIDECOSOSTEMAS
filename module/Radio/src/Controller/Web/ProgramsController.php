<?php
namespace Radio\Controller\Web;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;

class ProgramsController extends AbstractActionController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function indexAction()
    {
        $sql = 'SELECT * FROM programs ORDER BY start_time ASC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();

        return new ViewModel(['programs' => $results]);
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $title = $data['title'];
            $hostName = $data['host'];
            $startTime = $data['start_time'];
            $endTime = $data['end_time'];
            
            // Image Upload Handling
            $imageUrl = '';
            if (isset($files['image']) && $files['image']['error'] === 0) {
                $uploadDir = 'public/imgs/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($files['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($files['image']['tmp_name'], $targetPath)) {
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'];
                    $imageUrl = "$protocol://$host/radioApp/backend/public/imgs/$fileName";
                }
            }

            $sql = 'INSERT INTO programs (title, host, start_time, end_time, image_url) VALUES (?, ?, ?, ?, ?)';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$title, $hostName, $startTime, $endTime, $imageUrl]);

            return $this->redirect()->toRoute('radio-programs');
        }

        return new ViewModel();
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('radio-programs', ['action' => 'add']);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $title = $data['title'];
            $hostName = $data['host'];
            $startTime = $data['start_time'];
            $endTime = $data['end_time'];

            $sql = 'UPDATE programs SET title = ?, host = ?, start_time = ?, end_time = ? WHERE id = ?';
            $params = [$title, $hostName, $startTime, $endTime, $id];

            // Handle Image Update
            if (isset($files['image']) && $files['image']['error'] === 0) {
                $uploadDir = 'public/imgs/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($files['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($files['image']['tmp_name'], $targetPath)) {
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'];
                    $imageUrl = "$protocol://$host/radioApp/backend/public/imgs/$fileName";
                    
                    $sql = 'UPDATE programs SET title = ?, host = ?, start_time = ?, end_time = ?, image_url = ? WHERE id = ?';
                    $params = [$title, $hostName, $startTime, $endTime, $imageUrl, $id];
                }
            }

            $statement = $this->dbAdapter->query($sql);
            $statement->execute($params);

            return $this->redirect()->toRoute('radio-programs');
        }

        $sql = 'SELECT * FROM programs WHERE id = ?';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute([$id]);
        $program = $results->current();

        if (!$program) {
            return $this->redirect()->toRoute('radio-programs');
        }

        return new ViewModel(['program' => $program]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 !== $id) {
            $sql = 'DELETE FROM programs WHERE id = ?';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$id]);
        }

        return $this->redirect()->toRoute('radio/programs');
    }
}
