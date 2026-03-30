<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bondly | SMCC Mini Social</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="top-nav glass-card" style="border-radius: 0; margin-bottom: 0; border-top: none;">
        <div class="nav-container" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 10px 20px;">
            <div class="logo-area" style="display: flex; align-items: center; gap: 10px;">
                <div style="background: #6366f1; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">B</div>
                <span style="font-weight: 600; font-size: 1.2rem;">Bondly</span>
            </div>
            
            <div class="nav-links">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span style="margin-right: 15px; color: var(--text-muted);">Hi, <?= $_SESSION['full_name'] ?></span>
                    <a href="index.php?action=logout" style="color: #ef4444; text-decoration: none; font-weight: 600;">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>