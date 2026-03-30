<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/PostController.php';

$db = (new Database())->connect();
$action = $_GET['action'] ?? 'login';

$action = $_GET['action'] ?? 'login';

switch ($action) {
    // Auth
    case 'login':           include __DIR__ . '/../app/views/auth/login.php'; break;
    case 'login_submit':    $authCtrl->login(); break;
    case 'register':        include __DIR__ . '/../app/views/auth/register.php'; break;
    case 'register_submit': $authCtrl->register(); break;
    case 'logout':          $authCtrl->logout(); break;

    // Profile
    case 'profile':         (new ProfileController($db))->index(); break;
    case 'update_profile':  (new ProfileController($db))->update(); break;

    // Posts
    case 'newsfeed':        $postCtrl->index(); break;
    case 'create_post':     $postCtrl->store(); break;
    case 'delete_post':     $postCtrl->delete(); break;
    case 'like':            $postCtrl->like(); break;

    default:                header("Location: index.php?action=login"); break;
}