<<?php
class PostModel {
    private $conn;
    public function __construct($db) { $this->conn = $db; }

    public function getFeed() {
        try {
            $sql = "SELECT p.*, u.username, 
                   (SELECT COUNT(*) FROM likes WHERE post_id = p.id) as likes_count 
                    FROM posts p 
                    JOIN users u ON p.user_id = u.id 
                    ORDER BY p.created_at DESC";
            $stmt = $this->conn->query($sql);
            return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (PDOException $e) {
            return []; // Returns empty array instead of null to prevent errors
        }
    }
    public function createPost($uid, $content) {
        return $this->conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)")->execute([$uid, $content]);
    }

    public function toggleLike($pid, $uid) {
        $stmt = $this->conn->prepare("SELECT id FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$pid, $uid]);
        if ($stmt->fetch()) {
            return $this->conn->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?")->execute([$pid, $uid]);
        }
        return $this->conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)")->execute([$pid, $uid]);
    }

    public function addComment($pid, $uid, $content) {
        return $this->conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)")->execute([$pid, $uid, htmlspecialchars($content)]);
    }

    public function getCommentsByPost($pid) {
        $stmt = $this->conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
        $stmt->execute([$pid]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}