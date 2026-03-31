<?php $page_title = 'Search'; include 'header.php'; ?>

<div class="container-fluid" style="max-width: 900px; padding: 30px 15px;">
    <div style="margin-bottom: 30px;">
        <h2 style="margin: 0; font-size: clamp(1.5rem, 6vw, 2rem);">Search Bondly</h2>
        <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 0 0; font-size: clamp(0.85rem, 2.5vw, 1rem);">Find users and posts on the network</p>
    </div>

    <!-- Search Form -->
    <div class="glass-card" style="margin-bottom: 30px;">
        <form method="GET" action="">
            <input type="hidden" name="page" value="search">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <input 
                    type="text" 
                    class="form-control glass-input" 
                    name="q" 
                    placeholder="Search posts, users, or hashtags..."
                    value="<?php echo sanitize($query); ?>"
                    autofocus
                    style="flex: 1; min-width: 200px; min-width: 0;"
                >
                <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            
            <!-- Filter Tabs -->
            <?php if (!empty($query)): ?>
                <div style="margin-top: 16px; display: flex; gap: 8px; flex-wrap: wrap; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 16px;">
                    <a href="<?php echo BASE_URL; ?>index.php?page=search&q=<?php echo urlencode($query); ?>&type=all" class="btn <?php echo (!isset($_GET['type']) || $_GET['type'] === 'all') ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 16px; font-size: clamp(0.75rem, 2vw, 0.9rem); flex: 1;">
                        All
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?page=search&q=<?php echo urlencode($query); ?>&type=users" class="btn <?php echo (isset($_GET['type']) && $_GET['type'] === 'users') ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 16px; font-size: clamp(0.75rem, 2vw, 0.9rem); flex: 1;">
                        Users
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?page=search&q=<?php echo urlencode($query); ?>&type=posts" class="btn <?php echo (isset($_GET['type']) && $_GET['type'] === 'posts') ? 'btn-primary' : 'btn-secondary'; ?>" style="padding: 8px 16px; font-size: clamp(0.75rem, 2vw, 0.9rem); flex: 1;">
                        Posts
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Search Results -->
    <?php if (!empty($query)): ?>
        <!-- Users Results -->
        <?php if (!empty($user_results)): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">
                    <i class="fas fa-users"></i> Users (<?php echo count($user_results); ?>)
                </h3>
                <div class="row" style="row-gap: 15px;">
                    <?php foreach ($user_results as $result): ?>
                        <div class="col-12 col-sm-6 col-lg-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>index.php?page=profile&user=<?php echo sanitize($result['username']); ?>" style="text-decoration: none; color: inherit;">
                                <div class="user-card">
                                    <div class="user-card-avatar">
                                        <?php if ($result['profile_picture'] && $result['profile_picture'] !== 'default-avatar.png'): ?>
                                            <img src="<?php echo UPLOADS_URL; ?>profiles/<?php echo sanitize($result['profile_picture']); ?>" alt="Avatar">
                                        <?php else: ?>
                                            <i class="fas fa-user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <h5 style="margin: 12px 0 4px 0;"><?php echo sanitize($result['fullname']); ?></h5>
                                    <p style="color: var(--accent-color); margin: 0; font-weight: 500;">@<?php echo sanitize($result['username']); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Posts Results -->
        <?php if (!empty($post_results)): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="margin-bottom: 20px;">
                    <i class="fas fa-image"></i> Posts (<?php echo count($post_results); ?>)
                </h3>
                <div>
                    <?php foreach ($post_results as $post): ?>
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
                                    <div>
                                        <a href="<?php echo BASE_URL; ?>index.php?page=profile&user=<?php echo sanitize($post['username']); ?>" class="post-username">
                                            <?php echo sanitize($post['fullname']); ?>
                                        </a>
                                        <span class="post-time">@<?php echo sanitize($post['username']); ?> • <?php echo timeAgo($post['created_at']); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Title -->
                            <?php if (!empty($post['title'])): ?>
                                <h4 style="margin: 12px 0; color: var(--text-light); word-break: break-word;"><?php echo sanitize($post['title']); ?></h4>
                            <?php endif; ?>

                            <!-- Post Content -->
                            <div class="post-content" style="word-break: break-word;">
                                <?php echo sanitize(substr($post['content'], 0, 200)); ?>
                                <?php if (strlen($post['content']) > 200): ?>
                                    <a href="<?php echo BASE_URL; ?>index.php?page=home" style="color: var(--accent-color);">... more</a>
                                <?php endif; ?>
                            </div>

                            <!-- Post Image -->
                            <?php if ($post['image']): ?>
                                <img src="<?php echo UPLOADS_URL; ?>posts/<?php echo sanitize($post['image']); ?>" alt="Post image" class="post-image" style="max-height: 250px;">
                            <?php endif; ?>

                            <!-- Post Stats -->
                            <div style="display: flex; justify-content: space-around; color: rgba(224, 231, 255, 0.6); font-size: 0.875rem; margin-top: 12px; padding: 12px 0; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                                <span><i class="fas fa-heart"></i> <?php echo $post['likes_count']; ?> Likes</span>
                                <span><i class="fas fa-comment"></i> <?php echo $post['comments_count']; ?> Comments</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- No Results -->
        <?php if (empty($user_results) && empty($post_results)): ?>
            <div class="glass-card" style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-search" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 16px; display: block;"></i>
                <h4>No results found</h4>
                <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 0 0;">
                    Try searching with different keywords
                </p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <!-- No search query -->
        <div class="glass-card" style="text-align: center; padding: 60px 20px;">
            <i class="fas fa-binoculars" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 16px; display: block;"></i>
            <h4>Start exploring</h4>
            <p style="color: rgba(224, 231, 255, 0.7); margin: 8px 0 0 0;">
                Search for users or posts to explore the Bondly community
            </p>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
