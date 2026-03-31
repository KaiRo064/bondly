<?php
/**
 * Comment Model
 * Handles comment-related database operations
 */

class Comment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Add a comment to a post
     */
    public function addComment($post_id, $user_id, $content) {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO comments (post_id, user_id, content) 
                 VALUES (?, ?, ?)'
            );
            
            $result = $stmt->execute([$post_id, $user_id, $content]);

            if ($result) {
                // Update post comments count
                $post_model = new Post($this->pdo);
                $post_model->updateCommentsCount($post_id);
            }

            return ['success' => $result, 'message' => $result ? 'Comment added successfully' : 'Failed to add comment'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all comments for a post
     */
    public function getPostComments($post_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT c.id, c.post_id, c.user_id, c.content, c.created_at,
                        u.fullname, u.username, u.profile_picture
                 FROM comments c
                 JOIN users u ON c.user_id = u.id
                 WHERE c.post_id = ?
                 ORDER BY c.created_at ASC'
            );
            $stmt->execute([$post_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Delete comment (only by owner)
     */
    public function deleteComment($comment_id, $user_id) {
        try {
            // Check if user owns the comment
            $stmt = $this->pdo->prepare('SELECT user_id, post_id FROM comments WHERE id = ?');
            $stmt->execute([$comment_id]);
            $comment = $stmt->fetch();

            if (!$comment || $comment['user_id'] != $user_id) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            // Delete comment
            $stmt = $this->pdo->prepare('DELETE FROM comments WHERE id = ?');
            $result = $stmt->execute([$comment_id]);

            if ($result) {
                // Update post comments count
                $post_model = new Post($this->pdo);
                $post_model->updateCommentsCount($comment['post_id']);
            }

            return ['success' => $result, 'message' => $result ? 'Comment deleted successfully' : 'Failed to delete comment'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>
