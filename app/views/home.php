<?php $page_title = 'Home'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 900px; padding: 30px 15px;">
    <!-- Posts Feed -->
    <div class="posts-feed">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <!-- Post Header -->
                    <div class="post-header">
                        <div class="post-user-info">
                            <div class="post-avatar">
                                <?php if ($post['profile_picture'] && $post['profile_picture'] !== 'default-avatar.png'): ?>
                                    <img src="<?php echo UPLOADS_URL; ?>profiles/<?php echo sanitize($post['profile_picture']); ?>" alt="Avatar">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div style="min-width: 0; flex: 1;">
                                <a href="<?php echo BASE_URL; ?>index.php?page=profile&user=<?php echo sanitize($post['username']); ?>" class="post-username" style="display: block; word-break: break-word;">
                                    <?php echo sanitize($post['fullname']); ?>
                                </a>
                                <span class="post-time">@<?php echo sanitize($post['username']); ?> • <?php echo timeAgo($post['created_at']); ?></span>
                            </div>
                        </div>
                        
                        <?php if ($post['user_id'] == getCurrentUserId()): ?>
                            <button onclick="deletePost(<?php echo $post['id']; ?>)" class="btn-icon" title="Delete post" style="flex-shrink: 0;">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Post Title -->
                    <?php if (!empty($post['title'])): ?>
                        <h4 style="margin: 12px 0; color: var(--text-light); word-break: break-word;"><?php echo sanitize($post['title']); ?></h4>
                    <?php endif; ?>

                    <!-- Post Content -->
                    <div class="post-content" style="word-break: break-word;">
                        <?php echo sanitize($post['content']); ?>
                    </div>

                    <!-- Post Image -->
                    <?php if ($post['image']): ?>
                        <img src="<?php echo UPLOADS_URL; ?>posts/<?php echo sanitize($post['image']); ?>" alt="Post image" class="post-image" style="width: 100%; border-radius: 12px; margin: 12px 0;">
                    <?php endif; ?>

                    <!-- Post Actions -->
                    <div class="post-actions">
                        <?php $likedClass = $post['liked'] ? 'liked' : ''; ?>
                        <button 
                            class="action-btn like-btn <?php echo $likedClass; ?>" 
                            onclick="toggleLike(<?php echo $post['id']; ?>)"
                            data-post-id="<?php echo $post['id']; ?>"
                        >
                            <i class="<?php echo $post['liked'] ? 'fas' : 'far'; ?> fa-heart"></i>
                            <span><?php echo $post['likes_count']; ?></span>
                        </button>
                        <button class="action-btn" onclick="document.getElementById('comment-form-<?php echo $post['id']; ?>').scrollIntoView({behavior: 'smooth'})">
                            <i class="far fa-comment"></i>
                            <span><?php echo $post['comments_count']; ?></span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <?php if (!empty($post['comments'])): ?>
                        <div class="comments-section">
                            <h6 style="margin-bottom: 16px; color: rgba(224, 231, 255, 0.8); font-size: 0.95rem;">Comments</h6>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="comment">
                                    <div class="comment-avatar" style="flex-shrink: 0;">
                                        <?php if ($comment['profile_picture'] && $comment['profile_picture'] !== 'default-avatar.png'): ?>
                                            <img src="<?php echo UPLOADS_URL; ?>profiles/<?php echo sanitize($comment['profile_picture']); ?>" alt="Avatar">
                                        <?php else: ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="comment-body" style="min-width: 0; flex: 1;">
                                        <div style="display: flex; flex-wrap: wrap; gap: 4px; align-items: center;">
                                            <a href="<?php echo BASE_URL; ?>index.php?page=profile&user=<?php echo sanitize($comment['username']); ?>" class="comment-user">
                                                <?php echo sanitize($comment['fullname']); ?>
                                            </a>
                                            <span class="comment-time"><?php echo timeAgo($comment['created_at']); ?></span>
                                            
                                            <?php if ($comment['user_id'] == getCurrentUserId()): ?>
                                                <button onclick="deleteComment(<?php echo $comment['id']; ?>)" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.75rem; padding: 0; margin-left: auto;">
                                                    Delete
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-text"><?php echo sanitize($comment['content']); ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Add Comment Form -->
                    <div id="comment-form-<?php echo $post['id']; ?>" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                        <form onsubmit="return addComment(<?php echo $post['id']; ?>, this)">
                            <div style="display: flex; gap: 8px; flex-direction: row;">
                                <input 
                                    type="text" 
                                    name="comment" 
                                    placeholder="Add a comment..." 
                                    class="form-control glass-input" 
                                    style="flex: 1; min-width: 0;"
                                    required
                                >
                                <button type="submit" class="btn btn-primary" style="flex-shrink: 0; padding: 10px 16px;">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="glass-card" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 16px; display: block;"></i>
                <h4>No posts yet</h4>
                <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 16px 0;">
                    Be the first to share something with the community!
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?page=create_post" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Post
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
