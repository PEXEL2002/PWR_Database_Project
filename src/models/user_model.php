<?php
require_once __DIR__ . '/../DBConnect.php';

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
    public function login() {
        // Hash the password for comparison
        $hashedPassword = md5($_POST['password']);

        // Prepare the query
        $conn = $this->db->getConnection();
        $query = "SELECT * FROM users WHERE U_email = ? AND U_password = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $_POST['email'], $hashedPassword);
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['user'] = $user;
            $this->id = $user['U_id'];
            $this->name = $user['U_name'];
            $this->surname = $user['U_surname'];
            $this->email = $user['U_email'];
            $this->role = $user['U_role'];
            $this->profile_image = $user['U_photo'];
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Register user
     * @return bool - True if registration is successful, false otherwise
     */
    public function register() {
        if(!isset($_POST['name']) || !isset($_POST['surname']) || !isset($_POST['email']) || !isset($_POST['password'])) {
            return false;
        }
        // Get the form data
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(!isset($_POST['profile_image'])) {
            $profileImage = 'default.jpg';}
        else {
            $profileImage = $_POST['profile_image'];
        }
        // Hash the password
        $hashedPassword = md5($password);

        // Check if the email already exists
        $conn = $this->db->getConnection();
        $checkQuery = "SELECT * FROM users WHERE U_email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            return false; // Email already exists
        }

        // Insert the user into the database
        $query = "INSERT INTO users (U_name, U_surname, U_email, U_password, U_role, U_photo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $role = '0'; // Regular user

        // Bind parameters
        $stmt->bind_param("ssssss", $name, $surname, $email, $hashedPassword, $role, $profileImage);

        // Execute the query
        return $stmt->execute();
    }
    /**
     * Get all users from the database if the user is an admin
     */
    public function getUsers() {
        if ($_SESSION['user']['U_role'] == 1) {
            $conn = $this->db->getConnection();
            $query = "SELECT * FROM users";
            $result = $conn->query($query);
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return false;
        }
    }

    /**
     * Add new admin user
     * @return bool - True if the user is added, false otherwise
     */
    public function addAdmin() {
        if(!isset($_POST['name']) || !isset($_POST['surname']) || !isset($_POST['email']) || !isset($_POST['password'])) {
            return false;
        }
        // Get the form data
        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $profileImage = 'default.jpg';
        if ($_SESSION['user']['U_role'] == 1) {
            // Hash the password
            $hashedPassword = md5($password);
            // Insert the user into the database
            $conn = $this->db->getConnection();
            $query = "INSERT INTO users (U_name, U_surname, U_email, U_password, U_role, U_photo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $role = '1'; // Admin user
            $stmt->bind_param("ssssss", $name, $surname, $email, $hashedPassword, $role, $profileImage);
            // Execute the query
            return $stmt->execute();
        } else {
            return false;
        }
    }
    /**
     * Delete user account himself
     * @return void - Redirect to index.php
     */
    public function deleteUser() {
        $conn = $this->db->getConnection();
        $query = "DELETE FROM users WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $userId = $_SESSION['user']['U_id'];
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $this->logout();
            header("Location: index.php");
            exit();
        } else {
            echo "Błąd podczas usuwania użytkownika: " . $conn->error;
        }
    }
    /**
     * Change user password
     * @return bool - True if the password is changed, false otherwise
     */
    public function changePassword() {
        if(!isset($_POST['new_password'])) {
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_password = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $hashedPassword = md5($_POST['new_password']);
        $userId = $_SESSION['user']['U_id'];
        $stmt->bind_param("si", $hashedPassword, $userId);
        return $stmt->execute();
    }
    /**
     * Change user profile image
     * @return bool - True if the image is changed, false otherwise
     */
    public function changeProfileImage() {
        if (!isset($_POST['new_photo'])) {
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_photo = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $userId = $_SESSION['user']['U_id'];
        $_SESSION['user']['U_photo'] = $_POST['new_photo'];
        $stmt->bind_param("si", $_POST['new_photo'], $userId);
        return $stmt->execute();
    }
    /**
     * Change user user name
     */
    public function changeUserName() {
        if (!isset($_POST['new_name'])) {
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_name = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $userId = $_SESSION['user']['U_id'];
        $_SESSION['user']['U_name'] = $_POST['new_name'];
        $stmt->bind_param("si", $_POST['new_name'], $userId);
        return $stmt->execute();
    }
    /**
     * Change user surname
     */
    public function changeUserSurname() {
        if (!isset($_POST['new_surname'])) {
            return false;
        }
        $conn = $this->db->getConnection();
        $query = "UPDATE users SET U_surname = ? WHERE U_id = ?";
        $stmt = $conn->prepare($query);
        $userId = $_SESSION['user']['U_id'];
        $_SESSION['user']['U_surname'] = $_POST['new_surname'];
        $stmt->bind_param("si", $_POST['new_surname'], $userId);
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
