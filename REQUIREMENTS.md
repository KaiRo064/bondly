# Bondly Requirements & System Specifications

## System Requirements

### Minimum Requirements
- **PHP**: 7.4 or higher (Recommended: 8.0+)
- **MySQL**: 5.7 or higher (Recommended: 8.0+)
- **Server**: Apache 2.4+ with mod_rewrite enabled
- **RAM**: 512MB minimum
- **Disk Space**: 100MB minimum
- **Browser**: Modern browser with JavaScript support

### Recommended Setup
- **PHP**: 8.1 or later
- **MySQL**: 8.0 or later
- **Server**: Nginx or Apache with HTTP/2
- **RAM**: 2GB or more
- **Disk Space**: 500MB or more
- **SSL/TLS**: HTTPS certificate

---

## Required PHP Extensions

```
✓ php-core
✓ php-mysql (or php-mysqli)
✓ php-pdo
✓ php-pdo-mysql
✓ php-gd (for image handling)
✓ php-fileinfo (for file validation)
✓ php-json
✓ php-hash
✓ php-session
```

### Optional but Recommended
```
○ php-opcache (for performance)
○ php-zip (for backups)
○ php-curl (for API calls)
```

---

## Installation Methods

### Method 1: XAMPP (Windows/Mac/Linux)
1. Download XAMPP from https://www.apachefriends.org/
2. Install and start Apache & MySQL
3. Extract bondly to `htdocs/`
4. Import database
5. Access: `http://localhost/bondly/public/`

**XAMPP Path:**
- Windows: `C:\xampp\htdocs\`
- Mac: `/Applications/XAMPP/htdocs/`
- Linux: `/opt/lampp/htdocs/`

---

### Method 2: WAMP (Windows Only)
1. Download WAMP from http://www.wampserver.com/
2. Install and start WampServer
3. Extract bondly to `www/`
4. Import database
5. Access: `http://localhost/bondly/public/`

**WAMP Path:**
- Windows: `C:\wamp\www\`

---

### Method 3: LAMP (Linux Only)
```bash
# Install Apache
sudo apt-get install apache2 apache2-utils libapache2-mod-php

# Install PHP
sudo apt-get install php php-mysql php-pdo

# Install MySQL
sudo apt-get install mysql-server

# Enable modules
a2enmod rewrite
a2enmod php8.1

# Restart Apache
sudo systemctl restart apache2
```

**LAMP Path:** `/var/www/html/`

---

### Method 4: Docker (Advanced)
```dockerfile
FROM php:8.1-apache

# Install dependencies
RUN docker-php-ext-install pdo pdo_mysql

# Copy application
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
```

**Docker Compose:**
```yaml
version: '3.8'
services:
  web:
    build: .
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: bondly
      MYSQL_ROOT_PASSWORD: password
    ports:
      - "3306:3306"
```

---

## Browser Compatibility

### Desktop Browsers
| Browser | Minimum Version | Status |
|---------|-----------------|--------|
| Chrome | 90+ | ✅ Full Support |
| Firefox | 88+ | ✅ Full Support |
| Safari | 14+ | ✅ Full Support |
| Edge | 90+ | ✅ Full Support |
| Opera | 76+ | ✅ Full Support |

### Mobile Browsers
| Browser | Minimum Version | Status |
|---------|-----------------|--------|
| Chrome Mobile | 90+ | ✅ Full Support |
| Safari iOS | 14+ | ✅ Full Support |
| Firefox Mobile | 88+ | ✅ Full Support |
| Samsung Internet | 14+ | ✅ Full Support |

---

## Server Configuration Checklist

### Apache Configuration
```apache
# Enable these modules
LoadModule rewrite_module modules/mod_rewrite.so
LoadModule php_module modules/libphp.so

# Set max file upload
php_value upload_max_filesize 5M
php_value post_max_size 5M

# Set memory limit
php_value memory_limit 128M

# Session timeout
php_value session.gc_maxlifetime 3600
```

### PHP Configuration (php.ini)
```ini
; Basic Settings
max_execution_time = 30
max_input_time = 60
memory_limit = 128M

; File Uploads
upload_max_filesize = 5M
post_max_size = 5M
file_uploads = On

; Sessions
session.save_path = "/tmp"
session.gc_maxlifetime = 3600
session.use_strict_mode = 1
session.use_only_cookies = 1

