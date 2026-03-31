# Bondly Developer Guide

## Architecture Overview

Bondly follows a **Model-View-Controller (MVC)** architecture pattern:

```
Request → Router (index.php) → Controller → Model → Response
                                   ↓
                                 View
```

### Control Flow

1. **User makes request** → `public/index.php?page=home`
2. **Router parses request** → Determines the page/action
3. **Controller instantiates Models** → Fetches data from database
4. **Model queries database** → Returns data with PDO
5. **Controller passes data to View** → Renders HTML
6. **View displays page** → User sees the result

---

## Code Standards & Conventions

### Naming Conventions

**Files & Classes:**
```php
// Class files: PascalCase
User.php
PostController.php
CommentModel.php

// Methods: camelCase
public function getUserById()
public function createPost()
public function updateProfile()

// Variables: snake_case
$user_id = 1;
$post_title = "Hello World";
$likes_count = 0;

// Constants: UPPER_SNAKE_CASE
define('DB_HOST', 'localhost');
define('MAX_UPLOAD_SIZE', 5242880);
```

### Code Style

**Indentation:** 4 spaces (not tabs)

**Braces:**
```php
// Control structures
if ($condition) {
    // Do something
} else {
    // Do something else
}

// Functions
public function getUserById($id) {
    // Implementation
}

// Classes
class User {
    // Properties and methods
}
```

**Comments:**
```php
/**
 * Multi-line function documentation
 * 
 * @param int $id User ID
 * @return array User data
 */
public function getUserById($id) {
    // Single-line comment for logic
    $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = ?');
    
    // Another comment
    $stmt->execute([$id]);
    return $stmt->fetch();
}
```

---

## Adding New Features

### Example: Add a Follow Feature

#### Step 1: Create Database Migration

```sql
-- Add to sql/bondly.sql
CREATE TABLE follows (
    id INT PRIMARY KEY AUTO_INCREMENT,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_follow (follower_id, following_id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### Step 2: Create Model

```php
// app/models/Follow.php
class Follow {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function follow($follower_id, $following_id) {
        $stmt = $this->pdo->prepare(
            'INSERT INTO follows (follower_id, following_id) VALUES (?, ?)'
        );
        return $stmt->execute([$follower_id, $following_id]);
    }

    public function unfollow($follower_id, $following_id) {
        $stmt = $this->pdo->prepare(
            'DELETE FROM follows WHERE follower_id = ? AND following_id = ?'
        );
        return $stmt->execute([$follower_id, $following_id]);
    }

    public function getFollowers($user_id) {
        $stmt = $this->pdo->prepare(
            'SELECT u.* FROM users u 
             JOIN follows f ON u.id = f.follower_id 
             WHERE f.following_id = ?'
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }
}
```

#### Step 3: Add Controller Methods

```php
// In app/controllers/UserController.php
require_once __DIR__ . '/../models/Follow.php';

private $follow_model;

public function __construct($pdo) {
    // ... existing code
    $this->follow_model = new Follow($pdo);
}

public function follow($user_id) {
    $result = $this->follow_model->follow(getCurrentUserId(), $user_id);
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}
```

#### Step 4: Update Routes

```php
// In public/index.php
elseif ($action === 'follow') {
    $user_id = $_GET['user_id'] ?? null;
    $user_controller->follow($user_id);
}
```

#### Step 5: Add UI Elements

```php
// In app/views/profile.php - Add follow button
<?php if ($user['id'] != getCurrentUserId()): ?>
    <button onclick="followUser(<?php echo $user['id']; ?>)" class="btn btn-primary">
        Follow
    </button>
<?php endif; ?>

<script>
function followUser(userId) {
    fetch('<?php echo BASE_URL; ?>index.php?action=follow&user_id=' + userId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Followed successfully!');
                location.reload();
            }
        });
}
</script>
```

---

## Database Operations

### Using PDO (Prepared Statements)

**Always use prepared statements to prevent SQL injection:**

```php
// ✅ CORRECT - Using prepared statements
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

