<?php $page_title = 'Register'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 500px; padding: 30px 15px;">
    <div style="text-align: center; margin-bottom: 25px;">
        <h1 style="font-size: clamp(1.5rem, 6vw, 2rem); font-weight: 700; margin-bottom: 8px;">Join Bondly</h1>
        <p style="color: rgba(224, 231, 255, 0.7); font-size: clamp(0.9rem, 2.5vw, 1rem);">Create your account to connect with others</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo sanitize($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=process_register">
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    class="form-control glass-input" 
                    id="fullname" 
                    name="fullname" 
                    placeholder="Enter your full name"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input 
                    type="text" 
                    class="form-control glass-input" 
                    id="username" 
                    name="username" 
                    placeholder="Choose a unique username"
                    required
                >
                <small style="color: rgba(224, 231, 255, 0.5);">Username must be unique and contain only letters, numbers, and underscores</small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    class="form-control glass-input" 
                    id="email" 
                    name="email" 
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    class="form-control glass-input" 
                    id="password" 
                    name="password" 
                    placeholder="Minimum 6 characters"
                    required
                >
                <small style="color: rgba(224, 231, 255, 0.5);">Use a strong password with letters and numbers</small>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    class="form-control glass-input" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Re-enter your password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <hr style="border-color: rgba(255, 255, 255, 0.1);">

        <p style="text-align: center; margin: 0; color: rgba(224, 231, 255, 0.7);">
            Already have an account?
            <a href="<?php echo BASE_URL; ?>index.php?page=login" style="color: var(--accent-color); text-decoration: none; font-weight: 600;">
                Login here
            </a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
