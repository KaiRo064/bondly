<?php include 'layout/header.php'; ?>

<div class="main-layout">
    <aside>
        <div class="glass-card">
            <h3 style="margin-top:0">Create Post</h3>
            <form action="index.php?action=create_post" method="POST" enctype="multipart/form-data">
                <textarea name="content" placeholder="Kya soch rahe ho?" style="height: 120px;" required></textarea>
                <div style="margin: 15px 0;">
                    <input type="file" name="post_image" style="font-size: 0.8rem; color: var(--text-muted);">
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-blue" style="flex: 1;">Post</button>
                    <button type="reset" class="btn-blue" style="background:#334155; flex: 1;">Clear</button>
                </div>
            </form>
            <p style="font-size: 0.7rem; color: #64748b; margin-top: 15px;">Note: Data browser ke localStorage me save hota hai.</p>
        </div>

        <div class="glass-card">
            <h3 style="margin-top:0">Find People</h3>
            <input type="text" id="userSearch" placeholder="Search Person...">
            <div id="searchResults" style="margin-top:10px;"></div>
        </div>
    </aside>

    <main>
        <div class="glass-card" style="display: flex; gap: 10px; align-items: center; padding: 12px 20px;">
            <input type="text" placeholder="Search posts..." style="flex:1">
            <select style="background:#0f172a; color:white; border:1px solid #334155; padding:8px; border-radius:6px;">
                <option>Latest First</option>
            </select>
            <span style="color: var(--text-muted); font-size: 0.9rem;">Posts: <?= count($posts) ?></span>
        </div>

        <?php foreach ($posts as $post): ?>
            <div class="glass-card">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <div style="background: #f59e0b; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        <?= strtoupper($post['full_name'][0]) ?>
                    </div>
                    <div>
                        <div style="font-weight: 600;"><?= htmlspecialchars($post['full_name']) ?></div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?= date('d/m/Y, H:i:s', strtotime($post['created_at'])) ?></div>
                    </div>
                    <div style="margin-left: auto;">
                        <button style="background: #22c55e; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.8rem;">Liked (<?= $post['like_count'] ?>)</button>
                    </div>
                </div>

                <p style="line-height: 1.6;"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                <?php if($post['image']): ?>
                    <img src="assets/uploads/posts/<?= $post['image'] ?>" style="width: 100%; border-radius: 8px; border: 1px solid #334155; margin-top: 10px;">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>
</div>

<?php include 'layout/footer.php'; ?>