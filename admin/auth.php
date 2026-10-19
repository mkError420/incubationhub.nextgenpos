<?php
/**
 * NextGen Farming Incubation Hub - Admin Authentication & Layout Helpers
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

/**
 * Enforce admin authentication
 * Sends strict anti-caching headers and verifies session legitimacy
 */
function requireAdminAuth() {
    // 1. Prevent all browser caching of admin dashboard pages
    if (!headers_sent()) {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    }

    // 2. Strict session existence and integrity validation
    if (empty($_SESSION['admin_user']) || !is_array($_SESSION['admin_user']) || empty($_SESSION['admin_user']['id'])) {
        $_SESSION = [];
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? 'index.php');
        header("Location: login.php?redirect={$redirect}&auth_required=1");
        exit;
    }
}

/**
 * Get current admin user record from session
 * 
 * @return array
 */
function getCurrentAdmin() {
    return $_SESSION['admin_user'] ?? [
        'id'        => 0,
        'username'  => 'Guest',
        'full_name' => 'Administrator',
        'role'      => 'Super Admin'
    ];
}

/**
 * Render standard Admin Portal Header & Navigation Sidebar
 * 
 * @param string $activePage
 * @param string $pageTitle
 */
function renderAdminHeader($activePage = 'dashboard', $pageTitle = 'Dashboard Overview') {
    $admin = getCurrentAdmin();
    $dbConnected = isDbConnected();
    $stats = getAdminDashboardStats();
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Prevent caching on all client browsers -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title><?= htmlspecialchars($pageTitle) ?> | NextGen Admin Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        agro: {
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#0d5f3a',
                            900: '#083e25',
                            950: '#032314',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    <script>
        // Back-button / BFcache protection:
        // If user logs out and hits browser 'Back', immediately force a live reload
        // which triggers requireAdminAuth() on the server and redirects to login.php
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                window.location.reload();
            }
        });
    </script>
</head>
<body class="h-full flex bg-[#f8fafc] text-slate-800 antialiased overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-950 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800 z-30">
        <div>
            <!-- Brand Logo Header -->
            <div class="h-16 flex items-center px-6 border-b border-slate-800/80 bg-slate-950">
                <a href="index.php" class="flex items-center space-x-3 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-agro-600 to-agro-800 flex items-center justify-center text-white shadow-md">
                        <i class="fa-solid fa-leaf text-emerald-400"></i>
                    </div>
                    <div>
                        <span class="text-base font-extrabold text-white tracking-tight">NextGen <span class="text-emerald-400">Hub</span></span>
                        <span class="block text-[10px] text-slate-400 uppercase tracking-widest font-semibold -mt-1">Admin Portal</span>
                    </div>
                </a>
            </div>

            <!-- Database Status Indicator Strip -->
            <div class="px-5 py-2.5 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between text-xs">
                <span class="text-slate-400 text-[11px] flex items-center">
                    <span class="w-2 h-2 rounded-full <?= $dbConnected ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' ?> mr-2"></span>
                    <?= $dbConnected ? 'MySQL: nextgenp_incubation' : 'Cache Buffer Active' ?>
                </span>
                <span class="text-[10px] bg-slate-800 text-slate-300 px-1.5 py-0.5 rounded font-mono">v1.2</span>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1 text-sm font-semibold">
                <a href="index.php" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition <?= $activePage === 'dashboard' ? 'bg-agro-800 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-900' ?>">
                    <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="content.php" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition <?= $activePage === 'content' ? 'bg-agro-800 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-900' ?>">
                    <i class="fa-solid fa-pen-to-square w-5 text-center"></i>
                    <span>Site Content CMS</span>
                </a>

                <a href="programs.php" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition <?= $activePage === 'programs' ? 'bg-agro-800 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-900' ?>">
                    <i class="fa-solid fa-rocket w-5 text-center"></i>
                    <span>Programs & Cohorts</span>
                    <span class="ml-auto text-xs bg-slate-800 text-emerald-400 px-2 py-0.5 rounded-full font-mono"><?= $stats['total_programs'] ?></span>
                </a>

                <a href="submissions.php" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition <?= $activePage === 'submissions' ? 'bg-agro-800 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-900' ?>">
                    <i class="fa-solid fa-inbox w-5 text-center"></i>
                    <span>Submissions & Leads</span>
                    <?php if ($stats['new'] > 0): ?>
                        <span class="ml-auto text-xs bg-emerald-500 text-slate-950 font-black px-2 py-0.5 rounded-full"><?= $stats['new'] ?></span>
                    <?php endif; ?>
                </a>

                <div class="pt-4 pb-2 px-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    System & Settings
                </div>

                <a href="settings.php" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl transition <?= $activePage === 'settings' ? 'bg-agro-800 text-white font-bold shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-900' ?>">
                    <i class="fa-solid fa-gear w-5 text-center"></i>
                    <span>Admin Profile & DB</span>
                </a>

                <a href="../index.php" target="_blank" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-900 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center"></i>
                    <span>View Public Website</span>
                </a>
            </nav>
        </div>

        <!-- User Profile Card in Sidebar Footer -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white font-black flex items-center justify-center text-xs shadow">
                        <?= strtoupper(substr($admin['username'], 0, 2)) ?>
                    </div>
                    <div class="overflow-hidden">
                        <span class="block text-xs font-bold text-white truncate"><?= htmlspecialchars($admin['full_name']) ?></span>
                        <span class="block text-[10px] text-slate-400 truncate"><?= htmlspecialchars($admin['role']) ?></span>
                    </div>
                </div>
                <a href="logout.php" title="Sign Out" class="text-slate-400 hover:text-red-400 transition p-1.5 rounded-lg hover:bg-slate-900">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Top App Bar -->
        <header class="h-16 bg-white border-b border-gray-200 px-6 sm:px-8 flex items-center justify-between shrink-0 shadow-sm">
            <div class="flex items-center space-x-4">
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight"><?= htmlspecialchars($pageTitle) ?></h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="submissions.php?status=New" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                    <?= $stats['new'] ?> New Leads
                </a>
                <a href="../contact.php" target="_blank" class="hidden sm:inline-flex items-center px-3.5 py-1.5 text-xs font-bold text-white bg-agro-800 hover:bg-agro-900 rounded-xl shadow transition">
                    <i class="fa-solid fa-plus mr-1.5"></i> Test Form
                </a>
            </div>
        </header>

        <!-- Main Scrollable Body -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8">
<?php
}

/**
 * Render standard Admin Footer
 */
function renderAdminFooter() {
?>
        </main>
    </div>

    <!-- jQuery for Admin actions -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Dismissible alert helper
        $(document).on('click', '.alert-close-btn', function () {
            $(this).closest('.alert-box').fadeOut(250, function () { $(this).remove(); });
        });
    </script>
</body>
</html>
<?php
}
