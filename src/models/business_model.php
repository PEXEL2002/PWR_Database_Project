<?php 
require_once __DIR__ . '/../DBConnect.php';

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
}
?>