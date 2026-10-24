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
                    <img src="<?= htmlspecialchars($dynSiteLogoUrl) ?>" alt="<?= htmlspecialchars($dynSiteName) ?>" class="h-9 sm:h-10 w-auto max-w-[200px] object-contain transition group-hover:scale-105" onerror="this.style.display='none'; document.getElementById('default-landing-brand').style.display='flex';">
                    <div id="default-landing-brand" style="display: none;" class="items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-agro-800 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                            <i class="fa-solid fa-leaf text-lg text-emerald-200"></i>
                        </div>
                        <div>
                            <span class="text-lg font-extrabold text-white tracking-tight">NextGen <span class="text-emerald-400">Farming</span></span>
                            <span class="block text-[10px] font-bold text-emerald-300 uppercase tracking-widest -mt-1">Incubation Hub</span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-agro-800 flex items-center justify-center text-white shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-leaf text-lg text-emerald-200"></i>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-white tracking-tight">NextGen <span class="text-emerald-400">Farming</span></span>
                        <span class="block text-[10px] font-bold text-emerald-300 uppercase tracking-widest -mt-1">Incubation Hub</span>
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

        <!-- Hero Intro Section -->
        <section class="pt-12 sm:pt-16 pb-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto text-center relative z-10">
            
            <div class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold bg-emerald-900/40 text-emerald-300 border border-emerald-500/30 mb-6 backdrop-blur">
                <i class="fa-solid fa-seedling mr-2 text-emerald-400"></i> Agricultural Entrepreneurship Zone
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight sm:leading-none">
                Transform Your Idea to a <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-amber-300 bg-clip-text text-transparent">Scale Base Agri Business</span>
            </h1> 

            <p class="mt-5 text-sm sm:text-base lg:text-lg text-slate-300 max-w-3xl mx-auto leading-relaxed">
                NextGen Farming Incubation Hub unlocks pre-seed capital, 500+ acres of living farmland testbeds, and direct commercial off-take partnerships. Share your idea below to unlock full access to our platform and pitch directly to our incubation committee.
            </p>

            <!-- Trust / Impact Counters -->
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 max-w-4xl mx-auto text-left">
                <div class="bg-slate-900/60 border border-slate-800/80 p-3.5 sm:p-4 rounded-xl backdrop-blur">
                    <span class="text-xl sm:text-2xl font-black text-emerald-400 block">100+</span>
                    <span class="text-[11px] sm:text-xs text-slate-400 font-medium">Entrepreneurs</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800/80 p-3.5 sm:p-4 rounded-xl backdrop-blur">
                    <span class="text-xl sm:text-2xl font-black text-amber-300 block">20+</span>
                    <span class="text-[11px] sm:text-xs text-slate-400 font-medium">Investors</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800/80 p-3.5 sm:p-4 rounded-xl backdrop-blur">
                    <span class="text-xl sm:text-2xl font-black text-emerald-400 block">15+</span>
                    <span class="text-[11px] sm:text-xs text-slate-400 font-medium">Mentors</span>
                </div>
                <div class="bg-slate-900/60 border border-slate-800/80 p-3.5 sm:p-4 rounded-xl backdrop-blur">
                    <span class="text-xl sm:text-2xl font-black text-teal-300 block">24+</span>
                    <span class="text-[11px] sm:text-xs text-slate-400 font-medium">Organizers</span>
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
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0 hidden sm:flex">
                        <i class="fa-solid fa-lightbulb text-xl"></i>
                    </div>
                </div>

                <!-- Error / Feedback Alert Box -->
                <div id="form-error-alert" class="hidden mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-600/50 text-rose-200 text-xs sm:text-sm flex items-start space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5 text-base shrink-0"></i>
                    <div id="form-error-message" class="font-medium"></div>
                </div>

                <!-- Success Unlock Modal (Celebration state) -->
                <div id="form-success-box" class="hidden text-center py-10 px-4">
                    <div class="w-20 h-20 rounded-full bg-emerald-500/20 border-2 border-emerald-400 text-emerald-300 flex items-center justify-center mx-auto mb-6 text-3xl animate-bounce">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white">Submission Successful!</h3>
                    <p class="text-slate-300 mt-2 max-w-md mx-auto text-sm">
                        Thank you! Your idea has been sent directly to the NextGen Farming Incubation Committee. Full website access is now unlocked.
                    </p>
                    <div class="mt-6 flex flex-col items-center justify-center space-y-3">
                        <div class="inline-flex items-center text-xs text-emerald-400 font-semibold bg-emerald-950/60 px-4 py-2 rounded-full border border-emerald-800">
                            <i class="fa-solid fa-spinner fa-spin mr-2"></i> Redirecting to main website in <span id="countdown-sec" class="font-bold ml-1">2</span>s...
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
                        // Success! Hide form and show celebration
                        form.classList.add('hidden');
                        successBox.classList.remove('hidden');

                        let sec = 2;
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
    </script>

</body>
</html>
