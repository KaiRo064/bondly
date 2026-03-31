<?php
/**
 * User Controller
 * Handles user profile and settings
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';

class UserController {
    private $pdo;
    private $user_model;
    private $post_model;
    private $comment_model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->user_model = new User($pdo);
        $this->post_model = new Post($pdo);
        $this->comment_model = new Comment($pdo);
    }

    /**
     * Display user profile
     */
    public function showProfile($username = null) {
        requireLogin();

        // If no username provided, show current user's profile
        if (!$username) {
            $username = $_SESSION['username'];
        }

        // Get user data
        $user = $this->user_model->getUserByUsername($username);
        if (!$user) {
            header('Location: ' . BASE_URL . 'index.php?page=home&error=User%20not%20found');
            exit;
        }

        // Get user's posts
        $posts = $this->post_model->getUserPosts($user['id']);

        // Attach comments to each post for profile view
        foreach ($posts as &$post) {
            $post['comments'] = $this->comment_model->getPostComments($post['id']);
        }

        // Get user stats
        $posts_count = $this->user_model->getPostsCount($user['id']);
        $engagement_count = $this->user_model->getEngagementCount($user['id']);

        require __DIR__ . '/../views/profile.php';
    }

    /**
     * Show edit profile form
     */
    public function showEditProfile() {
        requireLogin();

        $user = $this->user_model->getUserById(getCurrentUserId());
        if (!$user) {
            header('Location: ' . BASE_URL . 'index.php?page=home');
            exit;
        }

        require __DIR__ . '/../views/edit_profile.php';
    }

    /**
     * Handle profile update
     */
    public function updateProfile() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = validateInput($_POST['fullname'] ?? '');
            $bio = validateInput($_POST['bio'] ?? '');
            $user_id = getCurrentUserId();

            // Validation
            if (empty($fullname)) {
                $error = 'Full name is required';
                $user = $this->user_model->getUserById($user_id);
                require __DIR__ . '/../views/edit_profile.php';
                return;
            }

            // Handle profile picture upload
            $profile_picture = null;
            if (!empty($_FILES['profile_picture']['name'])) {
                $profile_picture = $this->uploadProfilePicture($_FILES['profile_picture']);
                if (!$profile_picture) {
                    $error = 'Failed to upload profile picture';
                    $user = $this->user_model->getUserById($user_id);
                    require __DIR__ . '/../views/edit_profile.php';
                    return;
                }
            }

            // Update profile
            $result = $this->user_model->updateProfile($user_id, $fullname, $bio, $profile_picture);

            if ($result['success']) {
                // Update session
                $_SESSION['username'] = $this->user_model->getUserById($user_id)['username'];
                header('Location: ' . BASE_URL . 'index.php?page=profile&success=Profile%20updated%20successfully');
                exit;
            } else {
                $error = $result['message'];
                $user = $this->user_model->getUserById($user_id);
                require __DIR__ . '/../views/edit_profile.php';
            }
        } else {
            $this->showEditProfile();
        }
    }

    /**
     * Search users and posts
     */
    public function search() {
        requireLogin();

        $query = sanitize($_GET['q'] ?? '');
        $search_type = sanitize($_GET['type'] ?? 'all'); // all, users, posts
        $user_results = [];
        $post_results = [];

        if (!empty($query)) {
            if ($search_type === 'all' || $search_type === 'users') {
                $user_results = $this->user_model->searchUsers($query);
            }
            if ($search_type === 'all' || $search_type === 'posts') {
                $post_results = $this->post_model->searchPosts($query);
            }
        }

        require __DIR__ . '/../views/search.php';
    }

    /**
     * Upload profile picture
     */
    private function uploadProfilePicture($file) {
        $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png'];
        $filename = $file['name'];
        $filetype = $file['type'];
        $filesize = $file['size'];
        $tmp_name = $file['tmp_name'];

        // Validate file
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!array_key_exists($ext, $allowed)) {
            return false;
        }

        if ($filesize > 5 * 1024 * 1024) { // 5MB limit
            return false;
        }

        if (!in_array($filetype, $allowed)) {
            return false;
        }

        // Create unique filename
        $new_filename = uniqid('profile_') . '.' . $ext;
        $upload_path = UPLOADS_DIR . 'profiles/' . $new_filename;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            return $new_filename;
        }

        return false;
    }
}
?>
