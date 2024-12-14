<?php
require_once 'config.php';

class Database {
    /**
     * Database connection property
     */
    private $conn;
    /**
     * Constructor - initializes database connection
     */
    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
            header("Location: public/error.php");
        }
    }
    /**
     * Get the database connection
     * @return mysqli
     */
    public function getConnection() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->conn->set_charset("utf8mb4");
        return $this->conn;
    }
}
?>
