# Bondly Setup & Installation Guide

## Quick Start (5 Minutes)

### Prerequisites
- XAMPP, WAMP, or LAMP server
- PHP 7.4 or later
- MySQL 5.7 or later

---

## Installation Steps

### Step 1: Database Setup

1. **Open phpMyAdmin**
   - Navigate to: `http://localhost/phpmyadmin/`

2. **Create New Database**
   - Click on "New" in the left sidebar
   - Database name: `bondly`
   - Collation: `utf8mb4_general_ci`
   - Click "Create"

3. **Import SQL Schema**
   - Select the `bondly` database
   - Click "Import" tab
   - Select file: `sql/bondly.sql`
   - Click "Go" to import tables

   **Or manually:**
   - Copy contents of `sql/bondly.sql`
   - Paste into SQL query window
   - Execute

### Step 2: Configure Application

1. **Update Database Credentials**
   - Open: `config/database.php`
   - Find these lines:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'bondly');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
   - Update `DB_USER` and `DB_PASS` with your MySQL credentials
   - Save file

2. **Set File Permissions** (Linux/Mac)
   ```bash
   chmod 755 public/uploads/
   chmod 755 public/uploads/profiles/
   chmod 755 public/uploads/posts/
   ```

### Step 3: Launch Application

**Option A: XAMPP/WAMP**
1. Place bondly folder in `htdocs/` (XAMPP) or `www/` (WAMP)
2. Start Apache & MySQL services
3. Open browser: `http://localhost/bondly/public/index.php`

**Option B: Direct Server**
1. Place bondly folder in web root
2. Open: `http://your-domain.com/bondly/public/index.php`

### Step 4: Create First Account

1. Click "Register"
2. Fill in form:
   - Full Name: Your Name
   - Username: Your username
   - Email: your@email.com
   - Password: (min 6 characters)
3. Click "Create Account"
4. Click "Login" with credentials
5. Start using Bondly!

---

## Verification Checklist

✓ Database created and tables imported
✓ Database config updated
✓ Upload folders exist and writable
✓ Application accessible via browser
✓ Registration works
✓ Login successful
✓ Can create posts
✓ Can upload images
✓ Can like and comment
✓ Profile updates work
✓ Search functionality works

---

## Common Issues & Solutions

### Issue 1: "Connection refused" error
**Problem**: Cannot connect to database
**Solution**:
1. Verify MySQL is running
2. Check credentials in `config/database.php`
3. Ensure database name is correct
4. Test with phpMyAdmin

### Issue 2: "Upload failed" error
**Problem**: Cannot upload images
**Solution**:
1. Check folder permissions: `chmod 755 public/uploads/`
2. Verify file size < 5MB
3. Check file format (JPG, PNG, GIF)
4. Ensure PHP upload settings allow files

### Issue 3: "Session error" or stuck at login
**Problem**: Session issues
**Solution**:
1. Clear browser cookies
2. Check PHP session path writable
3. Restart web server
4. Clear browser cache (Ctrl+Shift+Del)

### Issue 4: "Access Denied" for files
**Problem**: Cannot access certain files
**Solution**:
1. Check .htaccess configuration
2. Verify mod_rewrite is enabled (Apache)
3. Check file permissions (644 for files)
4. Run: `a2enmod rewrite` (Linux)

### Issue 5: CSS/Images not loading
**Problem**: Styling appears broken
**Solution**:
1. Check BASE_URL in `config/database.php`
2. Clear browser cache (Ctrl+F5)
3. Verify file paths in HTML
4. Check upload folder structure

---

## For the Defense Presentation

### Demo Account
Create these accounts before (or during) the presentation:

**Account 1:**
- Username: `demo_user1`
- Password: `password123`

**Account 2:**
- Username: `demo_user2`
- Password: `password123`

**Pre-create some sample posts** so you have content to show during demo

### What to Demonstrate

1. **Authentication**
   - Show registration form
   - Show password validation
   - Demonstrate login

2. **Posts**
   - Create a post with text only
   - Create a post with image
   - Show post comments
   - Like/unlike posts
   - Delete own post

3. **Profile**
   - Show user profile page
   - Edit profile (change name, bio, picture)
   - Show post statistics

4. **Search**
   - Search for users
   - Click to view other user profiles

5. **Security**
   - Show XSS prevention (try `<script>` in forms)
   - Show input validation
   - Mention password hashing

---

## Project Files Explanation

### Models (app/models/)
- `User.php` - User registration, login, profile
- `Post.php` - CRUD operations for posts
- `Comment.php` - Comment management
- `Like.php` - Like/heart functionality

### Controllers (app/controllers/)
- `AuthController.php` - Login/Register logic
- `PostController.php` - Post operations
- `UserController.php` - Profile management

### Views (app/views/)
- `header.php` - Navigation & styles
- `footer.php` - Footer & scripts
- `login.php` - Login page
- `register.php` - Registration page
- `home.php` - Newsfeed
- `create_post.php` - Post creation
- `profile.php` - User profile
- `edit_profile.php` - Profile editing
- `search.php` - User search

### Configuration
- `config/database.php` - DB connection & helpers
- `public/index.php` - Router & entry point
- `sql/bondly.sql` - Database schema

---

## Additional Commands

### Reset Database
```bash
# In MySQL
DROP DATABASE bondly;
CREATE DATABASE bondly;
# Then re-import sql/bondly.sql
```

### Clear Sessions
```bash
# Navigate to session directory
cd /var/lib/php/sessions  # Linux
# Delete session files
rm sess_*
```

### Check Database Tables
```sql
-- In phpMyAdmin SQL tab
USE bondly;
SHOW TABLES;
DESCRIBE users;
DESCRIBE posts;
DESCRIBE comments;
DESCRIBE likes;
```

---

## Performance Tips

For Production:
1. Enable query caching in MySQL
2. Add indexes to frequently searched fields
3. Implement pagination for feeds
4. Compress images on upload
5. Use CDN for static files
6. Enable gzip compression
7. Cache database connections
8. Minify CSS and JavaScript

---

## Support

For issues:
1. Check error logs: `error.log`
2. Review database status
3. Check file permissions
4. Verify configuration
5. Clear cache and try again

---

**Good luck with your presentation! 🎉**
