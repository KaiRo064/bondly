    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Like button functionality
        function toggleLike(postId) {
            const likeBtn = document.querySelector(`[data-post-id="${postId}"]`);
            if (!likeBtn) return;

            const countEl = likeBtn.querySelector('span');
            const oldCount = parseInt(countEl?.textContent || '0', 10);

            fetch('<?php echo BASE_URL; ?>index.php?action=toggle_like&post_id=' + postId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newState = !likeBtn.classList.contains('liked');
                    likeBtn.classList.toggle('liked', newState);

                    const delta = newState ? 1 : -1;
                    const updatedCount = Math.max(0, oldCount + delta);
                    if (countEl) {
                        countEl.textContent = updatedCount;
                    }
                } else {
                    console.error('Like failed:', data.message || 'Unknown error');
                }
            })
            .catch(err => console.error('Like request failed', err));
        }

        // Add comment functionality
        function addComment(postId, form) {
            const formData = new FormData(form);
            const textInput = form.querySelector('input[name="comment"]');

            if (!textInput || !textInput.value.trim()) {
                return false;
            }

            fetch('<?php echo BASE_URL; ?>index.php?action=add_comment&post_id=' + postId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.comment) {
                    const commentsSection = document.getElementById('comments-section-' + postId);
                    if (commentsSection) {
                        commentsSection.style.display = 'block';
                        const commentsNotice = commentsSection.querySelector('div[style*="No comments yet"]');
                        if (commentsNotice) commentsNotice.remove();
                        const comment = document.createElement('div');
                        comment.className = 'comment';

                        const avatar = document.createElement('div');
                        avatar.className = 'comment-avatar';
                        avatar.style.flexShrink = '0';

                        if (data.comment.profile_picture && data.comment.profile_picture !== 'default-avatar.png') {
                            const img = document.createElement('img');
                            img.src = '<?php echo UPLOADS_URL; ?>profiles/' + encodeURIComponent(data.comment.profile_picture);
                            img.alt = 'Avatar';
                            avatar.appendChild(img);
                        } else {
                            const icon = document.createElement('i');
                            icon.className = 'fas fa-user';
                            avatar.appendChild(icon);
                        }

                        const body = document.createElement('div');
                        body.className = 'comment-body';
                        body.style.minWidth = '0';
                        body.style.flex = '1';

                        const header = document.createElement('div');
                        header.style.display = 'flex';
                        header.style.flexWrap = 'wrap';
                        header.style.gap = '4px';
                        header.style.alignItems = 'center';

                        const userLink = document.createElement('a');
                        userLink.href = '<?php echo BASE_URL; ?>index.php?page=profile&user=' + encodeURIComponent(data.comment.username);
                        userLink.className = 'comment-user';
                        userLink.textContent = data.comment.fullname;

                        const commentTime = document.createElement('span');
                        commentTime.className = 'comment-time';
                        commentTime.textContent = 'just now';

                        header.appendChild(userLink);
                        header.appendChild(commentTime);

                        if (data.comment.user_id === <?php echo getCurrentUserId(); ?>) {
                            const deleteBtn = document.createElement('button');
                            deleteBtn.textContent = 'Delete';
                            deleteBtn.style.background = 'none';
                            deleteBtn.style.border = 'none';
                            deleteBtn.style.color = '#ef4444';
                            deleteBtn.style.cursor = 'pointer';
                            deleteBtn.style.fontSize = '0.75rem';
                            deleteBtn.style.padding = '0';
                            deleteBtn.style.marginLeft = 'auto';
                            deleteBtn.addEventListener('click', function() {
                                deleteComment(data.comment.id);
                            });
                            header.appendChild(deleteBtn);
                        }

                        const content = document.createElement('div');
                        content.className = 'comment-text';
                        content.textContent = data.comment.content;

                        body.appendChild(header);
                        body.appendChild(content);

                        comment.appendChild(avatar);
                        comment.appendChild(body);

                        const commentsHeader = commentsSection.querySelector('h6');
                        if (commentsHeader) {
                            commentsSection.insertBefore(comment, commentsHeader.nextSibling);
                        } else {
                            commentsSection.appendChild(comment);
                        }
                        commentsSection.style.display = 'block';
                    }

                    const postCard = document.querySelector(`#comment-form-${postId}`)?.closest('.post-card') || document.querySelector(`#comment-form-${postId}`)?.closest('.glass-card');
                    const commentCountEl = postCard?.querySelector('.post-actions .comments-count');
                    if (commentCountEl && data.comments_count !== undefined) {
                        commentCountEl.textContent = data.comments_count;
                    }

                    form.reset();
                } else {
                    console.error('Failed to add comment', data.message || data);
                }
            })
            .catch(err => console.error('Comment request failed', err));

            return false;
        }

        // Delete comment functionality
        function deleteComment(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                fetch('<?php echo BASE_URL; ?>index.php?action=delete_comment&comment_id=' + commentId, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                });
            }
        }

        // Delete post functionality
        function deletePost(postId) {
            if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                window.location.href = '<?php echo BASE_URL; ?>index.php?action=delete_post&post_id=' + postId;
            }
        }
    </script>
</body>
</html>
