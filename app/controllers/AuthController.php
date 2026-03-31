<?php
/**
 * Auth Controller
 * Handles authentication logic (Login, Register, Logout)
 */

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $pdo;
    private $user_model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->user_model = new User($pdo);
    }

    /**
     * Display login page
     */
    public function showLogin() {
        require __DIR__ . '/../views/login.php';
    }

    /**
     * Handle login form submission
     */
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = validateInput($_POST['username'] ?? '');
            $password = validateInput($_POST['password'] ?? '');

            // Validation
            if (empty($username) || empty($password)) {
                $error = 'Username and password are required';
                require __DIR__ . '/../views/login.php';
                return;
            }

            // Attempt login
            $result = $this->user_model->login($username, $password);

            if ($result['success']) {
                header('Location: ' . BASE_URL . 'index.php?page=home');
                exit;
            } else {
                $error = $result['message'];
                require __DIR__ . '/../views/login.php';
            }
        } else {
            $this->showLogin();
        }
    }

    /**
     * Display registration page
     */
    public function showRegister() {
        require __DIR__ . '/../views/register.php';
    }

    /**
     * Handle registration form submission
     */
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = validateInput($_POST['fullname'] ?? '');
            $username = validateInput($_POST['username'] ?? '');
            $email = validateInput($_POST['email'] ?? '');
            $password = validateInput($_POST['password'] ?? '');
            $confirm_password = validateInput($_POST['confirm_password'] ?? '');

            // Validation
            if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
                $error = 'All fields are required';
                require __DIR__ . '/../views/register.php';
                return;
            }

            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
                require __DIR__ . '/../views/register.php';
                return;
            }

            if ($password !== $confirm_password) {
                $error = 'Passwords do not match';
                require __DIR__ . '/../views/register.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email address';
                require __DIR__ . '/../views/register.php';
                return;
            }

            // Attempt registration
            $result = $this->user_model->register($fullname, $username, $email, $password);

            if ($result['success']) {
                $success = 'Registration successful! Please login.';
                require __DIR__ . '/../views/login.php';
            } else {
                $error = $result['message'];
                require __DIR__ . '/../views/register.php';
            }
        } else {
            $this->showRegister();
        }
    }

    /**
     * Handle logout
     */
    public function logout() {
        $this->user_model->logout();
        header('Location: ' . BASE_URL . 'index.php?page=login');
        exit;
    }
}
?>
