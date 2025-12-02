<?php
namespace Radio\Controller\Web;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Adapter\Adapter;

class NewsController extends AbstractActionController
{
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function indexAction()
    {
        $sql = 'SELECT * FROM news ORDER BY published_date DESC';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute();

        return new ViewModel(['news' => $results]);
    }

    public function addAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $title = $data['title'];
            $summary = $data['summary'];
            $content = $data['content'];
            
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

            $sql = 'INSERT INTO news (title, summary, content, image_url, published_date) VALUES (?, ?, ?, ?, ?)';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$title, $summary, $content, $imageUrl, date('Y-m-d H:i:s')]);

            return $this->redirect()->toRoute('radio-news');
        }

        return new ViewModel();
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 === $id) {
            return $this->redirect()->toRoute('radio-news', ['action' => 'add']);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();

            $title = $data['title'];
            $summary = $data['summary'];
            $content = $data['content'];

            $sql = 'UPDATE news SET title = ?, summary = ?, content = ? WHERE id = ?';
            $params = [$title, $summary, $content, $id];

            // Handle Image Update if new file provided
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
                    
                    $sql = 'UPDATE news SET title = ?, summary = ?, content = ?, image_url = ? WHERE id = ?';
                    $params = [$title, $summary, $content, $imageUrl, $id];
                }
            }

            $statement = $this->dbAdapter->query($sql);
            $statement->execute($params);

            return $this->redirect()->toRoute('radio-news');
        }

        $sql = 'SELECT * FROM news WHERE id = ?';
        $statement = $this->dbAdapter->query($sql);
        $results = $statement->execute([$id]);
        $news = $results->current();

        if (!$news) {
            return $this->redirect()->toRoute('radio-news');
        }

        return new ViewModel(['news' => $news]);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (0 !== $id) {
            $sql = 'DELETE FROM news WHERE id = ?';
            $statement = $this->dbAdapter->query($sql);
            $statement->execute([$id]);
        }

        return $this->redirect()->toRoute('radio/news');
    }
}
