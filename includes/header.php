<?php
/**
 * NextGen Farming Incubation Hub - Shared Header Component
 */
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once __DIR__ . '/db.php';

if (!isset($currentPage)) {
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
}

// Gate Enforcement: If visitor has not submitted the landing page idea form,
// redirect to landing.php first
if ($currentPage !== 'landing') {
    requireVisitorAccess();
}

if (!isset($pageTitle)) {
    $pageTitle = 'NextGen Farming Incubation Hub | Ecosystem Builders in Bangladesh & Emerging Markets';
}
if (!isset($pageDescription)) {
    $pageDescription = 'Connecting entrepreneur ecosystem builders, agri-founders, investors, and mentors across Bangladesh and emerging markets for sustainable agricultural transformation.';
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="keywords" content="NextGen Farming, Incubation Hub, Bangladesh AgriTech, Emerging Markets, Agriculture Accelerator, Seed Venture Capital, Agronomy Mentorship">
    <meta name="theme-color" content="#0d5f3a">

    <!-- OpenGraph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:site_name" content="NextGen Farming Incubation Hub">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        agro: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#0d5f3a', // Brand Dark Green
                            900: '#083e25',
                            950: '#032314',
                        },
                        amber: {
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-[#fbfdfc] text-slate-800 antialiased flex flex-col min-h-screen">

<?php if (isset($_GET['unlocked'])): ?>
    <!-- Welcome Unlock Toast Notification -->
    <div id="unlock-toast" class="bg-gradient-to-r from-emerald-800 via-agro-800 to-emerald-900 text-white px-4 py-3 shadow-lg border-b border-emerald-500/40 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3 text-xs sm:text-sm">
                <span class="w-8 h-8 rounded-full bg-emerald-500 text-agro-950 flex items-center justify-center font-bold shrink-0">
                    <i class="fa-solid fa-circle-check text-base"></i>
                </span>
                <div>
                    <span class="font-extrabold text-amber-300">Welcome to NextGen Farming Incubation Hub!</span>
                    <span class="block text-emerald-100 text-[11px] sm:text-xs">Your idea and profile have been securely received. Full website access is now active, and our incubation committee will contact you on WhatsApp.</span>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('unlock-toast').remove()" class="text-emerald-200 hover:text-white p-1 ml-4 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
<?php endif; ?>

<?php
$dynBannerBadge = getSiteSetting('hero_banner_badge', 'New Call');
$dynBannerText  = getSiteSetting('hero_banner_text', 'Applications Open for AgriTech Ignite Accelerator');
$dynBannerLink  = getSiteSetting('hero_banner_link', 'contact.php?category=Entrepreneur');
?>
    <!-- Top Announcement Strip -->
    <div class="bg-gradient-to-r from-agro-950 via-agro-800 to-agro-900 text-white text-xs sm:text-sm py-2 px-4 text-center border-b border-agro-700/40 relative z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="hidden md:flex items-center space-x-4 text-emerald-300">
                <span><i class="fa-solid fa-location-dot mr-1 text-emerald-400"></i> Dhaka • Global Emerging Markets</span>
                <span class="text-agro-600">|</span>
                <span><i class="fa-solid fa-clock mr-1 text-emerald-400"></i> Spring 2026 Cohort Open</span>
            </div>
            <div class="mx-auto md:mx-0 flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500 text-agro-950 uppercase tracking-wide"><?= htmlspecialchars($dynBannerBadge) ?></span>
                <span><?= htmlspecialchars($dynBannerText) ?></span>
                <a href="<?= htmlspecialchars($dynBannerLink) ?>" class="underline font-semibold hover:text-amber-400 transition ml-2">Apply Now &rarr;</a>
            </div>
            <div class="hidden lg:flex items-center space-x-4 text-xs">
                <a href="landing.php" class="text-emerald-300 hover:text-white transition flex items-center">
                    <i class="fa-solid fa-lightbulb mr-1 text-amber-400"></i> Pitch an Idea
                </a>
                <span class="text-slate-600">|</span>
                <a href="admin/index.php" class="text-amber-400 hover:text-amber-300 font-bold transition flex items-center">
                    <i class="fa-solid fa-gauge-high mr-1"></i> Admin Portal
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header id="main-navbar" class="sticky top-0 z-40 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 py-3.5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                
                <!-- Logo & Brand Identity -->
                <?php
                $dynSiteLogo = getSiteSetting('site_logo', '');
                $dynSiteLogoUrl = !empty($dynSiteLogo) ? getLogoUrl($dynSiteLogo, false) : '';
                $dynSiteName = getSiteSetting('site_name', 'NextGen Farming Incubation Hub');
                ?>
                <a href="index.php" class="flex items-center space-x-3 group">
                    <?php if (!empty($dynSiteLogoUrl)): ?>
                        <img src="<?= htmlspecialchars($dynSiteLogoUrl) ?>" alt="<?= htmlspecialchars($dynSiteName) ?>" class="h-10 sm:h-11 w-auto max-w-[220px] object-contain transition duration-200 group-hover:scale-[1.02]" onerror="this.style.display='none'; document.getElementById('default-nav-brand').style.display='flex';">
                        <div id="default-nav-brand" style="display: none;" class="items-center space-x-3">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-agro-700 to-agro-900 flex items-center justify-center text-white shadow-md shadow-agro-800/20 group-hover:scale-105 transition duration-200">
                                <i class="fa-solid fa-leaf text-xl text-emerald-300 group-hover:rotate-12 transition duration-300"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-xl font-extrabold tracking-tight text-agro-900">NextGen</span>
                                    <span class="text-xl font-bold tracking-tight text-agro-600">Farming</span>
                                </div>
                                <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-widest -mt-1">Incubation Hub</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-agro-700 to-agro-900 flex items-center justify-center text-white shadow-md shadow-agro-800/20 group-hover:scale-105 transition duration-200">
                            <i class="fa-solid fa-leaf text-xl text-emerald-300 group-hover:rotate-12 transition duration-300"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xl font-extrabold tracking-tight text-agro-900">NextGen</span>
                                <span class="text-xl font-bold tracking-tight text-agro-600">Farming</span>
                            </div>
                            <span class="block text-[11px] font-semibold text-slate-500 uppercase tracking-widest -mt-1">Incubation Hub</span>
                        </div>
                    <?php endif; ?>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="index.php" class="px-3 py-2 text-sm font-semibold rounded-lg transition <?= $currentPage === 'index' ? 'text-agro-800 bg-agro-50/80 font-bold' : 'text-slate-600 hover:text-agro-800 hover:bg-gray-50' ?>">
                        Home
                    </a>
                    <a href="about.php" class="px-3 py-2 text-sm font-semibold rounded-lg transition <?= $currentPage === 'about' ? 'text-agro-800 bg-agro-50/80 font-bold' : 'text-slate-600 hover:text-agro-800 hover:bg-gray-50' ?>">
                        About Us
                    </a>
                    <a href="programs.php" class="px-3 py-2 text-sm font-semibold rounded-lg transition <?= $currentPage === 'programs' ? 'text-agro-800 bg-agro-50/80 font-bold' : 'text-slate-600 hover:text-agro-800 hover:bg-gray-50' ?>">
                        Programs & Cohorts
                    </a>
                    <a href="offerings.php" class="px-3 py-2 text-sm font-semibold rounded-lg transition <?= $currentPage === 'offerings' ? 'text-agro-800 bg-agro-50/80 font-bold' : 'text-slate-600 hover:text-agro-800 hover:bg-gray-50' ?>">
                        Core Offerings
                    </a>
                    <a href="networking.php" class="px-3 py-2 text-sm font-semibold rounded-lg transition <?= $currentPage === 'networking' ? 'text-agro-800 bg-agro-50/80 font-bold' : 'text-slate-600 hover:text-agro-800 hover:bg-gray-50' ?>">
                        Networking
                    </a>
                </nav>

                <!-- Action Button -->
                <div class="hidden md:flex items-center space-x-3">
                    <a href="contact.php" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-agro-800 to-agro-700 hover:from-agro-900 hover:to-agro-800 rounded-xl shadow-md shadow-agro-800/20 hover:shadow-lg transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-paper-plane mr-2 text-xs text-emerald-300"></i> Apply / Connect
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-btn" type="button" class="p-2 rounded-lg text-slate-600 hover:text-agro-800 hover:bg-gray-100 focus:outline-none" aria-label="Toggle Navigation">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown Navigation -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-md px-4 pt-3 pb-6 shadow-xl">
            <div class="flex flex-col space-y-2">
                <a href="index.php" class="px-3 py-2.5 rounded-lg text-sm font-bold <?= $currentPage === 'index' ? 'bg-agro-50 text-agro-800' : 'text-slate-700' ?>">
                    <i class="fa-solid fa-house w-6 text-agro-700"></i> Home
                </a>
                <a href="about.php" class="px-3 py-2.5 rounded-lg text-sm font-bold <?= $currentPage === 'about' ? 'bg-agro-50 text-agro-800' : 'text-slate-700' ?>">
                    About Us
                </a>
                <a href="programs.php" class="px-3 py-2.5 rounded-lg text-sm font-bold <?= $currentPage === 'programs' ? 'bg-agro-50 text-agro-800' : 'text-slate-700' ?>">
                    Programs & Cohorts
                </a>
                <a href="offerings.php" class="px-3 py-2.5 rounded-lg text-sm font-bold <?= $currentPage === 'offerings' ? 'bg-agro-50 text-agro-800' : 'text-slate-700' ?>">
                    Core Offerings
                </a>
                <a href="networking.php" class="px-3 py-2.5 rounded-lg text-sm font-bold <?= $currentPage === 'networking' ? 'bg-agro-50 text-agro-800' : 'text-slate-700' ?>">
                    Networking
                </a>
                <div class="pt-3">
                    <a href="contact.php" class="w-full flex items-center justify-center px-4 py-3 text-sm font-bold text-white bg-agro-800 rounded-xl shadow-md">
                        Apply / Connect Now
                    </a>
                </div>
            </div>
        </div>
    </header>
