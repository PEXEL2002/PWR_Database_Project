<?php
require_once realpath(__DIR__ . '/../DBConect.php');
/**
 * User model
 * This class contains methods for user authentication and management
 */
class User {
    private $db;
    private $id;
    private $name;
    private $surname;
    private $email;
    private $password;
    private $role;
    private $profile_image;

    /**
     * Constructor - initializes the database connection
     */
    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Login user
     * @return array|bool - User data or false if login fails
     */
    public function login($mail, $password) {
        // Hash the password for comparison
        $hashedPassword = md5($password);

        // Prepare the query
        $conn = $this->db->getConnection();
        $query = "SELECT 'U_id','U_name','U_surname','U_mail', 'U_role' FROM users WHERE U_mail = ? AND U_password = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $mail, $hashedPassword);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['user'] = $user; 
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Register user
     * @return bool - True if registration is successful, false otherwise
     */
    public function register($name, $surname, $email, $password) {
        // Hash the password
        $hashedPassword = md5($password);

        // Check if the email already exists
        $conn = $this->db->getConnection();
        $checkQuery = "SELECT * FROM users WHERE U_mail = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Email already exists
        }
        $profileImage = 'default.jpg';
        // Insert the user into the database
        $query = "INSERT INTO users (U_name, U_surname, U_mail, U_password, U_role, U_photo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $role = '0'; // Regular user

        // Bind parameters
        $stmt->bind_param("ssssss", $name, $surname, $email, $hashedPassword, $role, $profileImage);

        // Execute the query
        return $stmt->execute();
    }

    /**
     * logout user
     * @return void
     */
    public function logout() {
        session_destroy();
        session_start();
        session_unset();
        header("Location: index.php");
    }

    /**
     * Destructor - close the database connection
     */
    public function __destruct(){
        $this->db->getConnection()->close();
    }
}
?>
