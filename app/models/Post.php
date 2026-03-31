<?php
/**
 * Post Model
 * Handles post-related database operations
 */

class Post {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a new post
     */
    public function createPost($user_id, $title, $content, $image = null) {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO posts (user_id, title, content, image) 
                 VALUES (?, ?, ?, ?)'
            );
            
            $result = $stmt->execute([$user_id, $title, $content, $image]);
            return ['success' => $result, 'message' => $result ? 'Post created successfully' : 'Failed to create post'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all posts ordered by latest first
     */
    public function getAllPosts($limit = 20, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT p.id, p.user_id, p.title, p.content, p.image, p.likes_count, p.comments_count, p.created_at,
                        u.fullname, u.username, u.profile_picture
                 FROM posts p
                 JOIN users u ON p.user_id = u.id
                 ORDER BY p.created_at DESC
                 LIMIT ? OFFSET ?'
            );
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get user-specific posts
     */
    public function getUserPosts($user_id, $limit = 20, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT p.id, p.user_id, p.title, p.content, p.image, p.likes_count, p.comments_count, p.created_at,
                        u.fullname, u.username, u.profile_picture
                 FROM posts p
                 JOIN users u ON p.user_id = u.id
                 WHERE p.user_id = ?
                 ORDER BY p.created_at DESC
                 LIMIT ? OFFSET ?'
            );
            $stmt->execute([$user_id, $limit, $offset]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get single post by ID
     */
    public function getPostById($id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT p.id, p.user_id, p.title, p.content, p.image, p.likes_count, p.comments_count, p.created_at,
                        u.fullname, u.username, u.profile_picture
                 FROM posts p
                 JOIN users u ON p.user_id = u.id
                 WHERE p.id = ?'
            );
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Delete post (only by owner)
     */
    public function deletePost($post_id, $user_id) {
        try {
            // Check if user owns the post
            $stmt = $this->pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
            $stmt->execute([$post_id]);
            $post = $stmt->fetch();

            if (!$post || $post['user_id'] != $user_id) {
                return ['success' => false, 'message' => 'Unauthorized'];
            }

            // Delete post and associated records
            $stmt = $this->pdo->prepare('DELETE FROM posts WHERE id = ?');
            $result = $stmt->execute([$post_id]);

            return ['success' => $result, 'message' => $result ? 'Post deleted successfully' : 'Failed to delete post'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update likes count
     */
    public function updateLikesCount($post_id) {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE posts SET likes_count = (SELECT COUNT(*) FROM likes WHERE post_id = ?) 
                 WHERE id = ?'
            );
            return $stmt->execute([$post_id, $post_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Update comments count
     */
    public function updateCommentsCount($post_id) {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE posts SET comments_count = (SELECT COUNT(*) FROM comments WHERE post_id = ?) 
                 WHERE id = ?'
            );
            return $stmt->execute([$post_id, $post_id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Search posts by title or content
     */
    public function searchPosts($query, $limit = 20) {
        try {
            $search_term = '%' . $query . '%';
            $stmt = $this->pdo->prepare(
                'SELECT p.id, p.user_id, p.title, p.content, p.image, p.likes_count, p.comments_count, p.created_at,
                        u.fullname, u.username, u.profile_picture
                 FROM posts p
                 JOIN users u ON p.user_id = u.id
                 WHERE p.title LIKE ? OR p.content LIKE ?
                 ORDER BY p.created_at DESC
                 LIMIT ?'
            );
            $stmt->execute([$search_term, $search_term, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>
