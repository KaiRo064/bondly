<?php
class AuthController {
    private $userModel;

    public function __construct($db) {
        // Use realpath to ensure the model is always found
        require_once __DIR__ . '/../models/UserModel.php';
        $this->userModel = new UserModel($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                // Requirement 3A: Session Handling
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                
                header("Location: index.php?action=newsfeed");
                exit();
            } else {
                echo "<script>alert('Invalid Username or Password'); window.location='index.php?action=login';</script>";
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $full_name = trim($_POST['full_name']);

            // Basic validation
            if (empty($username) || empty($password)) {
                echo "<script>alert('Please fill in all fields'); window.history.back();</script>";
                return;
            }

            $result = $this->userModel->register($username, $password, $full_name);
            
            if ($result) {
                header("Location: index.php?action=login");
                exit();
            } else {
                echo "<script>alert('Registration failed. Username might be taken.'); window.history.back();</script>";
            }
        }
    }
}