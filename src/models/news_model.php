<?php
require_once __DIR__ . '/../DBConnect.php';
class News{
    private $db;

    public function  __construct(){
        $this->db = new Database();
    }
    
    public function getNews(){
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM news";
        $result = $conn->query($query);
        $news = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
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
        $conn = $this->db->getConnection();
        $query = "INSERT INTO news (N_title, N_content, N_image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $_POST['title'], $_POST['content'], $_POST['image']);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>