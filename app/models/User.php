<?php
/**
 * User Model
 * Handles user-related database operations
 */

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Create a new user account
     */
    public function register($fullname, $username, $email, $password): array {
        try {
            // Check if username or email already exists
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Username or email already exists'];
            }

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (fullname, username, email, password) 
                 VALUES (?, ?, ?, ?)'
            );
            
            $result = $stmt->execute([$fullname, $username, $email, $hashed_password]);

            if ($result) {
                return ['success' => true, 'message' => 'Registration successful'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }

    /**
     * Authenticate user login
     */
    public function login($username, $password): array {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, username, email, password FROM users WHERE username = ? OR email = ?'
            );
            $stmt->execute([$username, $username]);
            
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                return ['success' => true, 'message' => 'Login successful'];
            }

            return ['success' => false, 'message' => 'Invalid username or password'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get user profile by ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, fullname, username, email, bio, profile_picture, created_at 
                 FROM users WHERE id = ?'
            );
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get user by username
     */
    public function getUserByUsername($username) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, fullname, username, email, bio, profile_picture, created_at 
                 FROM users WHERE username = ?'
            );
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile($user_id, $fullname, $bio, $profile_picture = null) {
        try {
            if ($profile_picture) {
                $stmt = $this->pdo->prepare(
                    'UPDATE users SET fullname = ?, bio = ?, profile_picture = ? WHERE id = ?'
                );
                $result = $stmt->execute([$fullname, $bio, $profile_picture, $user_id]);
            } else {
                $stmt = $this->pdo->prepare(
                    'UPDATE users SET fullname = ?, bio = ? WHERE id = ?'
                );
                $result = $stmt->execute([$fullname, $bio, $user_id]);
            }

            return ['success' => $result, 'message' => $result ? 'Profile updated successfully' : 'Update failed'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Search users by username
     */
    public function searchUsers($query, $limit = 10) {
        try {
            $search_term = '%' . $query . '%';
            $stmt = $this->pdo->prepare(
                'SELECT id, fullname, username, profile_picture FROM users 
                 WHERE username LIKE ? OR fullname LIKE ? LIMIT ?'
            );
            $stmt->execute([$search_term, $search_term, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get user posts count
     */
    public function getPostsCount($user_id) {
        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM posts WHERE user_id = ?');
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Get user followers count (using likes as engagement metric)
     */
    public function getEngagementCount($user_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(DISTINCT l.user_id) as count FROM likes l
                 JOIN posts p ON l.post_id = p.id WHERE p.user_id = ?'
            );
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        session_destroy();
        return true;
    }
}
?>
