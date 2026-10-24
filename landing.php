<?php
/**
 * NextGen Farming Incubation Hub - Gate Landing Page & Idea Intake
 * 
 * Visitors are greeted with this landing page first.
 * Upon submitting their profile, idea description, and optional 5-minute video pitch,
 * their data is recorded to the admin dashboard and full website access is unlocked.
 */

if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once __DIR__ . '/includes/db.php';

$currentPage = 'landing';
$pageTitle = 'Submit Your Idea & Unlock Full Hub Access | NextGen Farming Incubation Hub';
$pageDescription = 'Submit your agricultural innovation, technology concept, or farming venture to unlock full access to NextGen Farming Incubation Hub, living farmland testbeds, and venture funding.';

// Check if visitor has already unlocked access
$alreadyUnlocked = isVisitorUnlocked();

// Dynamic site settings
$dynSiteLogo = getSiteSetting('site_logo', '');
$dynSiteLogoUrl = !empty($dynSiteLogo) ? getLogoUrl($dynSiteLogo, false) : '';
$dynSiteName = getSiteSetting('site_name', 'NextGen Farming Incubation Hub');
$landingSiteName = getSiteSetting('landing_site_name', 'NextGen IncubationHub');
$landingSubtitle = getSiteSetting('landing_subtitle', 'Empowering visionary innovators, farmers, and entrepreneurs with living farmland testbeds, venture capital, and commercial scale.');
$carouselEnabled = getSiteSetting('landing_carousel_enabled', '1');
$carouselAutoplay = (int)getSiteSetting('landing_carousel_autoplay', '5000');
$landingBanners = getLandingBanners(true); // Active banners only
$counterStats = getVisitorIdeaCountsByCategory();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="theme-color" content="#0d5f3a">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .hero-pattern {
            background-color: #032314;
            background-image: radial-gradient(rgba(34, 197, 94, 0.15) 1px, transparent 1px), radial-gradient(rgba(34, 197, 94, 0.08) 1px, #032314 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }
        .glow-effect {
            box-shadow: 0 0 50px -10px rgba(34, 197, 94, 0.3);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased flex flex-col min-h-screen selection:bg-emerald-500 selection:text-slate-950">

    <!-- Top Announcement & Status Bar -->
    <header class="border-b border-emerald-900/40 bg-slate-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Logo / Brand Identity -->
            <a href="landing.php" class="flex items-center space-x-3 group">
                <?php if (!empty($dynSiteLogoUrl)): ?>
                    <img src="<?= htmlspecialchars($dynSiteLogoUrl) ?>" alt="<?= htmlspecialchars($landingSiteName) ?>" class="h-9 sm:h-10 w-auto max-w-[200px] object-contain transition group-hover:scale-105" onerror="this.style.display='none'; document.getElementById('default-landing-brand').style.display='flex';">
                    <div id="default-landing-brand" style="display: none;" class="items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-agro-800 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-leaf text-lg text-emerald-200"></i>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-white tracking-tight">NextGen <span class="text-emerald-400">IncubationHub</span></span>
                            <span class="block text-[10px] font-bold text-emerald-300 uppercase tracking-widest -mt-1">Agri Innovation Platform</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-agro-800 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-leaf text-lg text-emerald-200"></i>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-white tracking-tight">NextGen <span class="text-emerald-400">IncubationHub</span></span>
                        <span class="block text-[10px] font-bold text-emerald-300 uppercase tracking-widest -mt-1">Agri Innovation Platform</span>
                    </div>
                <?php endif; ?>
            </a>

            <!-- Right Controls: Status & Admin Link -->
            <div class="flex items-center space-x-3 text-xs">
                <?php if ($alreadyUnlocked): ?>
                    <div class="flex items-center space-x-2">
                        <a href="landing.php?reset=1" class="hidden sm:inline-flex items-center px-2.5 py-1.5 rounded-lg border border-amber-500/40 text-amber-300 hover:bg-amber-500/10 text-[11px] font-semibold transition" title="Clear unlock session and test gate submission again">
                            <i class="fa-solid fa-rotate-left mr-1"></i> Reset Access (Test Gate)
                        </a>
                        <a href="index.php" class="inline-flex items-center px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold rounded-lg shadow transition text-xs">
                            <i class="fa-solid fa-circle-check mr-1 text-slate-950"></i> Enter Main Website &rarr;
                        </a>
                    </div>
                <?php else: ?>
                    <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold bg-emerald-950/80 text-emerald-300 border border-emerald-800/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>
                        Innovation Intake • Spring 2026 Batch
                    </span>
                <?php endif; ?>
                <a href="admin/login.php" class="text-slate-400 hover:text-white px-2.5 py-1.5 rounded-lg border border-slate-800 hover:border-slate-700 transition flex items-center" title="Admin Dashboard">
                    <i class="fa-solid fa-shield-halved mr-1.5 text-amber-400"></i> Admin
                </a>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 hero-pattern relative overflow-hidden pb-20">
        
        <!-- Ambient Glowing Orbs -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px] bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-[400px] h-[300px] bg-amber-500/5 blur-[100px] rounded-full pointer-events-none"></div>

        <!-- TOP BRAND TITLE & DYNAMIC BANNER CAROUSEL SECTION -->
        <section class="pt-8 sm:pt-12 pb-6 px-4 sm:px-6 lg:px-8 max-w-6xl mx-auto relative z-10">
            
            <!-- Site Name at Landing Page on TOP -->
            <div class="text-center mb-8 sm:mb-10">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-950/80 text-emerald-300 border border-emerald-500/40 mb-4 backdrop-blur shadow-sm">
                    <i class="fa-solid fa-leaf mr-2 text-emerald-400"></i> Agricultural Innovation & Venture Studio
                </div>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight sm:leading-none">
                    <?= htmlspecialchars($landingSiteName) ?>
                </h1>
                <?php if (!empty($landingSubtitle)): ?>
                    <p class="mt-4 text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl mx-auto font-medium leading-relaxed">
                        <?= htmlspecialchars($landingSubtitle) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Banner Carousel Under That Name -->
            <?php if ($carouselEnabled === '1' && !empty($landingBanners)): ?>
                <div id="landing-banner-carousel" class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-emerald-600/30 bg-slate-900/90 shadow-2xl shadow-emerald-950/60 group" data-autoplay="<?= (int)$carouselAutoplay ?>" data-total-slides="<?= count($landingBanners) ?>">
                    
                    <!-- Carousel Slides Track -->
                    <div class="carousel-track relative w-full h-[360px] sm:h-[440px] md:h-[480px]">
                        <?php foreach ($landingBanners as $idx => $banner): 
                            $bImg = getBannerImageUrl($banner['image_path'] ?? '');
                            $isFirst = ($idx === 0);
                        ?>
                            <div class="carousel-slide absolute inset-0 transition-all duration-700 ease-in-out <?= $isFirst ? 'opacity-100 z-20 scale-100' : 'opacity-0 z-10 scale-95 pointer-events-none' ?>" data-slide-index="<?= $idx ?>">
                                <!-- Slide Image with Dark Aesthetic Gradient Scrim -->
                                <img src="<?= htmlspecialchars($bImg) ?>" alt="<?= htmlspecialchars($banner['title']) ?>" class="absolute inset-0 w-full h-full object-cover object-center transform transition duration-1000 group-hover:scale-105" loading="<?= $isFirst ? 'eager' : 'lazy' ?>" onerror="this.src='https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=1600&q=80'">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/65 to-transparent"></div>
                                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/95 via-slate-950/60 to-transparent"></div>
                                
                                <!-- Content Container -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10 md:p-12 z-30 max-w-3xl">
                                    <?php if (!empty($banner['badge'])): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] sm:text-xs font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 uppercase tracking-wider mb-3 backdrop-blur-md shadow-sm">
                                            <i class="fa-solid fa-sparkles mr-1.5 text-amber-300"></i>
                                            <?= htmlspecialchars($banner['badge']) ?>
                                        </span>
                                    <?php endif; ?>

                                    <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-white leading-tight tracking-tight drop-shadow-md">
                                        <?= htmlspecialchars($banner['title']) ?>
                                    </h2>

                                    <?php if (!empty($banner['subtitle'])): ?>
                                        <p class="mt-2.5 sm:mt-3 text-xs sm:text-sm md:text-base text-slate-200 leading-relaxed max-w-2xl line-clamp-2 sm:line-clamp-none drop-shadow">
                                            <?= htmlspecialchars($banner['subtitle']) ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php if (!empty($banner['link_text'])): ?>
                                        <div class="mt-5 sm:mt-6 flex flex-wrap items-center gap-3">
                                            <a href="<?= htmlspecialchars($banner['link_url'] ?: '#intake-form-section') ?>" class="inline-flex items-center px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5">
                                                <span><?= htmlspecialchars($banner['link_text']) ?></span>
                                                <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                                            </a>
                                            <a href="#intake-form-section" class="inline-flex items-center px-4 py-2.5 sm:py-3 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-white font-semibold text-xs border border-slate-700/80 backdrop-blur transition">
                                                <i class="fa-solid fa-lightbulb mr-2 text-amber-400"></i> Submit Your Idea Below
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Previous & Next Arrow Buttons -->
                    <button type="button" id="carousel-btn-prev" class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-900/75 hover:bg-emerald-600 border border-white/20 text-white flex items-center justify-center transition backdrop-blur shadow-xl hover:scale-110" aria-label="Previous Slide">
                        <i class="fa-solid fa-chevron-left text-sm sm:text-base"></i>
                    </button>
                    <button type="button" id="carousel-btn-next" class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-900/75 hover:bg-emerald-600 border border-white/20 text-white flex items-center justify-center transition backdrop-blur shadow-xl hover:scale-110" aria-label="Next Slide">
                        <i class="fa-solid fa-chevron-right text-sm sm:text-base"></i>
                    </button>

                    <!-- Slide Dots Navigation -->
                    <div class="absolute bottom-4 sm:bottom-6 right-5 sm:right-10 z-30 flex items-center space-x-2">
                        <?php foreach ($landingBanners as $idx => $banner): ?>
                            <button type="button" class="carousel-dot-btn h-2.5 rounded-full transition-all duration-300 <?= $idx === 0 ? 'w-8 bg-emerald-400 shadow-md shadow-emerald-400/50' : 'w-2.5 bg-white/40 hover:bg-white/80' ?>" data-dot-index="<?= $idx ?>" aria-label="Go to slide <?= $idx + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>

                    <!-- Slide Progress Counter Badge -->
                    <div class="absolute top-4 right-4 z-30 px-3 py-1 rounded-full bg-slate-950/70 border border-white/10 text-[11px] font-mono text-emerald-300 backdrop-blur">
                        <span id="carousel-current-index">1</span> / <span><?= count($landingBanners) ?></span>
                    </div>

                </div>
            <?php endif; ?>

        </section>

        <!-- Innovation Pitch Intro Section -->
        <section class="pt-4 sm:pt-6 pb-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto text-center relative z-10">
            
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-900/40 text-emerald-300 border border-emerald-500/30 mb-5 backdrop-blur">
                <i class="fa-solid fa-seedling mr-2 text-emerald-400"></i> Agricultural Entrepreneurship Zone
            </div>

            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight sm:leading-none">
                Transform Your Idea to a <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-amber-300 bg-clip-text text-transparent">Scale Base Agri Business</span>
            </h2> 

            <p class="mt-4 sm:mt-5 text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed">
                NextGen Farming Incubation Hub unlocks pre-seed capital, 500+ acres of living farmland testbeds, and direct commercial off-take partnerships. Share your idea below to unlock full access to our platform and pitch directly to our incubation committee.
            </p>

            <!-- Trust / Impact Counters (Dynamic & Interactive with Form Selection) -->
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto text-left" id="impact-counters-container">
                <div id="counter-card-entrepreneurs" data-category="Entrepreneurs" class="impact-counter-card cursor-pointer bg-slate-900/80 border-2 border-emerald-500/80 ring-2 ring-emerald-500/30 p-3.5 sm:p-4 rounded-xl backdrop-blur transition-all duration-300 transform hover:-translate-y-0.5 shadow-lg shadow-emerald-950/50" title="Click to select Entrepreneurs in form">
                    <div class="flex items-center justify-between">
                        <span class="text-xl sm:text-2xl font-black text-emerald-400 block tracking-tight">
                            <span id="counter-val-entrepreneurs"><?= htmlspecialchars((string)($counterStats['Entrepreneurs'] ?? 100)) ?></span>+
                        </span>
                        <i class="fa-solid fa-seedling text-emerald-400 text-xs"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs text-white font-extrabold block mt-1">Entrepreneurs</span>
                    <span class="text-[10px] text-emerald-300 font-semibold block mt-0.5">Innovators & Startups</span>
                </div>

                <div id="counter-card-investors" data-category="Investors" class="impact-counter-card cursor-pointer bg-slate-900/60 border border-slate-800/80 hover:border-amber-500/50 p-3.5 sm:p-4 rounded-xl backdrop-blur transition-all duration-300 transform hover:-translate-y-0.5" title="Click to select Investors in form">
                    <div class="flex items-center justify-between">
                        <span class="text-xl sm:text-2xl font-black text-amber-300 block tracking-tight">
                            <span id="counter-val-investors"><?= htmlspecialchars((string)($counterStats['Investors'] ?? 20)) ?></span>+
                        </span>
                        <i class="fa-solid fa-coins text-amber-400/50 text-xs"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-bold block mt-1">Investors</span>
                    <span class="text-[10px] text-amber-300/80 font-semibold block mt-0.5">Angels & Syndicates</span>
                </div>

                <div id="counter-card-mentors" data-category="Mentors" class="impact-counter-card cursor-pointer bg-slate-900/60 border border-slate-800/80 hover:border-blue-500/50 p-3.5 sm:p-4 rounded-xl backdrop-blur transition-all duration-300 transform hover:-translate-y-0.5" title="Click to select Mentors in form">
                    <div class="flex items-center justify-between">
                        <span class="text-xl sm:text-2xl font-black text-blue-400 block tracking-tight">
                            <span id="counter-val-mentors"><?= htmlspecialchars((string)($counterStats['Mentors'] ?? 15)) ?></span>+
                        </span>
                        <i class="fa-solid fa-chalkboard-user text-blue-400/50 text-xs"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-bold block mt-1">Mentors</span>
                    <span class="text-[10px] text-blue-300/80 font-semibold block mt-0.5">Scientists & Advisors</span>
                </div>

                <div id="counter-card-organizers" data-category="Organizers" class="impact-counter-card cursor-pointer bg-slate-900/60 border border-slate-800/80 hover:border-teal-500/50 p-3.5 sm:p-4 rounded-xl backdrop-blur transition-all duration-300 transform hover:-translate-y-0.5" title="Click to select Organizers in form">
                    <div class="flex items-center justify-between">
                        <span class="text-xl sm:text-2xl font-black text-teal-300 block tracking-tight">
                            <span id="counter-val-organizers"><?= htmlspecialchars((string)($counterStats['Organizers'] ?? 24)) ?></span>+
                        </span>
                        <i class="fa-solid fa-users text-teal-400/50 text-xs"></i>
                    </div>
                    <span class="text-[11px] sm:text-xs text-slate-300 font-bold block mt-1">Organizers</span>
                    <span class="text-[10px] text-teal-300/80 font-semibold block mt-0.5">Agri Co-ops & Hubs</span>
                </div>
            </div>

        </section>

        <!-- Core Submission Gate Form Container -->
        <section id="intake-form-section" class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10">
            
            <div class="bg-slate-900/95 border border-emerald-600/30 rounded-2xl sm:rounded-3xl p-6 sm:p-10 shadow-2xl glow-effect backdrop-blur-xl relative">
                
                <!-- Form Header Bar -->
                <div class="flex items-start justify-between border-b border-slate-800 pb-6 mb-8">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wider mb-2">
                            <i class="fa-solid fa-lock-open mr-1.5 text-xs"></i> Hub Access Requirement
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Tell Us About Your Idea
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-400 mt-1">
                            Fill in your details and idea overview below. Submitting will register your pitch directly into our Admin Portal and immediately unlock the main website.
                        </p>
                    </div>
                </div>

                <!-- Error / Feedback Alert Box -->
                <div id="form-error-alert" class="hidden mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-200 text-xs sm:text-sm flex items-start space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5 text-base shrink-0"></i>
                    <div id="form-error-message" class="font-medium"></div>
                </div>

                <!-- Success Unlock Modal (Celebration state) -->
                <div id="form-success-box" class="hidden text-center py-8 px-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-300 flex items-center justify-center mx-auto mb-4 text-2xl animate-bounce">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">Submission Successful!</h3>
                    <p class="text-slate-300 mt-2 max-w-md mx-auto text-sm">
                        Thank you! Your idea has been sent directly to the NextGen Farming Incubation Committee. Full website access is now unlocked.
                    </p>

                    <!-- Dynamic Track Counter Update Notification Box -->
                    <div class="mt-5 p-3.5 rounded-xl bg-slate-950 border border-emerald-500/50 text-left max-w-md mx-auto flex items-center justify-between shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-base shrink-0">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Live Impact Counter</span>
                                <span class="text-xs sm:text-sm font-extrabold text-white" id="success-category-feedback">Dynamic Counter Updated (+1)</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-black text-emerald-400 bg-emerald-950 border border-emerald-800 px-2.5 py-1 rounded-md animate-pulse shrink-0">
                            +1 Added
                        </span>
                    </div>

                    <div class="mt-6 flex flex-col items-center justify-center space-y-3">
                        <div class="inline-flex items-center text-xs text-emerald-400 font-semibold bg-emerald-950/60 px-4 py-2 rounded-full border border-emerald-800">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Redirecting to main website in <span id="countdown-sec" class="font-bold ml-1">3</span>s...
                        </div>
                        <a href="index.php?unlocked=1" class="text-xs text-slate-400 hover:text-white underline">
                            Click here if you are not redirected automatically
                        </a>
                    </div>
                </div>

                <!-- The Actual Intake Form -->
                <form id="landing-intake-form" action="api/submit_idea.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <!-- Honeypot field (anti-spam) -->
                    <input type="text" name="website_trap" value="" style="display:none !important;" tabindex="-1" autocomplete="off">
                    <input type="hidden" name="is_ajax" value="1">
                    <input type="hidden" id="video_duration_input" name="video_duration" value="">

                    <!-- Selecting Field on Top: Category Track -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-slate-950/90 border border-emerald-500/30 shadow-inner">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider">
                                Select Your Category / Track <span class="text-emerald-400">*</span>
                            </label>
                            <span class="text-[11px] text-emerald-400 font-semibold flex items-center">
                                <i class="fa-solid fa-circle-check mr-1 text-[10px]"></i> Live Sync with Impact Counter
                            </span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5" id="category-selection-container">
                            
                            <!-- Option 1: Entrepreneurs -->
                            <label class="category-pill-label cursor-pointer relative flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl border-2 border-emerald-500 bg-emerald-950/50 text-emerald-300 shadow-sm transition-all duration-200 hover:scale-[1.02] ring-2 ring-emerald-500/40">
                                <input type="radio" name="category" value="Entrepreneurs" checked class="sr-only category-radio">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center mb-1.5 text-sm">
                                    <i class="fa-solid fa-seedling"></i>
                                </div>
                                <span class="text-xs font-black tracking-tight text-white block">Entrepreneurs</span>
                                <span class="text-[10px] text-emerald-300 font-medium block mt-0.5">Innovators & Startups</span>
                                <span class="active-indicator absolute top-2 right-2 text-emerald-400 text-xs">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            </label>

                            <!-- Option 2: Investors -->
                            <label class="category-pill-label cursor-pointer relative flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl border border-slate-700/80 bg-slate-900/80 text-slate-400 shadow-sm transition-all duration-200 hover:scale-[1.02] hover:border-slate-600">
                                <input type="radio" name="category" value="Investors" class="sr-only category-radio">
                                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-300 flex items-center justify-center mb-1.5 text-sm">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <span class="text-xs font-black tracking-tight text-white block">Investors</span>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Angel & Capital</span>
                                <span class="active-indicator absolute top-2 right-2 text-emerald-400 text-xs hidden">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            </label>

                            <!-- Option 3: Mentors -->
                            <label class="category-pill-label cursor-pointer relative flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl border border-slate-700/80 bg-slate-900/80 text-slate-400 shadow-sm transition-all duration-200 hover:scale-[1.02] hover:border-slate-600">
                                <input type="radio" name="category" value="Mentors" class="sr-only category-radio">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-300 flex items-center justify-center mb-1.5 text-sm">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                </div>
                                <span class="text-xs font-black tracking-tight text-white block">Mentors</span>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Advisors & Experts</span>
                                <span class="active-indicator absolute top-2 right-2 text-emerald-400 text-xs hidden">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            </label>

                            <!-- Option 4: Organizers -->
                            <label class="category-pill-label cursor-pointer relative flex flex-col items-center justify-center p-3 sm:p-3.5 rounded-xl border border-slate-700/80 bg-slate-900/80 text-slate-400 shadow-sm transition-all duration-200 hover:scale-[1.02] hover:border-slate-600">
                                <input type="radio" name="category" value="Organizers" class="sr-only category-radio">
                                <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-300 flex items-center justify-center mb-1.5 text-sm">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <span class="text-xs font-black tracking-tight text-white block">Organizers</span>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Co-ops & Hubs</span>
                                <span class="active-indicator absolute top-2 right-2 text-emerald-400 text-xs hidden">
                                    <i class="fa-solid fa-circle-check"></i>
                                </span>
                            </label>

                        </div>
                    </div>

                    <!-- Field 1: Name & Field 2: Email (Optional) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Name (Required) -->
                        <div>
                            <label for="field-full-name" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                                Full Name <span class="text-emerald-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </div>
                                <input type="text" id="field-full-name" name="full_name" required placeholder="e.g., Mohammad Rafiqul Islam" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                            </div>
                        </div>

                        <!-- Email (Optional) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="field-email" class="block text-xs font-bold text-slate-200 uppercase tracking-wider">
                                    Email Address
                                </label>
                                <span class="text-[10px] text-slate-400 font-semibold bg-slate-800 px-2 py-0.5 rounded">Optional</span>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </div>
                                <input type="email" id="field-email" name="email" placeholder="e.g., rafiqul@example.com" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                            </div>
                        </div>

                    </div>

                    <!-- Field 3: Phone number (WhatsApp) & Field 4: Education -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <!-- Phone Number (WhatsApp) (Required) -->
                        <div>
                            <label for="field-phone" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                                Phone Number (WhatsApp) <span class="text-emerald-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-500">
                                    <i class="fa-brands fa-whatsapp text-base"></i>
                                </div>
                                <input type="tel" id="field-phone" name="phone" required placeholder="e.g., +880 1712-345678" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                            </div>
                            <span class="block text-[11px] text-emerald-400/90 mt-1 font-medium">
                                <i class="fa-solid fa-circle-info mr-1"></i> Our team will reach out directly via WhatsApp.
                            </span>
                        </div>

                        <!-- Education (Required) -->
                        <div>
                            <label for="field-education" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                                Education / Background <span class="text-emerald-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                    <i class="fa-solid fa-graduation-cap text-sm"></i>
                                </div>
                                <input type="text" id="field-education" name="education" required placeholder="e.g., B.Sc. in Agriculture, BAU / Engineering / Diploma / Farmer" list="education-suggestions" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                                <datalist id="education-suggestions">
                                    <option value="B.Sc. in Agriculture (Honours)">
                                    <option value="M.Sc. in Agronomy / Soil Science">
                                    <option value="Biotechnology & Genetic Engineering">
                                    <option value="Computer Science & Engineering (IoT / AI)">
                                    <option value="Agricultural Engineering & Machinery">
                                    <option value="Business Administration (BBA / MBA)">
                                    <option value="Diploma in Agriculture / Polytechnic">
                                    <option value="Higher Secondary (HSC / Alim)">
                                    <option value="Practicing Farmer / Rural Agribusiness">
                                    <option value="Self-Taught Innovator">
                                </datalist>
                            </div>
                        </div>

                    </div>

                    <!-- Field 5: Local Address (Required) -->
                    <div>
                        <label for="field-address" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                            Local Address <span class="text-emerald-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </div>
                            <input type="text" id="field-address" name="local_address" required placeholder="e.g., Village/Road, Upazila/Thana, District (e.g. Sherpur, Bogura / Trishal, Mymensingh / Dhaka)" class="w-full pl-10 pr-4 py-3 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                        </div>
                    </div>

                    <!-- Field 6: Tell us a little bit about your idea (Text Box) (Required) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="field-idea" class="block text-xs font-bold text-slate-200 uppercase tracking-wider">
                                Tell us a little bit about your idea <span class="text-emerald-400">*</span>
                            </label>
                            <span id="char-counter" class="text-[11px] text-slate-500 font-mono">0 characters</span>
                        </div>
                        <textarea id="field-idea" name="idea_description" required rows="5" placeholder="Describe your farming innovation, agri-tech solution, or business concept. What problem does it solve? Who are your target farmers or buyers? What prototype or stage are you at?" class="w-full p-4 bg-slate-950 border border-slate-700/80 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition leading-relaxed"></textarea>
                    </div>

                    <!-- Field 7: Upload Videos Option with 5 Minutes (Optional) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-200 uppercase tracking-wider">
                                Upload Your Own Presentation Video (Optional • Max 5 Minutes)
                            </label>
                            <span class="text-[10px] text-emerald-400 font-semibold bg-emerald-950 border border-emerald-800/60 px-2 py-0.5 rounded">
                                <i class="fa-solid fa-stopwatch mr-1"></i> Max 5 mins (Optional)
                            </span>
                        </div>

                        <!-- Drag & Drop Container -->
                        <div id="video-dropzone" class="relative border-2 border-dashed border-slate-700 hover:border-emerald-500/60 bg-slate-950/70 hover:bg-slate-950 rounded-2xl p-6 text-center transition cursor-pointer group">
                            <input type="file" id="video-file-input" name="pitch_video" accept="video/mp4,video/webm,video/quicktime,video/x-msvideo,video/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            
                            <!-- Dropzone Default View -->
                            <div id="dropzone-idle-state" class="space-y-2 pointer-events-none">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 group-hover:bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto transition transform group-hover:scale-105">
                                    <i class="fa-solid fa-video text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white group-hover:text-emerald-300 transition">
                                        Click to choose video or drag and drop
                                    </p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        MP4, WebM, MOV, AVI • <strong class="text-emerald-400">Duration must be 5 minutes or less</strong>
                                    </p>
                                </div>
                                <span class="inline-block text-[10px] uppercase tracking-wider font-bold text-slate-500 bg-slate-900 px-2.5 py-1 rounded-md border border-slate-800">
                                    Live automatic duration check enabled
                                </span>
                            </div>

                            <!-- Duration Warning Error State (Over 5 Minutes) -->
                            <div id="dropzone-overlength-error" class="hidden text-rose-300 space-y-2 py-2">
                                <i class="fa-solid fa-triangle-exclamation text-3xl text-rose-400 block mb-1"></i>
                                <span class="font-bold text-sm block" id="overlength-text">Video duration exceeds 5 minutes</span>
                                <span class="text-xs text-slate-400 block">Please select or trim a video of 5 minutes (300 seconds) or less.</span>
                                <button type="button" id="reselect-video-btn" class="mt-2 text-xs bg-rose-900/60 hover:bg-rose-900 text-rose-200 px-3 py-1.5 rounded-lg border border-rose-700 transition">
                                    Choose another video
                                </button>
                            </div>

                            <!-- Dropzone Valid Video Preview State -->
                            <div id="dropzone-preview-state" class="hidden space-y-3 py-2 text-left">
                                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                    <div class="flex items-center space-x-3 truncate">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-film text-lg"></i>
                                        </div>
                                        <div class="truncate">
                                            <span id="preview-filename" class="text-xs font-bold text-white block truncate">video.mp4</span>
                                            <div class="flex items-center space-x-2 text-[11px] text-slate-400">
                                                <span id="preview-duration" class="text-emerald-400 font-bold flex items-center">
                                                    <i class="fa-solid fa-check-circle mr-1"></i> 2m 45s
                                                </span>
                                                <span>•</span>
                                                <span id="preview-filesize">18.5 MB</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="remove-video-btn" class="text-xs text-rose-400 hover:text-rose-300 hover:bg-rose-950/60 px-2.5 py-1.5 rounded-lg border border-rose-900/40 transition shrink-0">
                                        <i class="fa-solid fa-trash mr-1"></i> Remove
                                    </button>
                                </div>

                                <!-- Inline Video Preview -->
                                <div class="rounded-xl overflow-hidden bg-black max-h-56 flex items-center justify-center">
                                    <video id="video-preview-player" controls class="max-h-56 w-full object-contain"></video>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Upload Progress Bar (Visible during video upload) -->
                    <div id="upload-progress-container" class="hidden space-y-2">
                        <div class="flex justify-between text-xs text-slate-300 font-medium">
                            <span id="progress-status-text"><i class="fa-solid fa-cloud-arrow-up mr-1 text-emerald-400"></i> Uploading your idea & pitch video...</span>
                            <span id="progress-percentage-text" class="font-bold text-emerald-400">0%</span>
                        </div>
                        <div class="w-full bg-slate-800 rounded-full h-2.5 overflow-hidden">
                            <div id="upload-progress-bar" class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2.5 rounded-full transition-all duration-200" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" id="submit-intake-btn" class="w-full py-4 px-6 bg-gradient-to-r from-emerald-500 via-agro-700 to-emerald-600 hover:from-emerald-400 hover:via-agro-600 hover:to-emerald-500 text-slate-950 font-black text-base sm:text-lg rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                            <span>Submit Idea & Unlock Full Website</span>
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </button>
                        
                        <p class="text-center text-[11px] text-slate-500 mt-3 flex items-center justify-center">
                            <i class="fa-solid fa-shield-halved mr-1.5 text-emerald-500"></i>
                            Your intellectual property and concept are securely handled under Incubation NDA policies.
                        </p>
                    </div>

                </form>

            </div>

        </section>

        <!-- Incubation Advantages & Process Section -->
        <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-20 relative z-10">
            
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Accelerate With NextGen Hub</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">What Happens After You Submit?</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-xl mx-auto">
                    A streamlined journey engineered specifically for agri-founders, technologists, and rural innovators.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-black text-sm">
                        01
                    </div>
                    <h4 class="text-lg font-bold text-white">Instant Website Access</h4>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Immediately upon submission, you gain full, unrestricted access to browse our flagship programs, curriculum modules, living testbed specs, and corporate network.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-300 font-black text-sm">
                        02
                    </div>
                    <h4 class="text-lg font-bold text-white">Committee Evaluation</h4>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Our incubation directors and technical agronomists review your idea text and pitch video within 48 to 72 hours, evaluating fit across our pre-seed and growth tracks.
                    </p>
                </div>

                <div class="bg-slate-900/60 border border-slate-800 rounded-2xl p-6 backdrop-blur space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/30 flex items-center justify-center text-teal-300 font-black text-sm">
                        03
                    </div>
                    <h4 class="text-lg font-bold text-white">WhatsApp Founder Connect</h4>
                    <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                        Shortlisted applicants receive a direct WhatsApp invitation for a 1-on-1 virtual or Bogura field-lab interview with our investment syndicate partners.
                    </p>
                </div>

            </div>

        </section>

        <!-- FAQ Section -->
        <section class="max-w-3xl mx-auto px-4 sm:px-6 mt-16 relative z-10">
            <h4 class="text-xl font-extrabold text-white text-center mb-6">Frequently Asked Questions</h4>
            
            <div class="space-y-3 text-xs sm:text-sm">
                
                <details class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 text-slate-300 group cursor-pointer">
                    <summary class="font-bold text-white flex items-center justify-between list-none">
                        <span>Why do I need to submit an idea before viewing the full website?</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-3 text-slate-400 leading-relaxed">
                        NextGen Farming Incubation Hub is a high-conviction ecosystem dedicated to active founders and agri-entrepreneurs. Capturing your concept ensures our admissions committee can provide bespoke feedback, match you with living testbeds, and maintain an engaged innovator cohort.
                    </p>
                </details>

                <details class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 text-slate-300 group cursor-pointer">
                    <summary class="font-bold text-white flex items-center justify-between list-none">
                        <span>Is the video pitch mandatory?</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-3 text-slate-400 leading-relaxed">
                        No, the video upload is 100% optional! You can submit with just the text box description. However, a quick mobile phone video (up to 5 minutes) introducing yourself and showing your farm, prototype, or test results significantly speeds up evaluation.
                    </p>
                </details>

                <details class="bg-slate-900/60 border border-slate-800 rounded-xl p-4 text-slate-300 group cursor-pointer">
                    <summary class="font-bold text-white flex items-center justify-between list-none">
                        <span>What if my video exceeds 5 minutes?</span>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-3 text-slate-400 leading-relaxed">
                        Our system enforces a strict 5-minute (300 seconds) duration limit to maintain standardized evaluation. If your video is longer, our interactive file checker will alert you so you can quickly trim it or record a focused 3-5 minute summary pitch.
                    </p>
                </details>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 border-t border-slate-900 text-slate-500 text-xs py-8 px-4 text-center">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>&copy; <?= date('Y') ?> NextGen Farming Incubation Hub. All rights reserved.</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>Dhaka • Bogura • Khulna • Rajshahi</span>
                <span>|</span>
                <a href="admin/login.php" class="text-slate-400 hover:text-emerald-400 transition">Admin Portal</a>
            </div>
        </div>
    </footer>

    <!-- Client-Side Validation & Video Duration Checker Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('landing-intake-form');
            const submitBtn = document.getElementById('submit-intake-btn');
            const videoInput = document.getElementById('video-file-input');
            const durationHidden = document.getElementById('video_duration_input');
            const idleState = document.getElementById('dropzone-idle-state');
            const previewState = document.getElementById('dropzone-preview-state');
            const overlengthError = document.getElementById('dropzone-overlength-error');
            const overlengthText = document.getElementById('overlength-text');
            const reselectBtn = document.getElementById('reselect-video-btn');
            const removeBtn = document.getElementById('remove-video-btn');
            const previewPlayer = document.getElementById('video-preview-player');
            const previewFilename = document.getElementById('preview-filename');
            const previewDuration = document.getElementById('preview-duration');
            const previewFilesize = document.getElementById('preview-filesize');
            const errorAlert = document.getElementById('form-error-alert');
            const errorMessage = document.getElementById('form-error-message');
            const successBox = document.getElementById('form-success-box');
            const progressContainer = document.getElementById('upload-progress-container');
            const progressBar = document.getElementById('upload-progress-bar');
            const progressPercent = document.getElementById('progress-percentage-text');
            const charCounter = document.getElementById('char-counter');
            const ideaInput = document.getElementById('field-idea');

            // Maximum allowed video duration: 5 minutes = 300 seconds (with a 2s grace threshold)
            const MAX_DURATION_SEC = 300;

            // Character counter for idea description
            if (ideaInput && charCounter) {
                ideaInput.addEventListener('input', function() {
                    const len = this.value.length;
                    charCounter.textContent = len + ' character' + (len === 1 ? '' : 's');
                });
            }

            function showError(msg) {
                if (errorMessage && errorAlert) {
                    errorMessage.textContent = msg;
                    errorAlert.classList.remove('hidden');
                    errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            function clearError() {
                if (errorAlert) {
                    errorAlert.classList.add('hidden');
                }
            }

            function resetVideoSelection() {
                videoInput.value = '';
                durationHidden.value = '';
                previewPlayer.pause();
                previewPlayer.removeAttribute('src');
                previewPlayer.load();

                idleState.classList.remove('hidden');
                previewState.classList.add('hidden');
                overlengthError.classList.add('hidden');
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    resetVideoSelection();
                });
            }

            if (reselectBtn) {
                reselectBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    resetVideoSelection();
                    videoInput.click();
                });
            }

            // Video Duration & Format Validation
            videoInput.addEventListener('change', function () {
                clearError();
                const file = this.files && this.files[0];
                if (!file) {
                    resetVideoSelection();
                    return;
                }

                // Check file size (100MB max)
                if (file.size > 104857600) {
                    showError('Selected video file exceeds 100MB limit. Please choose a smaller file.');
                    resetVideoSelection();
                    return;
                }

                const fileUrl = URL.createObjectURL(file);
                const tempVideo = document.createElement('video');
                tempVideo.preload = 'metadata';
                tempVideo.src = fileUrl;

                tempVideo.onloadedmetadata = function () {
                    window.URL.revokeObjectURL(tempVideo.src);
                    const duration = Math.round(tempVideo.duration);

                    if (duration > MAX_DURATION_SEC) {
                        const mins = Math.floor(duration / 60);
                        const secs = duration % 60;
                        const durationFormatted = mins + 'm ' + (secs < 10 ? '0' : '') + secs + 's';
                        
                        overlengthText.textContent = 'Video duration is ' + durationFormatted + ' (Max allowed: 5 minutes)';
                        idleState.classList.add('hidden');
                        previewState.classList.add('hidden');
                        overlengthError.classList.remove('hidden');
                        
                        // Clear the input so oversized video is not uploaded
                        videoInput.value = '';
                        durationHidden.value = '';
                    } else {
                        // Duration is valid!
                        const mins = Math.floor(duration / 60);
                        const secs = duration % 60;
                        const durationFormatted = mins + 'm ' + (secs < 10 ? '0' : '') + secs + 's';

                        durationHidden.value = duration;
                        previewFilename.textContent = file.name;
                        previewDuration.innerHTML = '<i class="fa-solid fa-check-circle mr-1"></i> ' + durationFormatted + ' (Max 5m)';
                        previewFilesize.textContent = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                        
                        previewPlayer.src = fileUrl;

                        idleState.classList.add('hidden');
                        overlengthError.classList.add('hidden');
                        previewState.classList.remove('hidden');
                    }
                };

                tempVideo.onerror = function () {
                    // Browser could not load metadata (e.g. rare codec or unsupported preview)
                    // We still allow it through to the server for validation
                    durationHidden.value = '';
                    previewFilename.textContent = file.name;
                    previewDuration.innerHTML = '<i class="fa-solid fa-clock mr-1"></i> Duration will be verified on upload';
                    previewFilesize.textContent = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                    
                    idleState.classList.add('hidden');
                    overlengthError.classList.add('hidden');
                    previewState.classList.remove('hidden');
                };
            });

            // Category Selection & Trust/Impact Counter Interactive Sync
            const categoryRadios = document.querySelectorAll('.category-radio');
            const categoryCards = document.querySelectorAll('.category-pill-label');
            const counterCards = document.querySelectorAll('.impact-counter-card');

            function highlightCategoryTrack(selectedCategory) {
                // 1. Update form radio buttons and active styling
                categoryCards.forEach(card => {
                    const radio = card.querySelector('.category-radio');
                    const indicator = card.querySelector('.active-indicator');
                    if (radio && radio.value === selectedCategory) {
                        radio.checked = true;
                        card.classList.remove('border-slate-700/80', 'bg-slate-900/80', 'text-slate-400');
                        card.classList.add('border-2', 'border-emerald-500', 'bg-emerald-950/50', 'text-emerald-300', 'ring-2', 'ring-emerald-500/40', 'scale-[1.02]');
                        if (indicator) indicator.classList.remove('hidden');
                    } else {
                        card.classList.remove('border-2', 'border-emerald-500', 'bg-emerald-950/50', 'text-emerald-300', 'ring-2', 'ring-emerald-500/40', 'scale-[1.02]');
                        card.classList.add('border', 'border-slate-700/80', 'bg-slate-900/80', 'text-slate-400');
                        if (indicator) indicator.classList.add('hidden');
                    }
                });

                // 2. Dynamically highlight the matching Trust / Impact Counter Card
                counterCards.forEach(card => {
                    const cat = card.getAttribute('data-category');
                    if (cat === selectedCategory) {
                        card.classList.add('border-2', 'border-emerald-500/80', 'ring-2', 'ring-emerald-500/30', 'scale-[1.03]', 'shadow-lg', 'shadow-emerald-950/50', 'bg-slate-900/90');
                        card.classList.remove('border-slate-800/80', 'bg-slate-900/60');
                    } else {
                        card.classList.remove('border-2', 'border-emerald-500/80', 'ring-2', 'ring-emerald-500/30', 'scale-[1.03]', 'shadow-lg', 'shadow-emerald-950/50', 'bg-slate-900/90');
                        card.classList.add('border', 'border-slate-800/80', 'bg-slate-900/60');
                    }
                });
            }

            // Category radio input change listeners
            categoryRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.checked) {
                        highlightCategoryTrack(this.value);
                    }
                });
            });

            // Make clicking on category pill card activate the radio and update styling
            categoryCards.forEach(card => {
                card.addEventListener('click', function () {
                    const radio = this.querySelector('.category-radio');
                    if (radio) {
                        radio.checked = true;
                        highlightCategoryTrack(radio.value);
                    }
                });
            });

            // Trust / Impact Counter cards click to auto-select matching category & scroll to form
            counterCards.forEach(card => {
                card.addEventListener('click', function () {
                    const cat = this.getAttribute('data-category');
                    if (cat) {
                        highlightCategoryTrack(cat);
                        const intakeSection = document.getElementById('intake-form-section');
                        if (intakeSection) {
                            intakeSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });

            // Form Submit via AJAX with Upload Progress
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                clearError();

                const fullName = document.getElementById('field-full-name').value.trim();
                const phone = document.getElementById('field-phone').value.trim();
                const address = document.getElementById('field-address').value.trim();
                const education = document.getElementById('field-education').value.trim();
                const idea = ideaInput.value.trim();

                if (!fullName || !phone || !address || !education || !idea) {
                    showError('Please fill in all mandatory fields (Name, WhatsApp Phone, Address, Education, and Idea Description).');
                    return;
                }

                // Prepare FormData
                const formData = new FormData(form);

                // Disable submit button & show loading state
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Submitting Idea & Unlocking Access...';

                // Display progress bar if video is attached
                const hasVideo = videoInput.files && videoInput.files.length > 0;
                if (hasVideo) {
                    progressContainer.classList.remove('hidden');
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

                // Progress tracking
                xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable && hasVideo) {
                        const percent = Math.round((event.loaded / event.total) * 100);
                        progressBar.style.width = percent + '%';
                        progressPercent.textContent = percent + '%';
                    }
                };

                xhr.onload = function () {
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (err) {
                        response = { status: 'error', message: 'Unexpected server response. Please try again.' };
                    }

                    if (xhr.status >= 200 && xhr.status < 300 && response.status === 'success') {
                        const submittedCat = response.category || response.submitted_category || 'Entrepreneurs';

                        // Dynamically update Trust / Impact Counter numbers in real time
                        if (response.updated_counts) {
                            ['entrepreneurs', 'investors', 'mentors', 'organizers'].forEach(k => {
                                const capKey = k.charAt(0).toUpperCase() + k.slice(1);
                                const valEl = document.getElementById('counter-val-' + k);
                                if (valEl && response.updated_counts[capKey] !== undefined) {
                                    const oldVal = parseInt(valEl.textContent.trim(), 10) || 0;
                                    const newVal = parseInt(response.updated_counts[capKey], 10);
                                    valEl.textContent = newVal;
                                    if (newVal > oldVal) {
                                        valEl.parentElement.classList.add('scale-125', 'transition-transform', 'duration-300');
                                        setTimeout(() => valEl.parentElement.classList.remove('scale-125'), 600);
                                    }
                                }
                            });

                            const subCatKey = submittedCat.toLowerCase();
                            const activeCounterCard = document.getElementById('counter-card-' + subCatKey);
                            if (activeCounterCard) {
                                activeCounterCard.classList.add('ring-4', 'ring-emerald-400', 'scale-105', 'bg-emerald-950/80', 'transition-all');
                            }
                        }

                        // Update dynamic feedback in success box
                        const successFeedback = document.getElementById('success-category-feedback');
                        if (successFeedback && response.updated_counts && response.updated_counts[submittedCat] !== undefined) {
                            successFeedback.textContent = submittedCat + ' Track: ' + response.updated_counts[submittedCat] + '+ Impact Count';
                        } else if (successFeedback) {
                            successFeedback.textContent = submittedCat + ' Track: Verified & Dynamic Count Incremented';
                        }

                        // Success! Hide form and show celebration
                        form.classList.add('hidden');
                        successBox.classList.remove('hidden');

                        // Scroll smoothly to see the updated counters and success message
                        const countersEl = document.getElementById('impact-counters-container');
                        if (countersEl) {
                            countersEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        let sec = 3;
                        const countdownEl = document.getElementById('countdown-sec');
                        const timer = setInterval(function () {
                            sec--;
                            if (countdownEl) countdownEl.textContent = sec;
                            if (sec <= 0) {
                                clearInterval(timer);
                                window.location.href = response.redirect || 'index.php?unlocked=1';
                            }
                        }, 1000);
                    } else {
                        // Error returned by server
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        submitBtn.innerHTML = '<span>Submit Idea & Unlock Full Website</span><i class="fa-solid fa-arrow-right text-sm ml-2"></i>';
                        progressContainer.classList.add('hidden');
                        showError(response.message || 'Could not submit your idea. Please verify the form and try again.');
                    }
                };

                xhr.onerror = function () {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    submitBtn.innerHTML = '<span>Submit Idea & Unlock Full Website</span><i class="fa-solid fa-arrow-right text-sm ml-2"></i>';
                    progressContainer.classList.add('hidden');
                    showError('Network connection issue. Please check your internet connection and try again.');
                };

                xhr.send(formData);
            });
        });

        // Banner Carousel Interactive Controller
        (function initBannerCarousel() {
            const carousel = document.getElementById('landing-banner-carousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.carousel-slide');
            const dots = carousel.querySelectorAll('.carousel-dot-btn');
            const prevBtn = document.getElementById('carousel-btn-prev');
            const nextBtn = document.getElementById('carousel-btn-next');
            const currentCounter = document.getElementById('carousel-current-index');
            const autoplaySpeed = parseInt(carousel.getAttribute('data-autoplay') || '5000', 10);
            const totalSlides = slides.length;

            if (totalSlides <= 1) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                return;
            }

            let currentIndex = 0;
            let autoTimer = null;
            let isHovered = false;

            function goToSlide(index) {
                if (index < 0) {
                    index = totalSlides - 1;
                } else if (index >= totalSlides) {
                    index = 0;
                }

                slides.forEach((slide, i) => {
                    if (i === index) {
                        slide.classList.remove('opacity-0', 'scale-95', 'pointer-events-none', 'z-10');
                        slide.classList.add('opacity-100', 'scale-100', 'z-20');
                    } else {
                        slide.classList.remove('opacity-100', 'scale-100', 'z-20');
                        slide.classList.add('opacity-0', 'scale-95', 'pointer-events-none', 'z-10');
                    }
                });

                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.remove('w-2.5', 'bg-white/40');
                        dot.classList.add('w-8', 'bg-emerald-400', 'shadow-md', 'shadow-emerald-400/50');
                    } else {
                        dot.classList.remove('w-8', 'bg-emerald-400', 'shadow-md', 'shadow-emerald-400/50');
                        dot.classList.add('w-2.5', 'bg-white/40');
                    }
                });

                if (currentCounter) {
                    currentCounter.textContent = (index + 1);
                }

                currentIndex = index;
            }

            function startAutoplay() {
                if (autoplaySpeed > 0 && !autoTimer) {
                    autoTimer = setInterval(function () {
                        if (!isHovered) {
                            goToSlide(currentIndex + 1);
                        }
                    }, autoplaySpeed);
                }
            }

            function stopAutoplay() {
                if (autoTimer) {
                    clearInterval(autoTimer);
                    autoTimer = null;
                }
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    stopAutoplay();
                    goToSlide(currentIndex + 1);
                    startAutoplay();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    stopAutoplay();
                    goToSlide(currentIndex - 1);
                    startAutoplay();
                });
            }

            dots.forEach(dot => {
                dot.addEventListener('click', function () {
                    const targetIdx = parseInt(this.getAttribute('data-dot-index'), 10);
                    stopAutoplay();
                    goToSlide(targetIdx);
                    startAutoplay();
                });
            });

            // Pause on hover
            carousel.addEventListener('mouseenter', function () {
                isHovered = true;
            });
            carousel.addEventListener('mouseleave', function () {
                isHovered = false;
            });

            // Touch Swipe support for Mobile
            let touchStartX = 0;
            let touchEndX = 0;

            carousel.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            carousel.addEventListener('touchend', function (e) {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 45) {
                    goToSlide(currentIndex + 1); // Swipe left -> Next
                } else if (touchEndX - touchStartX > 45) {
                    goToSlide(currentIndex - 1); // Swipe right -> Prev
                }
            }, { passive: true });

            startAutoplay();
        })();
    </script>

</body>
</html>
