<?php
require_once __DIR__ . '/../DBConect.php';
require_once __DIR__ . '/user_model.php';

/**
 * Admin model
 * This class contains methods for admin management
 */
class Admin extends User{
    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all users
     * @return array - All users
     */
    public function getUsers() {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM users";
        $result = $conn->query($query);
        $users = $result->fetch_all(MYSQLI_ASSOC);
        return $users;
    }
    public function addNews($title, $content){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO news (N_title, N_content, N_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $_POST['title'], $_POST['content'], date('Y-m-d'));
        $stmt->execute();
        return true;
    }
    public function deleteNews($id){
        $conn = $this->db->getConnection();
        $query = "DELETE FROM news WHERE N_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }
    public function editNews($id, $title, $content){
        $conn = $this->db->getConnection();
        $query = "UPDATE news SET N_title = ?, N_content = ? WHERE N_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssi", $title, $content, $id);
        $stmt->execute();
        return true;
    }
    // Obsługa przesyłania formularza zmiany cen serwisu oraz sprzętu
    public function editPrice($equipmentName, $newPrice) {
        $conn = $this->db->getConnection();
        $newPrice = floatval($newPrice);
        $query = "UPDATE Rent_Price rp
                  JOIN Category c ON rp.RP_category = c.C_id
                  SET rp.RP_price_of_eq = ?
                  WHERE c.C_Name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ds", $newPrice, $equipmentName);
        $success = $stmt->execute();
        return true;
    }
    
    public function getPrice(){
        $conn = $this->db->getConnection();
        $query = "SELECT c.C_Name AS Equipment_Name, rp.RP_price_of_eq AS Rent_Price FROM  Category c JOIN  Rent_Price rp ON c.C_id = rp.RP_category;";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $price = $result->fetch_all();
        return $price;
    }
    public function  getServicePrices(){
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM service_price";
        $result = $conn->query($query);
        $price = $result->fetch_all(MYSQLI_ASSOC);
        return $price;
    }
    public function addNewSerwicePrice($name, $price){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO service_price (S_name, S_price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sd", $name, $price);
        $stmt->execute();
        return true;
    }
    public function editServicePriceByName($serviceName, $newPrice){
        $conn = $this->db->getConnection();
        $query = "UPDATE Service_Price SET SP_price = ? WHERE SP_service = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ds", $newPrice, $serviceName);
        $success = $stmt->execute();
        return true;
    }
    /* fukcje do obsługi serwisu */
    /**
     * Pobranie listy operacji serwisowych
     * @return array
     */
    public function getServiceOperations(){
        $conn = $this->db->getConnection();
        $query = "SELECT SP_id, SP_service, SP_price FROM service_price";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Pobranie listy rekordów serwisowych
     * @return array
     */
    public function getServiceRecords(){
        $conn = $this->db->getConnection();
        $query = "SELECT 
                    s.S_id, 
                    u.U_name, 
                    u.U_surname, 
                    sp.SP_service, 
                    sp.SP_price, 
                    s.S_date_in, 
                    s.S_status 
                  FROM service s
                  JOIN users u ON s.S_user = u.U_id
                  JOIN service_price sp ON s.S_price = sp.SP_id
                  WHERE s.S_status != 'Wydany'";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Dodanie nowego zgłoszenia serwisowego
     * @param int $userId
     * @param int $operationId
     * @return void
     */
    public function addServiceRecord($userId, $operationId){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO service (S_user, S_price, S_date_in, S_status, S_operation) 
                  VALUES (?, ?, ?, 'Przyjęty', ?)";

        $stmt = $conn->prepare($query);
        $date = date('Y-m-d');
        $stmt->bind_param("iisi", $userId, $operationId, $date, $operationId);
        $stmt->execute();
    }
    /**
     * Oznaczenie rekordu serwisowego jako wydany
     * @param int $serviceId
     * @return void
     */
    public function markServiceAsCompleted($serviceId){
        $conn = $this->db->getConnection();
        $query = "UPDATE service SET S_status = 'Wydany', S_date_out = ? WHERE S_id = ?";
        $stmt = $conn->prepare($query);
        $date = date('Y-m-d');
        $stmt->bind_param("si", $date, $serviceId);
        $stmt->execute();
    }
    /**
     * Pobranie danych o sprzęcie
     * @return array
     */
    public function getEquipmentList(){
        $conn = $this->db->getConnection();
        $query = "SELECT E_id, E_producer, E_category, E_price, E_if_rent FROM equipment";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /**
     * Aktualizacja ceny sprzętu
     * @param int $equipmentId
     * @param float $newPrice
     * @return void
     */
    public function updateEquipmentPrice($equipmentId, $newPrice){
        $conn = $this->db->getConnection();
        $query = "UPDATE equipment SET E_price = ? WHERE E_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("di", $newPrice, $equipmentId);
        $stmt->execute();
    }
    // Obsługa przesyłania formularza dodania firmy
    public function addBusiness($name, $contact, $nip, $photoPath) {
        $conn = $this->db->getConnection();
        $query = "INSERT INTO business (B_name, B_contact, B_nip, B_photo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $contact, $nip, $photoPath);
        $stmt->execute();
    }
    public function getAllBusinesses() {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM business";
        $result = $conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function editBusiness($id, $name, $contact, $nip, $photoPath = null) {
        $conn = $this->db->getConnection();
    
        if ($photoPath) {
            $query = "UPDATE business SET B_name = ?, B_contact = ?, B_nip = ?, B_photo = ? WHERE B_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $name, $contact, $nip, $photoPath, $id);
        } else {
            $query = "UPDATE business SET B_name = ?, B_contact = ?, B_nip = ? WHERE B_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $name, $contact, $nip, $id);
        }
    
        $stmt->execute();
    }
    public function deleteBusiness($id) {
        $conn = $this->db->getConnection();
    
        // Pobierz ścieżkę do zdjęcia
        $query = "SELECT B_photo FROM business WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $business = $result->fetch_assoc();
    
        if ($business && file_exists(__DIR__ . "/../../assets/businessPhoto/" . $business['B_photo'])) {
            unlink(__DIR__ . "/../../assets/businessPhoto/" . $business['B_photo']); // Usuń zdjęcie
        }
    
        // Usuń firmę z bazy danych
        $query = "DELETE FROM business WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    public function getBusinessById($id) {
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM business WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function isNipUnique($nip, $excludeId = null) {
        $conn = $this->db->getConnection();
    
        if ($excludeId) {
            $query = "SELECT COUNT(*) AS count FROM business WHERE B_nip = ? AND B_id != ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $nip, $excludeId);
        } else {
            $query = "SELECT COUNT(*) AS count FROM business WHERE B_nip = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $nip);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] == 0;
    }
    
}
?>