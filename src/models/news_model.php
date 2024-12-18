<?php
require_once realpath(__DIR__ . '/../DBConect.php');

class News {
    private $dbConect;
    private $db;

    public function __construct() {
        $this->dbConect = new Database();
        $this->db = $this->dbConect->getConnection();
    }

    public function getNews() {
        $conn = $this->db;
        // Sprawdź połączenie
        if (!$conn) {
            die("Połączenie z bazą danych jest zamknięte lub nie istnieje.");
        }
        $query = "SELECT news.*, users.U_name FROM news JOIN users ON news.N_creator = users.U_id";
        $result = $conn->query($query);
        $news = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $news[] = $row;
            }
        }
        return $news;
    }
    public function addNews($title, $content, $photoPath, $creatorId) {
        $conn = $this->db;
        $query = "INSERT INTO news (N_title, N_content, N_photo, N_creator) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $conn->error);
        }
        $stmt->bind_param("sssi", $title, $content, $photoPath, $creatorId);
        $stmt->execute();
    }
    public function deleteNews($newsId) {
        $query = "DELETE FROM news WHERE N_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $newsId);
        $stmt->execute();
    }
}
?>
