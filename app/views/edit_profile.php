<?php $page_title = 'Edit Profile'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 600px; padding: 30px 15px;">
    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0; font-size: clamp(1.5rem, 6vw, 2rem); color: white;">Edit Your Profile</h2>
        <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 0 0; font-size: clamp(0.85rem, 2.5vw, 1rem);">Update your information and profile picture</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo sanitize($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=update_profile" enctype="multipart/form-data">
            <!-- Current Profile Picture -->
            <div class="mb-4" style="text-align: center;">
                <div class="profile-picture" style="margin: 0 auto 16px;">
                    <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default-avatar.png'): ?>
                        <img src="<?php echo UPLOADS_URL; ?>profiles/<?php echo sanitize($user['profile_picture']); ?>" alt="Profile">
                    <?php else: ?>
                        <i class="fas fa-user"></i>
                    <?php endif; ?>
                </div>
                <p style="color: rgba(224, 231, 255, 0.7); margin: 0;">@<?php echo sanitize($user['username']); ?></p>
            </div>

            <!-- Profile Picture Upload -->
            <div class="mb-4">
                <label for="profile_picture" class="form-label">
                    <i class="fas fa-camera"></i> Change Profile Picture
                </label>
                <div class="input-group">
                    <input 
                        type="file" 
                        class="form-control glass-input" 
                        id="profile_picture" 
                        name="profile_picture"
                        accept="image/*"
                    >
                </div>
                <small style="color: rgba(224, 231, 255, 0.5);">Maximum 5MB • Supported: JPG, PNG, GIF</small>
                <div id="profile-preview" style="margin-top: 12px;"></div>
            </div>

            <hr style="border-color: rgba(255, 255, 255, 0.1);">

            <!-- Full Name -->
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    class="form-control glass-input" 
                    id="fullname" 
                    name="fullname" 
                    value="<?php echo sanitize($user['fullname']); ?>"
                    required
                >
            </div>

            <!-- Bio -->
            <div class="mb-4">
                <label for="bio" class="form-label">Bio</label>
                <textarea 
                    class="form-control glass-textarea" 
                    id="bio" 
                    name="bio" 
                    rows="4" 
                    placeholder="Tell us about yourself..."
                    style="resize: vertical; font-family: inherit;"
                ><?php echo sanitize($user['bio'] ?? ''); ?></textarea>
                <small style="color: rgba(224, 231, 255, 0.5);">Maximum 500 characters</small>
            </div>

            <hr style="border-color: rgba(255, 255, 255, 0.1);">

            <!-- Account Info (Read-only) -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input 
                    type="email" 
                    class="form-control glass-input" 
                    value="<?php echo sanitize($user['email']); ?>"
                    disabled
                    style="opacity: 0.6;"
                >
                <small style="color: rgba(224, 231, 255, 0.5);">Contact admin to change email</small>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 flex-wrap" style="justify-content: space-between; margin-top: 24px; gap: 10px !important;">
                <a href="<?php echo BASE_URL; ?>index.php?page=profile" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="color: white;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="glass-card" style="margin-top: 24px; background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.3);">
        <h5 style="margin-top: 0; margin-bottom: 12px; color: #fca5a5;">
            <i class="fas fa-exclamation-triangle"></i> Danger Zone
        </h5>
        <p style="color: rgba(224, 231, 255, 0.8); margin: 0 0 12px 0;">
            Be careful with these actions. They cannot be undone.
        </p>
        <button type="button" class="btn btn-danger" onclick="alert('Account deletion feature coming soon!')" style="background: rgba(239, 68, 68, 0.3); border: 1px solid #ef4444; color: #fca5a5;">
            <i class="fas fa-trash"></i> Delete Account
        </button>
    </div>
</div>

<script>
    // Profile picture preview
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const preview = document.getElementById('profile-preview');
        preview.innerHTML = '';

        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.borderRadius = '50%';
                img.style.objectFit = 'cover';
                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
    });
</script>

<?php include 'footer.php'; ?>