; Security
expose_php = Off
display_errors = Off
error_reporting = E_ALL

; Database
[MySQL]
default_socket = /var/run/mysqld/mysqld.sock
```

### MySQL Configuration (my.cnf)
```ini
[mysqld]
max_allowed_packet = 256M
max_connections = 100
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

; Performance
query_cache_type = 1
query_cache_size = 64M
```

---

## Directory Permissions

### Correct Permissions

```bash
# Directories
find . -type d -exec chmod 755 {} \;

# Files
find . -type f -exec chmod 644 {} \;

# Writable directories
chmod 777 public/uploads/
chmod 777 public/uploads/profiles/
chmod 777 public/uploads/posts/
```

### Permission Table
| Path | Permissions | Owner | Notes |
|------|-------------|-------|-------|
| / | 755 | root | Root files |
| /public | 755 | www-data | Web accessible |
| /public/uploads | 755 | www-data | Must exist |
| /config | 750 | www-data | Restrict access |
| /app | 755 | www-data | Application code |

---

## Database Requirements

### MySQL Character Set
```sql
-- Ensure UTF-8 support
ALTER DATABASE bondly CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Minimum Database Privileges
```sql
-- User for Bondly
CREATE USER 'bondly'@'localhost' IDENTIFIED BY 'strong_password';

-- Grant privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON bondly.* TO 'bondly'@'localhost';
GRANT CREATE, ALTER, DROP ON bondly.* TO 'bondly'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;
```

---

## Performance Requirements

### For Different User Loads

| Users | Database | RAM | Disk | Bandwidth |
|-------|----------|-----|------|-----------|
| 0-100 | Single DB | 512MB | 100MB | 512Kbps |
| 100-1000 | Single DB | 2GB | 500MB | 5Mbps |
| 1000-10K | DB Cluster | 8GB | 5GB | 50Mbps |
| 10K+ | Distributed | 16GB+ | 50GB+ | 100Mbps+ |

---

## Security Requirements

### SSL/TLS Certificate
- **Recommendation**: Let's Encrypt (Free)
- **Installation**: Certbot for Apache
- **Auto-renewal**: Recommended

### Security Headers (Apache)
```apache
<IfModule mod_headers.c>
    Header set Strict-Transport-Security "max-age=31536000"
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>
```

### Firewall Rules
```
- Allow only: 80 (HTTP), 443 (HTTPS)
- Block: Direct MySQL access
- Block: SSH from unknown IPs
- Allow: Database access from web server only
```

---

## Troubleshooting Installation

### Check PHP Extensions
```bash
php -m | grep -i pdo
php -i | grep "MySQL"
```

### Check MySQL Connection
```bash
mysql -h localhost -u root -p bondly
SHOW DATABASES;
SHOW TABLES;
```

### Test PHP Processing
```bash
# Create test file
echo "<?php phpinfo(); ?>" > test.php
# Check in browser
# http://localhost/test.php
```

### Check File Permissions
```bash
ls -la public/uploads/
# Should see: drwxr-xr-x (755)
```

---

## Performance Optimization

### Enable Caching
```php
// Redis cache (optional)
$redis = new Redis();
$redis->connect('localhost', 6379);
```

### Database Optimization
```sql
-- Add indexes
CREATE INDEX idx_user_id ON posts(user_id);
CREATE INDEX idx_created_at ON posts(created_at);
CREATE INDEX idx_post_id ON comments(post_id);

-- Analyze tables
ANALYZE TABLE users;
ANALYZE TABLE posts;
ANALYZE TABLE comments;
ANALYZE TABLE likes;
```

### PHP Optimization
- Enable PHP OPCache
- Use prepared statements
- Minimize file includes
- Use CDN for static assets

---

## Maintenance Tasks

### Daily
- Monitor error logs
- Check disk space
- Verify backups

### Weekly
- Database optimization
- Log cleanup
- Update checks

### Monthly
- Security patches
- Performance review
- Database backup

### Quarterly
- Database full backup
- Update dependencies
- Security audit

---

## Support & Resources

- **PHP Docs**: https://www.php.net/docs.php
- **MySQL Docs**: https://dev.mysql.com/doc/
- **Bootstrap Docs**: https://getbootstrap.com/docs/
- **Apache Docs**: https://httpd.apache.org/docs/

---

**Installation Complete! Ready to develop! 🚀**
