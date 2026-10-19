<?php
/**
 * NextGen Farming Incubation Hub - Admin Login
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Send anti-caching HTTP headers
if (!headers_sent()) {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
}

require_once __DIR__ . '/../includes/db.php';

// Redirect if already logged in with verified session
if (!empty($_SESSION['admin_user']) && is_array($_SESSION['admin_user']) && !empty($_SESSION['admin_user']['id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $user = verifyAdminLogin($username, $password);
        if ($user) {
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);
            $_SESSION['admin_user'] = $user;
            
            // Safe redirect verification
            $target = (!empty($redirect) && !str_contains($redirect, 'login.php') && !str_contains($redirect, 'logout.php')) 
                      ? filter_var($redirect, FILTER_SANITIZE_URL) 
                      : 'index.php';
            header("Location: " . $target);
            exit;
        } else {
            $error = 'Invalid credentials. Please verify username and password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Prevent caching on client browsers -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title>Admin Login | NextGen Farming Incubation Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-slate-900 text-slate-800">

    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 mx-auto rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md mb-3">
                <i class="fa-solid fa-leaf text-xl text-emerald-100"></i>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">NextGen Farming Incubation Hub</h1>
            <p class="text-xs text-slate-400 mt-0.5 uppercase tracking-wider font-semibold">Admin Authentication Console</p>
        </div>

        <!-- Classic Login Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-7 shadow-lg">
            
            <?php if (!empty($error)): ?>
                <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-200 text-red-800 text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-exclamation text-sm text-red-600"></i>
                    <span class="font-semibold"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout'])): ?>
                <div class="mb-5 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-sm text-emerald-600"></i>
                    <span class="font-semibold">You have been securely signed out.</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['auth_required'])): ?>
                <div class="mb-5 p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-lock text-sm text-amber-600"></i>
                    <span class="font-semibold">Access Denied. Please log in first to access the dashboard.</span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php?redirect=<?= htmlspecialchars($redirect) ?>" class="space-y-4 text-xs">
                
                <div>
                    <label for="username" class="block font-semibold text-slate-700 mb-1">
                        Username or Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" placeholder="Enter your username or email" class="w-full pl-9 pr-3 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-600 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block font-semibold text-slate-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" id="password" name="password" required placeholder="Enter your password" class="w-full pl-9 pr-10 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-600 transition">
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center justify-center space-x-1.5">
                        <span>Sign In to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </button>
                </div>

            </form>
        </div>

        <div class="text-center mt-5">
            <a href="../index.php" class="text-xs text-slate-400 hover:text-white transition flex items-center justify-center space-x-1">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Return to Public Website</span>
            </a>
        </div>

    </div>

    <script>
    document.querySelectorAll('.password-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    </script>
</body>
</html>
