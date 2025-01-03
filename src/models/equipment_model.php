<?php
require_once __DIR__ . '/../DBConect.php';

class Equipment {
    private $db;

    public function __construct() {
        $dbConnect = new Database();
        $this->db = $dbConnect->getConnection();
    }

    // Pobiera wszystkie sprzęty
    public function getAllEquipment() {
        $query = "SELECT e.E_id, b.B_name, c.C_Name, e.E_size, e.E_price, e.E_if_rent, e.E_photo
                  FROM equipment e
                  JOIN business b ON e.E_producer = b.B_id
                  JOIN category c ON e.E_category = c.C_id";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateEquipmentStatus($equipmentId, $status) {
        $allowedStatuses = ['Dostępny','Wynajęty','Zarezerwowany'];
        if (!in_array($status, $allowedStatuses)) {
            throw new InvalidArgumentException("Nieprawidłowy status: $status");
        }
    
        $query = "UPDATE equipment SET E_if_rent = ? WHERE E_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $status, $equipmentId);
        $stmt->execute();
    }
    public function addEquipmentToOrder($rentId, $equipmentId) {
        $query = "INSERT INTO entire_order (EO_rent_id, EO_eq_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $rentId, $equipmentId);
        $stmt->execute();
    }
    public function createRental($userId, $totalPrice, $rentalDate) {
        $query = "INSERT INTO rent (R_user_id, R_price, R_date_rental, R_is_completed) VALUES (?, ?, ?, 0)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ids', $userId, $totalPrice, $rentalDate);
        $stmt->execute();
        return $this->db->insert_id; // Return the ID of the newly created rental
    }
    public function rentEquipment($equipmentId) {
        $query = "UPDATE equipment SET E_if_rent = 'Zarezerwowany' WHERE E_id = ? AND E_if_rent = 'Dostępny'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $equipmentId);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Return true if the equipment was successfully rented
    }
    
    // Oblicza całkowitą cenę sprzętów w koszyku
    public function calculateTotalPrice($equipmentIds) {
        $placeholders = implode(',', array_fill(0, count($equipmentIds), '?'));
        $query = "SELECT SUM(E_price) AS total_price FROM equipment WHERE E_id IN ($placeholders)";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $this->db->error);
        }
        $stmt->bind_param(str_repeat('i', count($equipmentIds)), ...$equipmentIds);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total_price'] ?? 0;
    }
}
?>
