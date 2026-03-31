# Bondly - Mini Social Networking Web Application

**Bondly** is a fully functional mini social networking web application built with PHP, MySQL, and Bootstrap 5. It features a beautiful dark mode glassmorphism design and includes all modern social media features like posts, comments, likes, user profiles, and search functionality.

**Created for:** Saint Michael College of Caraga (SMCC) - 2nd Year Information Systems Final Defense

---

## Features

✨ **Core Features**
- User Authentication (Register, Login, Logout)
- User Profiles with Bio and Profile Pictures
- Create, Read, and Delete Posts
- Like/Heart Posts (1 per user)
- Comment on Posts
- Search Users
- Edit Profile Information
- Session-based Access Control

🎨 **Design**
- Instagram-inspired Dark Mode
- Glassmorphism Aesthetic
- Responsive Design (Mobile, Tablet, Desktop)
- Bootstrap 5 with Custom CSS
- FontAwesome 6 Icons
- Smooth Animations and Transitions

🔒 **Security**
- Password Hashing (password_hash & password_verify)
- XSS Prevention (htmlspecialchars)
- Input Sanitization
- PDO with Prepared Statements
- CSRF Protection Ready

---

## Project Structure

```
bondly/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php       # Authentication logic
│   │   ├── PostController.php       # Post management logic
│   │   └── UserController.php       # User profile logic
│   ├── models/
│   │   ├── User.php                 # User data model
│   │   ├── Post.php                 # Post data model
│   │   ├── Comment.php              # Comment data model
│   │   └── Like.php                 # Like/Heart data model
│   └── views/
│       ├── header.php               # Navigation header
│       ├── footer.php               # Footer
│       ├── login.php                # Login page
│       ├── register.php             # Registration page
│       ├── home.php                 # Newsfeed/Home page
│       ├── create_post.php          # Create post page
│       ├── profile.php              # User profile page
│       ├── edit_profile.php         # Edit profile page
│       └── search.php               # User search page
├── config/
│   └── database.php                 # Database configuration (PDO)
├── public/
│   ├── index.php                    # Main router/entry point
│   ├── assets/
│   │   └── css/
│   │       └── style.css            # Custom CSS
│   └── uploads/
│       ├── profiles/                # User profile pictures
│       └── posts/                   # Post images
├── sql/
│   └── bondly.sql                   # Database schema
└── README.md                        # This file
```

---

## Database Schema

### Tables

**1. Users Table**
```sql
- id (Primary Key)
- username (Unique)
- email (Unique)
- password (Hashed)
- fullname
- bio
- profile_picture
- created_at
- updated_at
```

**2. Posts Table**
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- title
- content
- image
- likes_count
- comments_count
- created_at
- updated_at
```

**3. Comments Table**
```sql
- id (Primary Key)
- post_id (Foreign Key → posts.id)
- user_id (Foreign Key → users.id)
- content
- created_at
- updated_at
```

**4. Likes Table**
```sql
- id (Primary Key)
- post_id (Foreign Key → posts.id)
- user_id (Foreign Key → users.id)
- created_at
- UNIQUE KEY (post_id, user_id) - Prevents duplicate likes
```

---

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web Server (Apache with mod_rewrite)
- Composer (Optional)

### Step 1: Create Database

1. Open phpMyAdmin or MySQL command line
2. Import the SQL schema:
   ```sql
   SOURCE sql/bondly.sql;
   ```
   Or copy content from `sql/bondly.sql` and execute

### Step 2: Configure Connection

Edit `config/database.php` and update:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'bondly');
define('DB_USER', 'root');
define('DB_PASS', '');  // Your MySQL password
```

### Step 3: Set File Permissions

Make sure upload directories are writable:
```bash
chmod 755 public/uploads/profiles/
chmod 755 public/uploads/posts/
```

### Step 4: Access Application

1. Place the bondly folder in your web root (htdocs or public_html)
2. Open in browser: `http://localhost/bondly/public/index.php`
3. Click "Register" to create an account
4. Start using Bondly!

---

## Usage Guide

### Registration & Login
1. Click "Register" to create a new account
2. Enter Full Name, Username, Email, and Password (min 6 characters)
3. Click "Login" to log in with your credentials

### Creating Posts
1. Click "Create Post" in the navigation
2. Add a title (optional) and content
3. Upload an image (optional)
4. Click "Publish Post"

