<?php include __DIR__ . '/layout/header.php'; ?>

<div class="main-layout" style="max-width: 1000px; margin: 40px auto; display: grid; grid-template-columns: 350px 1fr; gap: 20px;">
    <aside class="glass-card">
        <h3 style="margin-top: 0;">My Profile</h3>
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="assets/uploads/profiles/<?= $user['profile_image'] ?>" 
                 style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--primary-blue); object-fit: cover;">
        </div>
        
        <form action="index.php?action=update_profile" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="current_image" value="<?= $user['profile_image'] ?>">
            
            <label style="font-size: 0.8rem; color: var(--text-muted);">Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            
            <label style="font-size: 0.8rem; color: var(--text-muted);">Bio</label>
            <textarea name="bio" rows="3"><?= htmlspecialchars($user['bio']) ?></textarea>
            
            <label style="font-size: 0.8rem; color: var(--text-muted);">Change Profile Picture</label>
            <input type="file" name="profile_image" style="font-size: 0.8rem;">
            
            <button type="submit" class="btn-blue" style="margin-top: 10px;">Update Profile</button>
        </form>
    </aside>

    <main>
        <div class="glass-card">
            <h3 style="margin-top: 0;">Your Posts</h3>
            <?php if(empty($userPosts)): ?>
                <p style="color: var(--text-muted);">You haven't posted anything yet.</p>
            <?php else: ?>
                <?php foreach($userPosts as $post): ?>
                    <div style="border-bottom: 1px solid rgba(255,255,255,0.1); padding: 15px 0;">
                        <p style="margin-bottom: 5px;"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        <small style="color: var(--text-muted);"><?= $post['created_at'] ?></small>
                        <div style="margin-top: 10px;">
                            <a href="index.php?action=delete_post&id=<?= $post['id'] ?>" 
                               onclick="return confirm('Delete this post?')" 
                               style="color: #ef4444; text-decoration: none; font-size: 0.8rem;">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/layout/footer.php'; ?>