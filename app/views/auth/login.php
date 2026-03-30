<div style="display: flex; justify-content: center; align-items: center; height: 80vh;">
    <div class="glass-card" style="width: 400px;">
        <h2 style="margin-top: 0;">Sign in to Mini Social</h2>
        <form action="index.php?action=login_submit" method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="alex@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Your password" required>
            </div>
            <button type="submit" class="btn-blue">Sign in</button>
        </form>
        <p style="text-align: center; font-size: 0.85rem; color: var(--text-muted); margin-top: 20px;">
            Don't have an account? <a href="index.php?action=register" style="color: var(--primary-blue);">Create one</a>
        </p>
    </div>
</div>