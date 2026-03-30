<?php
class PostModel {
    private $db;

    public function __construct($db) { $this->db = $db; }

    public function getFeed() {
        $sql = "SELECT p.*, u.full_name, u.username, 
               (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as like_count 
               FROM posts p 
               JOIN users u ON p.user_id = u.id 
               ORDER BY p.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function create($userId, $content, $image) {
        $stmt = $this->db->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $content, $image]);
    }

    public function toggleLike($postId, $userId) {
        $check = $this->db->prepare("SELECT * FROM likes WHERE post_id = ? AND user_id = ?");
        $check->execute([$postId, $userId]);
        
        if ($check->rowCount() > 0) {
            $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        } else {
            $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        }
        return $stmt->execute([$postId, $userId]);
    }
}