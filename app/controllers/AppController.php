<?php
require_once '../app/models/UserModel.php';
require_once '../app/models/PostModel.php';

class AppController {
    private $db;
    private $userModel;
    private $postModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new UserModel($db);
        $this->postModel = new PostModel($db);
    }

    public function showFeed() {
        $posts = $this->postModel->getFeed() ?: []; 
        foreach ($posts as &$post) {
            $post['comments'] = $this->postModel->getCommentsByPost($post['id']) ?: [];
        }
        include '../app/views/newsfeed.php';
    }

    public function loginAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->login($_POST['username'], $_POST['password']);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: index.php?page=newsfeed");
                exit();
            } else {
                header("Location: index.php?page=login&error=1");
                exit();
            }
        }
    }

    public function registerAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->register($_POST['username'], $_POST['password'], $_POST['full_name'])) {
                header("Location: index.php?page=login&success=1");
            } else {
                header("Location: index.php?page=register&error=1");
            }
            exit();
        }
    }

    // FIX: Added missing method for posting
    public function createPostAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
            $this->postModel->createPost($_SESSION['user_id'], $_POST['content']);
        }
        header("Location: index.php?page=newsfeed");
        exit();
    }

    // FIX: Added missing method for liking
    public function likeAction() {
        if (isset($_GET['id'])) {
            $this->postModel->toggleLike($_GET['id'], $_SESSION['user_id']);
        }
        header("Location: index.php?page=newsfeed");
        exit();
    }

    // FIX: Added missing method for commenting
    public function commentAction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['content'])) {
            $this->postModel->addComment($_POST['post_id'], $_SESSION['user_id'], $_POST['content']);
        }
        header("Location: index.php?page=newsfeed");
        exit();
    }
}