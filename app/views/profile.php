<?php $page_title = 'Profile'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 900px; padding: 30px 15px;">
    <!-- Profile Header -->
    <div class="profile-header">
        <!-- Profile Picture -->
        <div class="profile-picture">
            <?php if ($user['profile_picture'] && $user['profile_picture'] !== 'default-avatar.png'): ?>
                <img src="<?php echo UPLOADS_URL; ?>profiles/<?php echo sanitize($user['profile_picture']); ?>" alt="Profile">
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>

        <!-- User Info -->
        <h2 style="margin: 16px 0 4px 0; font-size: clamp(1.3rem, 5vw, 1.8rem);"><?php echo sanitize($user['fullname']); ?></h2>
        <p style="color: var(--accent-color); margin: 0 0 12px 0; font-size: clamp(0.95rem, 3vw, 1.1rem); word-break: break-word;">@<?php echo sanitize($user['username']); ?></p>

        <!-- Bio -->
        <?php if (!empty($user['bio'])): ?>
            <p style="color: rgba(224, 231, 255, 0.8); max-width: 500px; margin: 12px auto; line-height: 1.6;">
                <?php echo sanitize($user['bio']); ?>
            </p>
        <?php endif; ?>

        <!-- Stats -->
        <div class="profile-stats">
            <div class="stat">
                <div class="stat-value"><?php echo $posts_count; ?></div>
                <div class="stat-label">Posts</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?php echo $engagement_count; ?></div>
                <div class="stat-label">Engagements</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?php echo formatDate($user['created_at']); ?></div>
                <div class="stat-label">Joined</div>
            </div>
        </div>

        <!-- Edit Profile Button (Only for current user) -->
        <?php if ($user['id'] == getCurrentUserId()): ?>
            <div style="margin-top: 20px;">
                <a href="<?php echo BASE_URL; ?>index.php?page=edit_profile" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Posts Section -->
    <div style="margin-top: 30px;">
        <h3 style="margin-bottom: 20px;">
            <i class="fas fa-image"></i> Posts
        </h3>

        <?php if (!empty($posts)): ?>
            <div class="row" id="posts-grid" style="row-gap: 15px;">
                <?php foreach ($posts as $post): ?>
                    <div class="col-12 col-sm-6 col-lg-4 mb-3">
                        <div class="glass-card" style="height: 100%; display: flex; flex-direction: column;">
                            <!-- Post Title -->
                            <?php if (!empty($post['title'])): ?>
                                <h5 style="margin: 0 0 12px 0;"><?php echo sanitize($post['title']); ?></h5>
                            <?php endif; ?>

                            <!-- Post Image -->
                            <?php if ($post['image']): ?>
                                <img 
                                    src="<?php echo UPLOADS_URL; ?>posts/<?php echo sanitize($post['image']); ?>" 
                                    alt="Post image" 
                                    class="post-image"
                                    style="margin-bottom: 12px; height: 200px; object-fit: cover;"
                                >
                            <?php else: ?>
                                <div style="background: rgba(59, 130, 246, 0.1); border-radius: 12px; padding: 40px; text-align: center; margin-bottom: 12px; height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="font-size: 3rem; color: rgba(59, 130, 246, 0.3);"></i>
                                </div>
                            <?php endif; ?>

                            <!-- Post Content Preview -->
                            <p style="flex: 1; color: rgba(224, 231, 255, 0.9); margin: 0 0 12px 0; display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo sanitize(substr($post['content'], 0, 100)); ?>...
                            </p>

                            <!-- Post Stats -->
                            <div class="post-actions" style="display: flex; justify-content: space-between; color: rgba(224, 231, 255, 0.6); font-size: 0.875rem; padding: 12px 0; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                <span><i class="fas fa-heart"></i> <span class="likes-count"><?php echo $post['likes_count']; ?></span> Likes</span>
                                <span><i class="fas fa-comment"></i> <span class="comments-count"><?php echo $post['comments_count']; ?></span> Comments</span>
                            </div>

                            <!-- Action Button -->
                            <a href="<?php echo BASE_URL; ?>index.php?page=home" class="btn btn-secondary btn-sm w-100" style="margin-top: 12px;">
                                View Post
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="glass-card" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 16px; display: block;"></i>
                <h4>No posts yet</h4>
                <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 16px 0;">
                    <?php if ($user['id'] == getCurrentUserId()): ?>
                        Start sharing your thoughts with the community!
                    <?php else: ?>
                        This user hasn't shared any posts yet.
                    <?php endif; ?>
                </p>
                <?php if ($user['id'] == getCurrentUserId()): ?>
                    <a href="<?php echo BASE_URL; ?>index.php?page=create_post" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Post
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
