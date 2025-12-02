<?php
namespace Radio\Controller\Web;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;

class TeamController extends AbstractActionController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function indexAction()
    {
        $sql = 'SELECT * FROM team_members ORDER BY id ASC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();

        return new ViewModel(['team' => $results]);
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $name = $data['name'];
            $role = $data['role'];
            $bio = $data['bio'];
            
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

            $sql = 'INSERT INTO team_members (name, role, bio, image_url) VALUES (?, ?, ?, ?)';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$name, $role, $bio, $imageUrl]);

            return $this->redirect()->toRoute('radio-team');
        }

        return new ViewModel();
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('radio-team', ['action' => 'add']);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $name = $data['name'];
            $role = $data['role'];
            $bio = $data['bio'];

            $sql = 'UPDATE team_members SET name = ?, role = ?, bio = ? WHERE id = ?';
            $params = [$name, $role, $bio, $id];

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
                    
                    $sql = 'UPDATE team_members SET name = ?, role = ?, bio = ?, image_url = ? WHERE id = ?';
                    $params = [$name, $role, $bio, $imageUrl, $id];
                }
            }

            $statement = $this->dbAdapter->query($sql);
            $statement->execute($params);

            return $this->redirect()->toRoute('radio-team');
        }

        $sql = 'SELECT * FROM team_members WHERE id = ?';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute([$id]);
        $member = $results->current();

        if (!$member) {
            return $this->redirect()->toRoute('radio-team');
        }

        return new ViewModel(['member' => $member]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 !== $id) {
            $sql = 'DELETE FROM team_members WHERE id = ?';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$id]);
        }

        return $this->redirect()->toRoute('radio/team');
    }
}
