<?php 


class Buisness{
    private $db;

    public function  __construct(){
        $this->db = new Database();
    }
    public function getBuisness(){
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM buisness";
        $result = $conn->query($query);
        $buisness = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $buisness[] = $row;
            }
        }
        return $buisness;
    }

    public function addBuisness(){
        if(!isset($_POST['name']) || !isset($_POST['contact']) || !isset($_POST['NIP'])){
            return false;
        }
        if(!isset($_POST['image'])){
            $_POST['image'] = '';
        }
        $conn = $this->db->getConnection();
        $query = "INSERT INTO buisness (B_name, B_contact, B_NIP, B_image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $_POST['name'], $_POST['contact'], $_POST['NIP'], $_POST['image']);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteBuisness(){
        if(!isset($_POST['id'])){
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "DELETE FROM buisness WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $_POST['id']);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function updateNameBuisness(){
        if(!isset($_POST['id']) || !isset($_POST['name'])){
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE buisness SET B_name = ? WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $_POST['name'], $_POST['id']);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function updateContactBuisness(){
        if(!isset($_POST['id']) || !isset($_POST['contact'])){
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE buisness SET B_contact = ? WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $_POST['contact'], $_POST['id']);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function updateNIPBuisness(){
        if(!isset($_POST['id']) || !isset($_POST['NIP'])){
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE buisness SET B_NIP = ? WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $_POST['NIP'], $_POST['id']);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function updateImageBuisness(){
        if(!isset($_POST['id']) || !isset($_POST['image'])){
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE buisness SET B_image = ? WHERE B_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $_POST['image'], $_POST['id']);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>