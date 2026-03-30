<?php
class CommentModel {
    private $db;

    public function __construct($db) { $this->db = $db; }

    public function add($postId, $userId, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        return $stmt->execute([$postId, $userId, htmlspecialchars($content)]);
    }

    public function getByPost($postId) {
        $stmt = $this->db->prepare("SELECT c.*, u.full_name FROM comments c 
                                    JOIN users u ON c.user_id = u.id 
                                    WHERE c.post_id = ? ORDER BY c.created_at ASC");
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }
}