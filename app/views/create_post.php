<?php $page_title = 'Create Post'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 700px; padding: 30px 15px;">
    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0; font-size: clamp(1.5rem, 6vw, 2rem); color: white;">Share Your Thoughts</h2>
        <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 0 0; font-size: clamp(0.85rem, 2.5vw, 1rem);">Create a new post for your followers to see</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo sanitize($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="glass-card">
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=create_post" enctype="multipart/form-data">
            <!-- Post Content -->
            <div class="mb-3">
                <label for="content" class="form-label">What's on your mind?</label>
                <textarea 
                    class="form-control glass-textarea" 
                    id="content" 
                    name="content" 
                    rows="6" 
                    placeholder="Share your thoughts, ideas, or updates..."
                    required
                    style="resize: vertical; font-family: inherit;"
                ></textarea>
                <small style="color: rgba(224, 231, 255, 0.5);" id="char-count">0 / 5000 characters</small>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label for="image" class="form-label">
                    <i class="fas fa-image"></i> Add Image (Optional)
                </label>
                <div class="input-group">
                    <input 
                        type="file" 
                        class="form-control glass-input" 
                        id="image" 
                        name="image"
                        accept="image/*"
                    >
                </div>
                <small style="color: rgba(224, 231, 255, 0.5);">Maximum 5MB • Supported: JPG, PNG, GIF</small>
                <div id="image-preview" style="margin-top: 12px;"></div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex gap-2 flex-wrap" style="justify-content: space-between; gap: 10px !important;">
                <a href="<?php echo BASE_URL; ?>index.php?page=home" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="color: white;">
                    <i class="fas fa-paper-plane"></i> Publish Post
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Character counter
    document.getElementById('content').addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('char-count').textContent = count + ' / 5000 characters';
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = '';

        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                img.style.borderRadius = '12px';
                img.style.marginTop = '12px';
                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
    });
</script>

<?php include 'footer.php'; ?>
