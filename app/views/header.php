<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? sanitize($page_title) . ' - ' : ''; ?>Bondly | Mini Social Network</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome 6 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">

    <style>
        :root {
            --primary-color: #020617;
            --secondary-color: #0f172a;
            --accent-color: #3b82f6;
            --text-light: #e0e7ff;
            --text-dark: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(2, 6, 23, 0.85) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: white !important;
            transition: all 0.3s ease;
            margin: 0 8px;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: var(--accent-color) !important;
            border-bottom: 2px solid var(--accent-color);
        }

        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .glass-card:hover {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.1);
        }

        .glass-input, .glass-textarea, .glass-select {
            background: rgba(30, 41, 59, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
            padding: 12px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        /* File input styling */
        .form-control[type="file"]::file-selector-button {
            border-radius: 12px;
            padding: 8px 16px;
            border: none;
            background: var(--accent-color);
            color: white;
            cursor: pointer;
            font-weight: 600;
            margin-right: 12px;
        }

        .glass-input:focus, .glass-textarea:focus, .glass-select:focus {
            background: rgba(30, 41, 59, 0.8) !important;
            border-color: var(--accent-color) !important;
            color: var(--text-light) !important;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
        }

        .glass-input::placeholder, .glass-textarea::placeholder {
            color: rgba(224, 231, 255, 0.5) !important;
        }

        /* File input styling */
        .form-control[type="file"]::file-selector-button {
            border-radius: 12px;
            padding: 8px 16px;
            border: none;
            background: var(--accent-color);
            color: white;
            cursor: pointer;
            font-weight: 600;
            margin-right: 12px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color), #60a5fa) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 10px 24px !important;
            font-weight: 600 !important;
            color: white !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3) !important;
            color: white !important;
        }

        .btn-secondary {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid var(--accent-color);
            color: var(--accent-color);
            border-radius: 12px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--accent-color);
            color: white;
        }

        .btn-icon {
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
        }

        .btn-icon:hover {
            transform: scale(1.2);
            color: #60a5fa;
        }

        .btn-icon.liked {
            color: #ef4444;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem 0;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem 0;
            }
        }

        /* Post Card */
        .post-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .post-card:hover {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .post-user-info {
            display: flex;
            align-items: center;
        }

        .post-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
            overflow: hidden;
        }

        .post-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-username {
            font-weight: 700;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .post-username:hover {
            color: var(--accent-color);
        }

        .post-time {
            display: block;
            font-size: 0.875rem;
            color: rgba(224, 231, 255, 0.5);
        }

        .post-image {
            width: 100%;
            border-radius: 12px;
            margin: 16px 0;
            max-height: 400px;
            object-fit: cover;
        }

        .post-content {
            margin: 16px 0;
            line-height: 1.6;
        }

        .post-actions {
            display: flex;
            justify-content: space-around;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: rgba(224, 231, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .action-btn:hover {
            color: var(--accent-color);
            background: rgba(59, 130, 246, 0.1);
        }

        .like-btn.liked {
            color: #ef4444;
        }

        /* Comments Section */
        .comments-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .comment {
            background: rgba(30, 41, 59, 0.5);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            display: flex;
            gap: 12px;
        }

        .comment-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            overflow: hidden;
        }

        .comment-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .comment-body {
            flex: 1;
        }

        .comment-user {
            font-weight: 600;
            color: var(--text-light);
            text-decoration: none;
        }

        .comment-user:hover {
            color: var(--accent-color);
        }

        .comment-time {
            font-size: 0.75rem;
            color: rgba(224, 231, 255, 0.5);
            margin-left: 8px;
        }

        .comment-text {
            color: rgba(224, 231, 255, 0.9);
            margin-top: 4px;
            word-break: break-word;
        }

        /* Profile Card */
        .profile-header {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            text-align: center;
        }

        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            overflow: hidden;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
        }

        .stat-label {
            font-size: 0.875rem;
            color: rgba(224, 231, 255, 0.7);
            margin-top: 4px;
        }

        /* Footer */
        .footer {
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px 0;
            margin-top: auto;
            text-align: center;
            color: rgba(224, 231, 255, 0.7);
        }

        /* Alerts */
        .alert {
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border-radius: 12px;
        }

        .alert-danger {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(127, 29, 29, 0.3);
        }

        .alert-success {
            border-color: rgba(34, 197, 94, 0.3);
            background: rgba(20, 83, 45, 0.3);
        }

        /* Search Results */
        .user-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-card:hover {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(59, 130, 246, 0.3);
            transform: translateY(-4px);
        }

        .user-card-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), #60a5fa);
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            overflow: hidden;
        }

        .user-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                font-size: 14px;
            }

            body {
                font-size: 14px;
            }

            .glass-card {
                padding: 16px;
                border-radius: 16px;
                margin-bottom: 16px;
            }

            .post-card {
                padding: 16px;
                border-radius: 16px;
                margin-bottom: 16px;
            }

            .navbar-brand {
                font-size: 1.4rem;
            }

            .nav-link {
                margin: 4px 0;
                padding: 8px 0 !important;
                font-size: 0.95rem;
            }

            .profile-stats {
                flex-direction: row;
                gap: 12px;
                justify-content: space-around;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .post-avatar {
                width: 40px;
                height: 40px;
            }

            .post-image {
                max-height: 300px;
            }

            .comment-avatar {
                width: 32px;
                height: 32px;
            }

            .profile-picture {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .post-actions {
                flex-direction: row;
                gap: 8px;
            }

            .action-btn {
                flex: 1;
                font-size: 0.85rem;
                padding: 6px 8px;
                min-width: 60px;
            }

            .user-card-avatar {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }

            h1 {
                font-size: 1.5rem !important;
            }

            h2 {
                font-size: 1.3rem !important;
            }

            h3 {
                font-size: 1.1rem !important;
            }

            h4 {
                font-size: 1rem !important;
            }

            h5 {
                font-size: 0.95rem !important;
            }

            .main-content {
                padding: 16px 0 !important;
            }

            .container, .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .btn-sm {
                padding: 6px 12px;
                font-size: 0.75rem;
            }

            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .form-control, .form-select {
                padding: 10px 12px;
                font-size: 0.9rem;
                border-radius: 10px;
            }

            .input-group {
                flex-wrap: wrap;
            }

            .input-group input {
                min-width: 100%;
                margin-bottom: 8px;
            }

            .glass-textarea {
                resize: vertical;
                min-height: 100px;
            }

            .comment {
                flex-direction: column;
                gap: 8px;
            }

            .comment-user {
                font-size: 0.9rem;
            }

            .comment-text {
                font-size: 0.85rem;
            }

            .post-username {
                font-size: 0.95rem;
            }

            .post-time {
                font-size: 0.75rem;
            }

            .row > [class*="col-"] {
                margin-bottom: 12px;
            }

            /* Hide on mobile if needed */
            .hide-mobile {
                display: none !important;
            }

            /* Stack buttons vertically on very small screens */
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 12px !important;
            }

            .d-flex.gap-2 > button,
            .d-flex.gap-2 > a {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            :root {
                font-size: 13px;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .nav-link {
                font-size: 0.85rem;
                margin: 2px 0;
            }

            .glass-card {
                padding: 12px;
                border-radius: 12px;
            }

            .post-card {
                padding: 12px;
                border-radius: 12px;
            }

            h1 {
                font-size: 1.3rem !important;
            }

            h2 {
                font-size: 1.1rem !important;
            }

            h3 {
                font-size: 0.95rem !important;
            }

            .post-avatar {
                width: 36px;
                height: 36px;
                margin-right: 8px;
            }

            .comment-avatar {
                width: 28px;
                height: 28px;
            }

            .profile-picture {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .user-card-avatar {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .btn {
                padding: 6px 12px;
                font-size: 0.8rem;
                border-radius: 8px;
            }

            .form-control, .form-select, .glass-input {
                padding: 8px 10px;
                font-size: 0.85rem;
            }

            .post-actions {
                gap: 4px;
            }

            .action-btn {
                padding: 4px 6px;
                font-size: 0.75rem;
                min-width: auto;
            }

            .stat-value {
                font-size: 1rem;
            }

            .stat-label {
                font-size: 0.7rem;
            }

            /* Full width inputs */
            .input-group {
                flex-direction: column;
                width: 100%;
            }

            .input-group input,
            .input-group button {
                width: 100%;
                margin-bottom: 8px;
            }

            .input-group button:last-child {
                margin-bottom: 0;
            }

            /* Responsive form elements */
            .form-control, .form-select, .glass-input, .glass-textarea {
                width: 100%;
                font-size: 16px; /* Prevents zoom on iOS */
                line-height: 1.5;
            }

            .glass-textarea {
                min-height: 120px;
                resize: vertical;
            }

            /* Full width columns */
            .col-md-6, .col-lg-4, .col-md-6 {
                max-width: 100%;
                flex: 0 0 100%;
            }

            /* Compact cards on mobile */
            .user-card, .glass-card {
                width: 100%;
            }

            .comment {
                padding: 8px;
            }

            .d-flex.gap-2 {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .glass-card {
                padding: 16px;
            }

            .post-card {
                padding: 16px;
            }

            .profile-stats {
                flex-direction: column;
                gap: 16px;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            /* Touch-friendly minimum heights */
            .btn {
                min-height: 40px;
            }

            .form-control, .glass-input, .form-select {
                min-height: 40px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php?page=home" style="display: flex; align-items: center; height: 40px;">
                <img src="<?php echo BASE_URL; ?>assets/images/logo.png" alt="Bondly Logo" style="height: 40px; width: auto;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=home">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=create_post">
                                <i class="fas fa-plus"></i> Create Post
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=search">
                                <i class="fas fa-search"></i> Search
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=profile">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?action=logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>index.php?page=register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
