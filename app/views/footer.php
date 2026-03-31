    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Like button functionality
        function toggleLike(postId) {
            const likeBtn = document.querySelector(`[data-post-id="${postId}"]`);
            
            fetch('<?php echo BASE_URL; ?>index.php?action=toggle_like&post_id=' + postId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    likeBtn.classList.toggle('liked');
                    location.reload();
                }
            });
        }

        // Add comment functionality
        function addComment(postId, form) {
            const formData = new FormData(form);
            
            fetch('<?php echo BASE_URL; ?>index.php?action=add_comment&post_id=' + postId, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    form.reset();
                    location.reload();
                }
            });
            
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
