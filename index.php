<?php
/**
 * Bondly Social Network Application
 * Main Router/Entry Point
 * Saint Michael College of Caraga (SMCC)
 */

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Include controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

// Initialize controllers
$auth_controller = new AuthController($pdo);
$post_controller = new PostController($pdo);
$user_controller = new UserController($pdo);

// Get the requested page/action
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

try {
    // Route authentication pages
    if ($action === 'process_login') {
        $auth_controller->processLogin();
    } elseif ($action === 'process_register') {
        $auth_controller->processRegister();
    } elseif ($action === 'logout') {
        $auth_controller->logout();
    }
    // Route post-related actions
    elseif ($action === 'create_post') {
        $post_controller->createPost();
    } elseif ($action === 'delete_post') {
        $post_id = $_GET['post_id'] ?? null;
        $post_controller->deletePost($post_id);
    } elseif ($action === 'toggle_like') {
        $post_id = $_GET['post_id'] ?? null;
        $post_controller->toggleLike($post_id);
    } elseif ($action === 'add_comment') {
        $post_id = $_GET['post_id'] ?? null;
        $post_controller->addComment($post_id);
    } elseif ($action === 'delete_comment') {
        $comment_id = $_GET['comment_id'] ?? null;
        $post_controller->deleteComment($comment_id);
    }
    // Route user-related actions
    elseif ($action === 'update_profile') {
        $user_controller->updateProfile();
    }
    // Route pages
    elseif ($page === 'home') {
        $post_controller->showHome();
    } elseif ($page === 'login') {
        $auth_controller->showLogin();
    } elseif ($page === 'register') {
        $auth_controller->showRegister();
    } elseif ($page === 'create_post') {
        $post_controller->showCreatePost();
    } elseif ($page === 'profile') {
        $username = $_GET['user'] ?? null;
        $user_controller->showProfile($username);
    } elseif ($page === 'edit_profile') {
        $user_controller->showEditProfile();
    } elseif ($page === 'search') {
        $user_controller->search();
    } else {
        // Default to home if logged in, otherwise to login
        if (isLoggedIn()) {
            $post_controller->showHome();
        } else {
            $auth_controller->showLogin();
        }
    }
} catch (Exception $e) {
    // Error handling
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error - Bondly</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body style="background: #020617; color: #e0e7ff;">
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">An Error Occurred</h4>
                <p><?php echo sanitize($e->getMessage()); ?></p>
                <hr>
                <a href="<?php echo BASE_URL; ?>index.php" class="btn btn-primary">Go Home</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
