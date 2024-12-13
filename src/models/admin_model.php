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
    public function addBissnes($contact,$nip,$name,$photo){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO buisness (B_contact, B_nip, B_name, B_photo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $_POST['contact'], $_POST['nip'], $_POST['name'], $_POST['photo']);
        $stmt->execute();
        return true;
    }
    public function deleteBissnes($id){
        $conn = $this->db->getConnection();
        $query = "DELETE FROM buisness WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }
    public function editBissnes($id, $contact, $nip, $name, $photo){
        $conn = $this->db->getConnection();
        $query = "UPDATE buisness SET B_contact = ?, B_nip = ?, B_name = ?, B_photo = ? WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $contact, $nip, $name, $photo, $id);
        $stmt->execute();
        return true;
    }
    public function addEquipment($producer,$category,$size){
        $conn = $this->db->getConnection();
        $query = "INSERT INTO equipment (E_name, E_photo) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_POST['name'], $_POST['photo']);
        $stmt->execute();
        return true;
    }
    public function deleteEquipment($id){
        $conn = $this->db->getConnection();
        $query = "DELETE FROM equipment WHERE E_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }
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
}
?>