<?php
class ProfileController {
    private $db;
    private $userModel;
    private $postModel;

    public function __construct($db) {
        $this->db = $db;
        require_once __DIR__ . '/../models/UserModel.php';
        require_once __DIR__ . '/../models/PostModel.php';
        $this->userModel = new UserModel($db);
        $this->postModel = new PostModel($db);
    }

    public function index() {
        // Security check: must be logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=login");
            exit();
        }
        
        $user = $this->userModel->getById($_SESSION['user_id']);
        // Requirement 3B: List of user posts
        $userPosts = $this->postModel->getByUser($_SESSION['user_id']);
        
        // Pass data to the view
        include __DIR__ . '/../views/profile.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $fullName = htmlspecialchars(trim($_POST['full_name']));
            $bio = htmlspecialchars(trim($_POST['bio']));
            $imageName = $_POST['current_image']; // Default to old image

            // Handle Profile Picture Upload
            if (!empty($_FILES['profile_image']['name'])) {
                $targetDir = "assets/uploads/profiles/";
                // Create directory if it doesn't exist
                if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }

                $fileName = time() . "_" . basename($_FILES['profile_image']['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                    $imageName = $fileName;
                }
            }

            $result = $this->userModel->updateProfile($userId, $fullName, $bio, $imageName);
            
            if ($result) {
                $_SESSION['full_name'] = $fullName; 
                header("Location: index.php?action=profile");
                exit();
            }
        }
    }
}