<?php
class PostController {
    private $postModel;

    public function __construct($db) {
        require_once __DIR__ . '/../models/PostModel.php';
        $this->postModel = new PostModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) header("Location: index.php");
        $posts = $this->postModel->getFeed();
        include __DIR__ . '/../app/views/newsfeed.php';
    }

    public function store() {
        $content = htmlspecialchars($_POST['content']);
        $image = null;
        
        if (!empty($_FILES['post_image']['name'])) {
            $image = time() . "_" . $_FILES['post_image']['name'];
            move_uploaded_file($_FILES['post_image']['tmp_name'], "assets/uploads/posts/" . $image);
        }

        $this->postModel->create($_SESSION['user_id'], $content, $image);
        header("Location: index.php?action=newsfeed");
    }

    public function like() {
        $this->postModel->toggleLike($_GET['id'], $_SESSION['user_id']);
        header("Location: index.php?action=newsfeed");
    }
}