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
 * Render standard Admin Portal Header & Navigation Sidebar (Normal & Classic Style)
 * 
 * @param string $activePage
 * @param string $pageTitle
 * @param string $breadcrumb
 */
function renderAdminHeader($activePage = 'dashboard', $pageTitle = 'Dashboard Overview', $breadcrumb = '') {
    $admin = getCurrentAdmin();
    $dbConnected = isDbConnected();
    $stats = getAdminDashboardStats();
    if (empty($breadcrumb)) {
        $breadcrumb = $pageTitle;
    }
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <title><?= htmlspecialchars($pageTitle) ?> | NextGen Admin Portal</title>
    
    <!-- Classic Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
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
                        sans: ['Inter', '"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        /* Classic custom scrollbar for dashboard */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>

    <script>
        // Back-button / BFcache protection:
        // Force live reload if page loaded from back/forward history cache
        window.addEventListener('pageshow', function (event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                window.location.reload();
            }
        });
    </script>
</head>
<body class="h-full flex flex-col bg-slate-100 text-slate-800 antialiased overflow-hidden">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden transition-opacity"></div>

    <div class="flex flex-1 h-full overflow-hidden">

        <!-- Classic Dark Slate Admin Sidebar -->
        <aside id="admin-sidebar" class="fixed md:static inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800 transition-transform duration-300 transform -translate-x-full md:translate-x-0">
            
            <div class="flex flex-col h-full">
                
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-5 border-b border-slate-800 bg-slate-950/80">
                    <a href="index.php" class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white shadow">
                            <i class="fa-solid fa-leaf text-base"></i>
                        </div>
                        <div>
                            <span class="text-base font-extrabold text-white tracking-tight leading-none block">NextGen <span class="text-emerald-400">Hub</span></span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold block mt-0.5">Admin Dashboard</span>
                        </div>
                    </a>
                    <button id="sidebar-close-btn" class="md:hidden text-slate-400 hover:text-white p-1">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Database Status Badge -->
                <div class="px-5 py-2.5 bg-slate-950/40 border-b border-slate-800/80 flex items-center justify-between text-xs">
                    <span class="text-slate-400 text-[11px] flex items-center">
                        <span class="w-2 h-2 rounded-full <?= $dbConnected ? 'bg-emerald-400 animate-pulse' : 'bg-amber-400' ?> mr-2"></span>
                        <?= $dbConnected ? 'Database: Live' : 'Storage: Cache Buffer' ?>
                    </span>
                    <span class="text-[10px] bg-slate-800 text-slate-300 px-1.5 py-0.5 rounded font-mono font-medium">v1.3</span>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-xs font-semibold">
                    
                    <div class="px-3 pt-2 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        Main Menu
                    </div>

                    <a href="index.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'dashboard' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i>
                        <span>Dashboard Overview</span>
                    </a>

                    <a href="submissions.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'submissions' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-inbox w-5 text-center text-sm"></i>
                        <span>Leads & Submissions</span>
                        <?php if ($stats['new'] > 0): ?>
                            <span class="ml-auto text-[10px] bg-emerald-500 text-slate-950 font-black px-1.5 py-0.5 rounded-full"><?= $stats['new'] ?> New</span>
                        <?php else: ?>
                            <span class="ml-auto text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded font-mono"><?= $stats['total'] ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        Content & Programs
                    </div>

                    <a href="content.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'content' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-pen-to-square w-5 text-center text-sm"></i>
                        <span>Site Content CMS</span>
                    </a>

                    <a href="programs.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'programs' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-rocket w-5 text-center text-sm"></i>
                        <span>Programs & Cohorts</span>
                        <span class="ml-auto text-[10px] bg-slate-800 text-emerald-400 px-1.5 py-0.5 rounded font-mono"><?= $stats['total_programs'] ?></span>
                    </a>

                    <a href="offerings.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'offerings' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-layer-group w-5 text-center text-sm"></i>
                        <span>Core Offerings</span>
                        <span class="ml-auto text-[10px] bg-slate-800 text-emerald-400 px-1.5 py-0.5 rounded font-mono"><?= $stats['total_offerings'] ?? 6 ?></span>
                    </a>

                    <a href="networking.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'networking' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-network-wired w-5 text-center text-sm"></i>
                        <span>Network & Partners</span>
                        <span class="ml-auto text-[10px] bg-slate-800 text-emerald-400 px-1.5 py-0.5 rounded font-mono"><?= $stats['total_partners'] ?? 6 ?></span>
                    </a>

                    <div class="px-3 pt-4 pb-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                        System & Security
                    </div>

                    <a href="settings.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition <?= $activePage === 'settings' ? 'bg-emerald-700 text-white font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/80' ?>">
                        <i class="fa-solid fa-gear w-5 text-center text-sm"></i>
                        <span>Settings & DB</span>
                    </a>

                    <a href="../index.php" target="_blank" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-400 hover:text-emerald-400 hover:bg-slate-800/80 transition">
                        <i class="fa-solid fa-arrow-up-right-from-square w-5 text-center text-sm"></i>
                        <span>View Live Website</span>
                    </a>

                </nav>

                <!-- Sidebar Footer: Current Admin User -->
                <div class="p-3 border-t border-slate-800 bg-slate-950/90">
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-900 border border-slate-800/60">
                        <div class="flex items-center space-x-2.5 overflow-hidden">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                <?= strtoupper(substr($admin['username'], 0, 2)) ?>
                            </div>
                            <div class="truncate">
                                <span class="block text-xs font-bold text-white truncate"><?= htmlspecialchars($admin['full_name']) ?></span>
                                <span class="block text-[10px] text-emerald-400 font-medium truncate"><?= htmlspecialchars($admin['role']) ?></span>
                            </div>
                        </div>
                        <a href="logout.php" title="Sign Out" class="text-slate-400 hover:text-red-400 p-1.5 rounded transition hover:bg-slate-800 shrink-0">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </a>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden bg-slate-100">
            
            <!-- Classic Top Navbar -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-8 flex items-center justify-between shrink-0 shadow-sm z-20">
                
                <div class="flex items-center space-x-3">
                    <!-- Mobile Hamburger -->
                    <button id="sidebar-toggle-btn" class="md:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>

                    <!-- Classic Breadcrumb -->
                    <div class="flex items-center space-x-2 text-xs">
                        <a href="index.php" class="text-slate-400 hover:text-slate-600">Home</a>
                        <span class="text-slate-300">/</span>
                        <span class="font-bold text-slate-800"><?= htmlspecialchars($breadcrumb) ?></span>
                    </div>
                </div>

                <!-- Right Top Bar Controls -->
                <div class="flex items-center space-x-3 text-xs">
                    
                    <!-- MySQL Connection Indicator -->
                    <div class="hidden sm:flex items-center space-x-2 px-2.5 py-1 rounded-md border text-[11px] font-semibold <?= $dbConnected ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' ?>">
                        <span class="w-2 h-2 rounded-full <?= $dbConnected ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' ?>"></span>
                        <span><?= $dbConnected ? 'MySQL Connected' : 'Buffer Mode' ?></span>
                    </div>

                    <!-- View Site -->
                    <a href="../index.php" target="_blank" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 font-medium transition text-xs shadow-sm">
                        <i class="fa-solid fa-globe mr-1.5 text-slate-500"></i> Website
                    </a>

                    <!-- Logout Button -->
                    <a href="logout.php" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 font-semibold border border-red-200 transition text-xs">
                        <i class="fa-solid fa-right-from-bracket mr-1.5"></i> Logout
                    </a>
                </div>

            </header>

            <!-- Scrollable Content Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8">
<?php
}

/**
 * Render standard Admin Footer with classic scripts
 */
function renderAdminFooter() {
?>
            </main>
        </div>
    </div>

    <!-- jQuery for Admin Interactivity -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // Dismissible alert helper
        $(document).on('click', '.alert-close-btn', function () {
            $(this).closest('.alert-box').fadeOut(200, function () { $(this).remove(); });
        });

        // Responsive mobile sidebar drawer toggle
        $('#sidebar-toggle-btn').on('click', function() {
            $('#admin-sidebar').removeClass('-translate-x-full');
            $('#sidebar-backdrop').removeClass('hidden');
        });

        $('#sidebar-close-btn, #sidebar-backdrop').on('click', function() {
            $('#admin-sidebar').addClass('-translate-x-full');
            $('#sidebar-backdrop').addClass('hidden');
        });
    </script>
</body>
</html>
<?php
}

