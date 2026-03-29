<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Bondly | Login</title>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-2xl border border-gray-100 shadow-xl">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black bg-gradient-to-r from-orange-500 to-pink-500 bg-clip-text text-transparent">Bondly.</h1>
            <p class="text-gray-400 mt-2 font-medium">Welcome back!</p>
        </div>

        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-50 text-green-600 p-3 rounded-xl text-xs text-center mb-4 border border-green-100 font-bold">
                Account created! You can now log in.
            </div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-50 text-red-500 p-3 rounded-xl text-xs text-center mb-4 border border-red-100 font-bold">
                Invalid username or password.
            </div>
        <?php endif; ?>

        <form action="index.php?page=login_action" method="POST" class="space-y-4">
            <input type="text" name="username" placeholder="Username" required 
                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-pink-400 outline-none transition">
            
            <input type="password" name="password" placeholder="Password" required 
                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-pink-400 outline-none transition">

            <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-pink-500 text-white font-bold py-3 rounded-xl shadow-lg hover:opacity-90 transition transform active:scale-95">
                Log In
            </button>
        </form>

        <div class="mt-8 text-center border-t pt-6 text-sm">
            <span class="text-gray-500">Don't have an account?</span>
            <a href="index.php?page=register" class="text-pink-600 font-bold hover:underline ml-1">Sign Up</a>
        </div>
    </div>
</body>
</html>