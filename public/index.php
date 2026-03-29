<?php
// PROBLEM 1: SESSION NOT INITIALIZED SAFELY
// We add a check to ensure sessions only start if one isn't already active.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../app/controllers/AppController.php';

// PROBLEM 2: CALLING METHODS ON A NULL OBJECT
// If the database connection fails, (new Database())->getConnection() returns null.
// We must check if $db exists before giving it to the Controller.
$database = new Database();
$db = $database->getConnection();

if (!$db) {
    die("Connection Error: Please ensure 'bondly_db' exists in phpMyAdmin.");
}

$ctrl = new AppController($db);
$page = $_GET['page'] ?? 'login';

// Security: Redirect logged-in users away from Login/Register
if (isset($_SESSION['user_id']) && ($page == 'login' || $page == 'register')) {
    header("Location: index.php?page=newsfeed");
    exit();
}

// Security: Redirect guests away from Newsfeed
$public = ['login', 'register', 'login_action', 'register_action'];
if (!isset($_SESSION['user_id']) && !in_array($page, $public)) {
    header("Location: index.php?page=login");
    exit();
}

// PROBLEM 3: UNDEFINED ACTIONS IN THE SWITCH
// Ensure every case matches a method that actually exists in your AppController.
switch ($page) {
    case 'login': 
        include '../app/views/login.php'; 
        break;
    case 'register': 
        include '../app/views/register.php'; 
        break;
    case 'login_action': 
        $ctrl->loginAction(); 
        break;
    case 'register_action': 
        $ctrl->registerAction(); 
        break;
    case 'newsfeed': 
        $ctrl->showFeed(); 
        break;
    case 'post_action': 
        $ctrl->createPostAction(); 
        break;
    case 'like': 
        $ctrl->likeAction(); 
        break;
    case 'comment_action': 
        $ctrl->commentAction(); 
        break;
    case 'logout':
        session_unset();
        session_destroy();
        header("Location: index.php?page=login");
        exit();
    default: 
        header("Location: index.php?page=newsfeed"); 
        break;
}