### Interacting with Posts
- **Like**: Click the heart icon (limit 1 per user)
- **Comment**: Enter text in comment box and press send
- **Delete**: Only post owners can delete their own posts

### Profile Management
1. Click "Profile" to view your profile
2. Click "Edit Profile" to update:
   - Full Name
   - Bio
   - Profile Picture
3. View your post statistics and all published posts

### Searching Users
1. Click "Search" in navigation
2. Enter username or full name
3. Click on user card to view their profile

---

## File Upload Details

### Upload Locations
- **Profile Pictures**: `public/uploads/profiles/`
- **Post Images**: `public/uploads/posts/`

### Specifications
- **Max File Size**: 5MB
- **Allowed Formats**: JPG, PNG, GIF
- **File Names**: Auto-generated (uniqid_filename.ext)

---

## Security Features

✅ **Implemented Security**
1. **Password Hashing**: Uses PHP's password_hash() with BCRYPT
2. **XSS Prevention**: All outputs sanitized with htmlspecialchars()
3. **Input Validation**: All inputs validated and trimmed
4. **SQL Injection Prevention**: PDO with prepared statements
5. **Session Security**: Strict session configuration
6. **File Upload Validation**: Type and size validation

---

## Code Quality Standards

✓ **Clean Code Principles**
- Modular MVC Architecture
- Clear separation of concerns
- Well-commented code blocks
- Consistent naming conventions
- Reusable helper functions
- Error handling and exceptions

✓ **Database Best Practices**
- Normalized schema design
- Foreign key constraints
- Indexes on frequently used columns
- Timestamps for audit trails
- Unique constraints

---

## API Structure

### Auth Routes
- `?page=login` - Login page
- `?page=register` - Registration page
- `?action=process_login` - Login processing
- `?action=process_register` - Register processing
- `?action=logout` - Logout

### Post Routes
- `?page=home` - Newsfeed
- `?page=create_post` - Create post form
- `?action=create_post` - Create post processing
- `?action=delete_post&post_id=ID` - Delete post
- `?action=toggle_like&post_id=ID` - Like/unlike post
- `?action=add_comment&post_id=ID` - Add comment
- `?action=delete_comment&comment_id=ID` - Delete comment

### User Routes
- `?page=profile&user=USERNAME` - View user profile
- `?page=profile` - View own profile
- `?page=edit_profile` - Edit profile form
- `?action=update_profile` - Update profile processing
- `?page=search` - Search users

---

## Technologies Used

**Frontend**
- HTML5
- CSS3 (with Glassmorphism)
- Bootstrap 5
- FontAwesome 6
- Vanilla JavaScript

**Backend**
- PHP 7.4+
- MySQL 5.7+
- PDO (PHP Data Objects)

**Architecture**
- MVC (Model-View-Controller)
- RESTful principles

---

## Browser Compatibility

✓ Chrome 90+
✓ Firefox 88+
✓ Safari 14+
✓ Edge 90+
✓ Mobile browsers (iOS Safari, Chrome Mobile)

---

## Future Enhancement Ideas

💡 **Possible Improvements**
- Follow/Unfollow system
- Direct messaging
- Post likes statistics
- User notifications
- Hashtags functionality
- Trending posts
- Dark/Light mode toggle
- Two-factor authentication
- API endpoints for mobile app
- Real-time notifications with WebSockets

---

## Troubleshooting

### Issue: "Access Denied" for database
**Solution**: Check credentials in `config/database.php`

### Issue: Files not uploading
**Solution**: Ensure `public/uploads/` directories have 755 permissions

### Issue: Login not working
**Solution**: Clear browser cookies/session cache

### Issue: CSS not loading
**Solution**: Verify BASE_URL in `config/database.php` matches your folder name

---

## License

This project is created for educational purposes at Saint Michael College of Caraga (SMCC).

---

## Author

Developed as a 2nd Year Information Systems Project

**Contact**: For support or questions, please contact SMCC IT Department

---

## Deployment Checklist

Before deploying to production:

- [ ] Change database credentials
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Disable error display in production
- [ ] Enable HTTPS/SSL
- [ ] Set secure session cookies
- [ ] Regular database backups
- [ ] Monitor upload directories
- [ ] Implement rate limiting
- [ ] Add CSRF tokens

---

**Happy Bonding! 💚**

Welcome to Bondly - Where connections matter.