// ❌ WRONG - String concatenation (SQL Injection vulnerable)
$sql = "SELECT * FROM users WHERE email = '" . $email . "'";
$result = $pdo->query($sql);
```

### Common Operations

**Select:**
```php
// Single record
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$id]);
$user = $stmt->fetch(); // Returns associative array

// Multiple records
$stmt = $pdo->prepare('SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(); // Returns array of records

// Count
$stmt = $pdo->prepare('SELECT COUNT(*) as count FROM posts WHERE user_id = ?');
$stmt->execute([$user_id]);
$result = $stmt->fetch();
$count = $result['count'];
```

**Insert:**
```php
$stmt = $pdo->prepare(
    'INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)'
);
$result = $stmt->execute([$user_id, $title, $content]);

// Get last inserted ID
$last_id = $pdo->lastInsertId();
```

**Update:**
```php
$stmt = $pdo->prepare(
    'UPDATE users SET fullname = ?, bio = ? WHERE id = ?'
);
$result = $stmt->execute([$fullname, $bio, $user_id]);

// Check if successful
if ($result) {
    echo "Updated successfully";
}
```

**Delete:**
```php
$stmt = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
$result = $stmt->execute([$post_id, $user_id]);
```

**Transactions:**
```php
try {
    $pdo->beginTransaction();
    
    // Multiple operations
    $stmt1 = $pdo->prepare('DELETE FROM likes WHERE post_id = ?');
    $stmt1->execute([$post_id]);
    
    $stmt2 = $pdo->prepare('DELETE FROM comments WHERE post_id = ?');
    $stmt2->execute([$post_id]);
    
    $stmt3 = $pdo->prepare('DELETE FROM posts WHERE id = ?');
    $stmt3->execute([$post_id]);
    
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error: " . $e->getMessage();
}
```

---

## Security Best Practices

### Input Validation

```php
// ✅ CORRECT - Validate and sanitize
$username = validateInput($_POST['username'] ?? '');
if (empty($username) || strlen($username) < 3) {
    $error = "Username must be at least 3 characters";
}

// ❌ WRONG - No validation
$username = $_POST['username'];
```

### Output Escaping

```php
// ✅ CORRECT - Escape all output
<p><?php echo sanitize($user['bio']); ?></p>

// ❌ WRONG - No escaping (XSS vulnerability)
<p><?php echo $user['bio']; ?></p>
```

### Password Security

```php
// ✅ CORRECT - Use password_hash
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// ✅ CORRECT - Verify password
if (password_verify($password, $user['password_hash'])) {
    // Password is correct
}

// ❌ WRONG - Storing plain text
$password = $_POST['password'];
```

### File Upload Security

```php
// ✅ CORRECT - Validate file uploads
$allowed = ['jpg' => 'image/jpeg', 'png' => 'image/png'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!array_key_exists($ext, $allowed)) {
    $error = "Invalid file type";
}

if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
    $error = "File too large";
}

// ❌ WRONG - No validation
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
```

---

## Debugging & Logging

### Error Handling

```php
// Enable error reporting in development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Use try-catch blocks
try {
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "Database error occurred";
}
```

### Logging

```php
// Log important events
function log_event($message, $type = 'info') {
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] [$type] $message\n";
    error_log($log_message, 3, 'logs/app.log');
}

// Usage
log_event('User ' . $user_id . ' logged in', 'auth');
log_event('Post creation failed: ' . $e->getMessage(), 'error');
```

### Debugging with var_dump

```php
// Only in development
if (APP_DEBUG) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
```

---

## Testing Checklist

### Manual Testing

- [ ] User registration (valid/invalid inputs)
- [ ] User login (correct/incorrect credentials)
- [ ] Password reset
- [ ] Create post (text only, with image)
- [ ] Edit post
- [ ] Delete post
- [ ] Like/unlike post
- [ ] Add/delete comment
- [ ] Edit profile
- [ ] Upload profile picture
- [ ] Search users
- [ ] Mobile responsiveness

### Security Testing

- [ ] XSS prevention (try `<script>alert('xss')</script>`)
- [ ] SQL injection (try `' OR '1'='1`)
- [ ] CSRF protection
- [ ] File upload validation
- [ ] Session hijacking prevention

---

## Performance Optimization Tips

### Database Optimization

```php
// Use indexes
CREATE INDEX idx_user_id ON posts(user_id);
CREATE INDEX idx_created_at ON posts(created_at);

