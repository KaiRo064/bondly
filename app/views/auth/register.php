<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account | Mini Social</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #0f172a; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-card { background: #1e293b; padding: 2.5rem; border-radius: 12px; width: 380px; box-shadow: 0 20px 25px rgba(0,0,0,0.3); }
        .auth-card h2 { margin-bottom: 1.5rem; font-size: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; color: #94a3b8; font-size: 0.9rem; }
        input { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #334155; background: #0f172a; color: white; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; margin-top: 1rem; }
        .switch { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: #6366f1; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold;">MS</div>
            <span style="font-weight: 600;">Mini Social</span>
        </div>
        <h2>Create an account</h2>
        <form action="index.php?action=register_submit" method="POST">
            <div class="form-group">
                <label>Full Name (optional)</label>
                <input type="text" name="full_name" placeholder="alex">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="alex_dev" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="alex@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••" required>
            </div>
            <button type="submit">Create account</button>
        </form>
        <div class="switch">
            Already have an account? <a href="index.php?action=login" style="color: #3b82f6; text-decoration: none;">Sign in</a>
        </div>
    </div>
</body>
</html>