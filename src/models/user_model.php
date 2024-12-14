<?php
require_once realpath(__DIR__ . '/../DBConect.php');
/**
 * User model
 * This class contains methods for user authentication and management
 */
class User {
    protected  $db;
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
        $query = "SELECT U_id,U_name,U_surname,U_mail, U_role FROM users WHERE U_mail = ? AND U_password = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $mail, $hashedPassword);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $this->id = $user['U_id'];
            $this->name = $user['U_name'];
            $this->surname = $user['U_surname'];
            $this->email = $user['U_mail'];
            $this->role = $user['U_role'];
            return true;
        } else {
            return false;
        }
    }
    public function getId(){
        return $this->id;
    }
    public function getRole(){
        return $this->role;
    }
    public function getInfo(){
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
        ];
    }
    public function updateName($newName){
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_name = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newName, $this->id);
        $this->name = $newName;
        $_SESSION['user'] = $this;
        return $stmt->execute();
    }
    public function updateSurname($newSurname){
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_surname = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newSurname, $this->id);
        $this->surname = $newSurname;
        $_SESSION['user'] = $this;
        return $stmt->execute();
    }
    public function updateEmail($newEmail){
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_mail = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $newEmail, $this->id);
        $this->email = $newEmail;
        $_SESSION['user'] = $this;
        return $stmt->execute();
    }
    public function getPassword(){
        $conn = $this->db->getConnection();
        $query = "SELECT U_password FROM users WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        $password = $result->fetch_assoc();
        $_SESSION['user'] = $this;
        return $password['U_password'];
    }
    public function updatePassword($newPassword){
        $conn = $this->db->getConnection();
        $hashedPassword = md5($newPassword);
        $query = "UPDATE users SET U_password = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $hashedPassword, $this->id);
        $_SESSION['user'] = $this;
        return $stmt->execute();
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
    public function getTransactionHistory() {
        $conn = $this->db->getConnection();
        $query = "SELECT R_date_rental, R_date_submission, R_price
                  FROM rent
                  WHERE R_user_id = ?";
        $stmt = $conn->prepare($query);
        $userId = $this->getId();
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}
?>
