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
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-agro-950 to-slate-900 text-slate-100">

    <div class="w-full max-w-md">
        
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-agro-600 to-agro-800 flex items-center justify-center text-white shadow-xl shadow-agro-800/30 mb-4">
                <i class="fa-solid fa-leaf text-2xl text-emerald-300"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">NextGen Farming</h1>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-semibold">Incubation Hub Management Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl relative">
            
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 rounded-xl bg-red-950/80 border border-red-800 text-red-200 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-base text-red-400"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout'])): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-800 text-emerald-200 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-base text-emerald-400"></i>
                    <span>You have been securely signed out. Login required to re-enter.</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['auth_required'])): ?>
                <div class="mb-6 p-4 rounded-xl bg-amber-950/80 border border-amber-800 text-amber-200 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-lock text-base text-amber-400"></i>
                    <span>Access Denied. Please log in first to access the admin dashboard.</span>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php?redirect=<?= htmlspecialchars($redirect) ?>" class="space-y-5">
                
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 mb-1.5 uppercase tracking-wider">
                        Username or Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" id="username" name="username" required value="admin" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5 uppercase tracking-wider">
                        Admin Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password" name="password" required value="admin123" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-agro-700 to-agro-600 hover:from-agro-600 hover:to-agro-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-agro-700/30 transition flex items-center justify-center space-x-2">
                        <span>Sign In to Dashboard</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>

            </form>

            <!-- Default Credentials Notice -->
            <div class="mt-6 pt-5 border-t border-slate-800 text-center">
                <span class="text-[11px] text-slate-400 block mb-1">Default Admin Credentials</span>
                <div class="inline-flex items-center space-x-2 bg-slate-950 px-3 py-1.5 rounded-lg border border-slate-800 text-xs font-mono text-emerald-400">
                    <span>Username: <strong>admin</strong></span>
                    <span class="text-slate-600">•</span>
                    <span>Password: <strong>admin123</strong></span>
                </div>
                <p class="text-[10px] text-slate-500 mt-2">Credentials can be changed anytime from the Settings page.</p>
            </div>

        </div>

        <div class="text-center mt-6">
            <a href="../index.php" class="text-xs text-slate-400 hover:text-emerald-400 transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Back to Website Homepage</span>
            </a>
        </div>

    </div>

</body>
</html>
