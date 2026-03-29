<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bondly | Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b p-4 shadow-sm">
        <div class="max-w-2xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-black text-pink-500">Bondly.</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-bold">
                    Hi, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User' ?>!
                </span>
                <a href="index.php?page=logout" class="text-xs font-bold text-gray-400 hover:text-red-500">LOGOUT</a>
            </div>
        </div>
    </nav>

    <main class="max-w-xl mx-auto py-8">
        <div class="bg-white p-5 rounded-2xl border mb-8">
            <form action="index.php?page=post_action" method="POST">
                <textarea name="content" placeholder="Share a moment..." class="w-full border-none focus:ring-0 text-lg resize-none" required></textarea>
                <div class="text-right border-t pt-2">
                    <button class="bg-pink-500 text-white px-6 py-2 rounded-full font-bold">Post</button>
                </div>
            </form>
        </div>

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="bg-white border rounded-2xl mb-4 p-4 shadow-sm">
                    <p class="font-bold text-sm mb-2"><?= htmlspecialchars($post['username']) ?></p>
                    <p class="text-gray-800"><?= htmlspecialchars($post['content']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center text-gray-400 py-10">No posts yet. Start the conversation!</div>
        <?php endif; ?>
    </main>
</body>
</html>