// Optimize queries
// Bad: SELECT * FROM posts WHERE user_id = 1
// Good: SELECT id, title, created_at FROM posts WHERE user_id = 1

// Pagination
$offset = ($page - 1) * $limit;
$stmt = $pdo->prepare('SELECT * FROM posts LIMIT ? OFFSET ?');
$stmt->execute([$limit, $offset]);
```

### Caching

```php
// Cache user data
$cache_key = 'user_' . $user_id;
if (apcu_exists($cache_key)) {
    $user = apcu_fetch($cache_key);
} else {
    $user = $this->getUserById($user_id);
    apcu_store($cache_key, $user, 3600); // Cache for 1 hour
}
```

### Code Optimization

```php
// Minimize database queries
// Bad: Multiple queries in a loop
foreach ($posts as $post) {
    $comments = getAllComments($post['id']); // Query inside loop!
}

// Good: Fetch all related data at once
$posts_with_comments = getPostsWithComments($user_id);
```

---

## Extending the Application

### Adding Email Functionality

```php
// Use PHPMailer or similar
use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->setFrom('noreply@bondly.local', 'Bondly');
$mail->addAddress($user_email);
$mail->Subject = 'Verify Your Email';
$mail->Body = 'Click here to verify...';
$mail->send();
```

### Adding API Endpoints

```php
// api/posts.php
header('Content-Type: application/json');

$action = $_GET['action'] ?? null;

if ($action === 'get_posts') {
    $posts = $post_model->getAllPosts();
    echo json_encode($posts);
} elseif ($action === 'create_post') {
    $data = json_decode(file_get_contents('php://input'), true);
    $result = $post_model->createPost($data);
    echo json_encode($result);
}
```

### Adding Admin Panel

```
app/
├── admin/
│   ├── controllers/
│   ├── views/
│   └── dashboard.php
```

---

## Database Relationships Diagram

```
users
├── 1 ─────→ N posts
├── 1 ─────→ N comments
├── 1 ─────→ N likes
└── 1 ─────→ N follows (follower)

posts
├── N ─────→ 1 users
├── 1 ─────→ N comments
├── 1 ─────→ N likes
└── created_at: INDEX

comments
├── N ─────→ 1 posts
├── N ─────→ 1 users
└── created_at: INDEX

likes
├── N ─────→ 1 posts
├── N ─────→ 1 users
└── UNIQUE (post_id, user_id)

follows
├── N ─────→ 1 users (follower)
├── N ─────→ 1 users (following)
└── UNIQUE (follower_id, following_id)
```

---

## Version History

### v1.0.0 (Current)
- Initial release
- Core features implemented
- Authentication system
- Post management
- Comments and likes
- User profiles
- Search functionality

---

## Common Errors & Solutions

| Error | Cause | Solution |
|-------|-------|----------|
| PDOException | DB connection failed | Check credentials in `config/database.php` |
| Session error | Session not started | Call `session_start()` |
| File not found | Wrong path | Use `__DIR__` for absolute paths |
| Function not defined | Missing require | Add `require_once` statement |

---

## Resources

- **PHP Manual**: https://www.php.net/manual/
- **PDO Documentation**: https://www.php.net/manual/en/book.pdo.php
- **Bootstrap Components**: https://getbootstrap.com/docs/5.0/components/
- **FontAwesome Icons**: https://fontawesome.com/icons

---

**Happy Coding! 💻**
