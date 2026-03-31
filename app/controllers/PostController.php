<?php
/**
 * Post Controller
 * Handles post-related logic (Create, Read, Delete)
 */

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Like.php';

class PostController {
    private $pdo;
    private $post_model;
    private $comment_model;
    private $like_model;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->post_model = new Post($pdo);
        $this->comment_model = new Comment($pdo);
        $this->like_model = new Like($pdo);
    }

    /**
     * Display home feed with all posts
     */
    public function showHome() {
        requireLogin();
        
        $posts = $this->post_model->getAllPosts(10000, 0); // Get all posts

        // Deduplicate any posts by ID (defensive, protects against repeat rows in joins)
        $uniquePosts = [];
        $seenPostIds = [];
        foreach ($posts as $singlePost) {
            if (!isset($seenPostIds[$singlePost['id']])) {
                $seenPostIds[$singlePost['id']] = true;
                $uniquePosts[] = $singlePost;
            }
        }
        $posts = $uniquePosts;

        // Add like and comment info for each post
        foreach ($posts as $index => $post) {
            $posts[$index]['liked'] = $this->like_model->hasLiked($post['id'], getCurrentUserId());
            $posts[$index]['comments'] = $this->comment_model->getPostComments($post['id']);
        }

        require __DIR__ . '/../views/home.php';
    }

    /**
     * Show create post form
     */
    public function showCreatePost() {
        requireLogin();
        require __DIR__ . '/../views/create_post.php';
    }

    /**
     * Handle post creation
     */
    public function createPost() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = validateInput($_POST['title'] ?? '');
            $content = validateInput($_POST['content'] ?? '');
            $user_id = getCurrentUserId();

            // Validation
            if (empty($content)) {
                $error = 'Post content is required';
                require __DIR__ . '/../views/create_post.php';
                return;
            }

            // Handle image upload
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $image = $this->uploadImage($_FILES['image']);
                if (!$image) {
                    $error = 'Failed to upload image';
                    require __DIR__ . '/../views/create_post.php';
                    return;
                }
            }

            // Create post
            $result = $this->post_model->createPost($user_id, $title, $content, $image);

            if ($result['success']) {
                header('Location: ' . BASE_URL . 'index.php?page=home');
                exit;
            } else {
                $error = $result['message'];
                require __DIR__ . '/../views/create_post.php';
            }
        } else {
            $this->showCreatePost();
        }
    }

    /**
     * Delete a post
     */
    public function deletePost($post_id) {
        requireLogin();
        
        if (!is_numeric($post_id)) {
            header('Location: ' . BASE_URL . 'index.php?page=home');
            exit;
        }

        // Get post details to delete image
        $post = $this->post_model->getPostById($post_id);
        if ($post && $post['image']) {
            $image_path = UPLOADS_DIR . 'posts/' . $post['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete post
        $result = $this->post_model->deletePost($post_id, getCurrentUserId());

        if ($result['success']) {
            header('Location: ' . BASE_URL . 'index.php?page=home');
        } else {
            header('Location: ' . BASE_URL . 'index.php?page=home&error=' . urlencode($result['message']));
        }
        exit;
    }

    /**
     * Handle like/heart toggle
     */
    public function toggleLike($post_id) {
        requireLogin();

        if (!is_numeric($post_id)) {
            exit;
        }

        $result = $this->like_model->toggleLike($post_id, getCurrentUserId());
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    /**
     * Handle comment submission
     */
    public function addComment($post_id) {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['comment'])) {
            $comment_text = validateInput($_POST['comment']);
            
            $result = $this->comment_model->addComment($post_id, getCurrentUserId(), $comment_text);
            
            header('Content-Type: application/json');
            if ($result['success']) {
                $lastComment = $this->comment_model->getLastComment($post_id);
                $post = $this->post_model->getPostById($post_id);

                echo json_encode([
                    'success' => true,
                    'message' => 'Comment added successfully',
                    'comment' => $lastComment,
                    'comments_count' => $post ? $post['comments_count'] : null,
                ]);
            } else {
                echo json_encode($result);
            }
        }
        exit;
    }

    /**
     * Delete a comment
     */
    public function deleteComment($comment_id) {
        requireLogin();

        if (!is_numeric($comment_id)) {
            exit;
        }

        $result = $this->comment_model->deleteComment($comment_id, getCurrentUserId());
        
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    /**
     * Upload image file
     */
    private function uploadImage($file) {
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
        $new_filename = uniqid('post_') . '.' . $ext;
        $upload_path = UPLOADS_DIR . 'posts/' . $new_filename;

        if (move_uploaded_file($tmp_name, $upload_path)) {
            return $new_filename;
        }

        return false;
    }
}
?>
