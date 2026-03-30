<?php
class LikeModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Requirement 3E: Toggle Like (One like per user per post)
    public function toggle($postId, $userId) {
        // Check if user already liked the post
        $stmt = $this->db->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        
        if ($stmt->fetch()) {
            // If exists, remove it (Unlike)
            $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
        } else {
            // If not, add it (Like)
            $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
        }
        return $stmt->execute([$postId, $userId]);
    }
}