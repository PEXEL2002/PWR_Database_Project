<?php
require_once  realpath(__DIR__ . '/../DBConect.php');
class News{
    private $dbConect;
    private $db;
    public function  __construct(){
        $this->dbConect = new Database();
        $this->db = $this->dbConect->getConnection();
    }
    
    public function getNews(){
        $conn = $this->db;

        // Sprawdź połączenie
        if (!$conn) {
            die("Połączenie z bazą danych jest zamknięte lub nie istnieje.");
        }
    
        $query = "SELECT news.*,users.U_name FROM news JOIN USERs ON news.N_creator = users.U_id";
        $result = $conn->query($query);
        $news = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $news[] = $row;
            }
        }
        return $news;
    }

    public function addNews(){
        if(!isset($_POST['title']) || !isset($_POST['content'])){
            return false;
        }
        if(!isset($_POST['image'])){
            $_POST['image'] = '';
        }
        $conn = $this->db;
        $query = "INSERT INTO news (N_title, N_content, N_image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $_POST['title'], $_POST['content'], $_POST['image']);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>