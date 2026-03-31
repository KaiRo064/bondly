<?php
/**
 * Like Model
 * Handles like/heart functionality for posts
 */

class Like {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Toggle like/heart on a post
     */
    public function toggleLike($post_id, $user_id) {
        try {
            // Check if like already exists
            $stmt = $this->pdo->prepare(
                'SELECT id FROM likes WHERE post_id = ? AND user_id = ?'
            );
            $stmt->execute([$post_id, $user_id]);

            if ($stmt->rowCount() > 0) {
                // Unlike the post
                $stmt = $this->pdo->prepare(
                    'DELETE FROM likes WHERE post_id = ? AND user_id = ?'
                );
                $result = $stmt->execute([$post_id, $user_id]);
                $liked = false;
            } else {
                // Like the post
                $stmt = $this->pdo->prepare(
                    'INSERT INTO likes (post_id, user_id) VALUES (?, ?)'
                );
                $result = $stmt->execute([$post_id, $user_id]);
                $liked = true;
            }

            if ($result) {
                // Update post likes count
                $post_model = new Post($this->pdo);
                $post_model->updateLikesCount($post_id);
            }

            return ['success' => $result, 'liked' => $liked];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Check if user liked a post
     */
    public function hasLiked($post_id, $user_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id FROM likes WHERE post_id = ? AND user_id = ?'
            );
            $stmt->execute([$post_id, $user_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Get likes count for a post
     */
    public function getLikesCount($post_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) as count FROM likes WHERE post_id = ?'
            );
            $stmt->execute([$post_id]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Get users who liked a post
     */
    public function getLikers($post_id, $limit = 5) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT u.id, u.fullname, u.username, u.profile_picture
                 FROM likes l
                 JOIN users u ON l.user_id = u.id
                 WHERE l.post_id = ?
                 ORDER BY l.created_at DESC
                 LIMIT ?'
            );
            $stmt->execute([$post_id, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
