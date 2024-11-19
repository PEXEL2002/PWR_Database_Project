<?php 
require_once 'config.php';
class Database{
    /**
    * Database connection properties
    *    @param $conn - database connection variable
    */
    private $conn;
    /**
     * Database connection
     *   @return $conn - database connection
     */
    public function __construct(){
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    /** 
     * Execute query
     *  @param $query - query to be executed
     *  @return $result - result of the query
    */
    public function execute($query){
        return $this->conn->query($query);
        
    }
    /**
     * Get array from query
     * @param $query - query to be executed
     * @return $data - array of data from the query
     */
    public function getArray($query){
        $result = $this->conn->query($query);
        $data = [];
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $data[] = $row;
            }
        }
        return $data;
    }
    /**
     * Get number of rows from query
     * @param $query - query to be executed
     * @return $result->num_rows - number of rows from the query
     */
    public function getNumRows($query){
        $result = $this->conn->query($query);
        return $result->num_rows;
    }
    /**
     * Close database connection
     * @return void
     */
    public function __destruct(){
        $this->conn->close();
    }

}

?>