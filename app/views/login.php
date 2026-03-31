<?php $page_title = 'Login'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 500px; padding: 40px 15px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: clamp(1.8rem, 7vw, 2.5rem); font-weight: 700; margin-bottom: 8px;">Bondly</h1>
        <p style="color: rgba(224, 231, 255, 0.7); font-size: clamp(0.95rem, 3vw, 1.1rem);">Welcome to Your Social Circle</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo sanitize($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo sanitize($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=process_login">
            <div class="mb-3">
                <label for="username" class="form-label">Username or Email</label>
                <input 
                    type="text" 
                    class="form-control glass-input" 
                    id="username" 
                    name="username" 
                    placeholder="Enter your username or email"
                    required
                    autofocus
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    class="form-control glass-input" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

        <hr style="border-color: rgba(255, 255, 255, 0.1);">

        <p style="text-align: center; margin: 0; color: rgba(224, 231, 255, 0.7);">
            Don't have an account?
            <a href="<?php echo BASE_URL; ?>index.php?page=register" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">
                Register here
            </a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
