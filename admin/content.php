<?php
/**
 * NextGen Farming Incubation Hub - Multi-Page Content CMS (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';

// Handle form submission to update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_content') {
    $allowedFields = [
        // Home & Announcement
        'hero_tagline', 'hero_title_main', 'hero_title_highlight', 'hero_subtitle', 'hero_description',
        'hero_banner_text', 'hero_banner_badge', 'hero_banner_link',
        'metric_members', 'metric_members_suffix', 'metric_members_desc',
        'metric_ventures', 'metric_ventures_suffix', 'metric_ventures_desc',
        'metric_capital', 'metric_organizers', 'metric_organizers_suffix', 'metric_organizers_desc',
        'contact_email', 'contact_phone', 'contact_address', 'admissions_email', 'investors_email',

        // Programs Page Content
        'programs_badge', 'programs_title', 'programs_desc',
        'curriculum_phase1_weeks', 'curriculum_phase1_title', 'curriculum_phase1_desc',
        'curriculum_phase2_weeks', 'curriculum_phase2_title', 'curriculum_phase2_desc',
        'curriculum_phase3_weeks', 'curriculum_phase3_title', 'curriculum_phase3_desc',
        'curriculum_phase4_weeks', 'curriculum_phase4_title', 'curriculum_phase4_desc',
        'faq1_q', 'faq1_a', 'faq2_q', 'faq2_a', 'faq3_q', 'faq3_a', 'faq4_q', 'faq4_a',

        // Core Offerings Page Content
        'offerings_badge', 'offerings_title_main', 'offerings_title_highlight', 'offerings_desc',
        'offering_stat1_num', 'offering_stat1_desc',
        'offering_stat2_num', 'offering_stat2_desc',
        'offering_stat3_num', 'offering_stat3_desc',
        'offering_stat4_num', 'offering_stat4_desc',

        // Networking Page Content
        'networking_badge', 'networking_title_main', 'networking_title_highlight', 'networking_desc',
        'network_stat1_num', 'network_stat1_label', 'network_stat1_desc',
        'network_stat2_num', 'network_stat2_label', 'network_stat2_desc',
        'network_stat3_num', 'network_stat3_label', 'network_stat3_desc',
        'network_stat4_num', 'network_stat4_label', 'network_stat4_desc',
    ];

    $updates = [];
    foreach ($allowedFields as $f) {
        if (isset($_POST[$f])) {
            $updates[$f] = trim($_POST[$f]);
        }
    }

    if (updateSiteSettings($updates)) {
        $successMsg = 'Content across selected pages has been successfully published live!';
    } else {
        $errorMsg = 'Could not save some settings. Please verify database connectivity.';
    }
}

$settings = getAllSiteSettings();
$activeTab = $_GET['tab'] ?? 'home';

renderAdminHeader('content', 'Website Content CMS', 'Content CMS');
?>

<div class="max-w-5xl">
    
    <!-- Page Title & Quick Links -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Multi-Page Content & CMS Settings</h1>
            <p class="text-xs text-slate-500 mt-0.5">Edit live hero banners, curriculum schedules, FAQs, and capability statistics across all public pages.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="../programs.php" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                Programs
            </a>
            <a href="../offerings.php" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                Offerings
            </a>
            <a href="../networking.php" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                Networking
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if ($successMsg): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold"><?= htmlspecialchars($successMsg) ?></span>
            </div>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <span class="font-bold"><?= htmlspecialchars($errorMsg) ?></span>
            </div>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-slate-200 pb-3 text-xs">
        <button type="button" data-tab-target="tab-home" class="cms-tab-btn px-4 py-2 rounded-lg font-bold border transition <?= $activeTab === 'home' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?>">
            <i class="fa-solid fa-house mr-1.5"></i> Homepage & Contact
        </button>
        <button type="button" data-tab-target="tab-programs" class="cms-tab-btn px-4 py-2 rounded-lg font-bold border transition <?= $activeTab === 'programs' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?>">
            <i class="fa-solid fa-rocket mr-1.5"></i> Programs Page (Curriculum & FAQs)
        </button>
        <button type="button" data-tab-target="tab-offerings" class="cms-tab-btn px-4 py-2 rounded-lg font-bold border transition <?= $activeTab === 'offerings' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?>">
            <i class="fa-solid fa-layer-group mr-1.5"></i> Core Offerings Page
        </button>
        <button type="button" data-tab-target="tab-networking" class="cms-tab-btn px-4 py-2 rounded-lg font-bold border transition <?= $activeTab === 'networking' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?>">
            <i class="fa-solid fa-network-wired mr-1.5"></i> Networking Page
        </button>
    </div>

    <form method="POST" action="content.php" class="space-y-6">
        <input type="hidden" name="action" value="save_content">

        <!-- ======================================================== -->
        <!-- TAB 1: HOMEPAGE, ANNOUNCEMENTS, METRICS, CONTACT -->
        <!-- ======================================================== -->
        <div id="tab-home" class="cms-tab-pane <?= $activeTab === 'home' ? '' : 'hidden' ?> space-y-6">
            
            <!-- 1. Top Announcement Strip -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Top Notification Strip</h2>
                        <p class="text-[11px] text-slate-500">Banner displayed across the top header of all public pages.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Tag</label>
                        <input type="text" name="hero_banner_badge" value="<?= htmlspecialchars($settings['hero_banner_badge'] ?? 'New Call') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Announcement Message</label>
                        <input type="text" name="hero_banner_text" value="<?= htmlspecialchars($settings['hero_banner_text'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Banner Link Target</label>
                        <input type="text" name="hero_banner_link" value="<?= htmlspecialchars($settings['hero_banner_link'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                </div>
            </div>

            <!-- 2. Home Hero Section -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Homepage Hero Section</h2>
                        <p class="text-[11px] text-slate-500">Headlines, taglines, and core proposition on index.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Pill Tagline</label>
                        <input type="text" name="hero_tagline" value="<?= htmlspecialchars($settings['hero_tagline'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline Part 1</label>
                            <input type="text" name="hero_title_main" value="<?= htmlspecialchars($settings['hero_title_main'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Headline Part 2 (Green Highlight)</label>
                            <input type="text" name="hero_title_highlight" value="<?= htmlspecialchars($settings['hero_title_highlight'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($settings['hero_subtitle'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Full Value Proposition Description</label>
                        <textarea name="hero_description" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['hero_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 3. Verified Impact & Track Record Metrics -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Track Record & Impact Numbers</h2>
                        <p class="text-[11px] text-slate-500">Displayed in the "Track Record" section of homepage and about pages.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <strong class="text-xs text-slate-800 block">1. Ecosystem Members</strong>
                        <div class="flex space-x-2">
                            <input type="text" name="metric_members" value="<?= htmlspecialchars($settings['metric_members'] ?? '5200') ?>" placeholder="5200" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <input type="text" name="metric_members_suffix" value="<?= htmlspecialchars($settings['metric_members_suffix'] ?? '+') ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                        <textarea name="metric_members_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_members_desc'] ?? '') ?></textarea>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <strong class="text-xs text-slate-800 block">2. Ventures Accelerated</strong>
                        <div class="flex space-x-2">
                            <input type="text" name="metric_ventures" value="<?= htmlspecialchars($settings['metric_ventures'] ?? '128') ?>" placeholder="128" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <input type="text" name="metric_ventures_suffix" value="<?= htmlspecialchars($settings['metric_ventures_suffix'] ?? '+') ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                        <textarea name="metric_ventures_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_ventures_desc'] ?? '') ?></textarea>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <strong class="text-xs text-slate-800 block">3. Capital Mobilized</strong>
                        <input type="text" name="metric_capital" value="<?= htmlspecialchars($settings['metric_capital'] ?? '$28.5M+') ?>" placeholder="$28.5M+" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <span class="text-[10px] text-slate-500">Cumulative funding raised by cohort startups</span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <strong class="text-xs text-slate-800 block">4. Partner Institutions</strong>
                        <div class="flex space-x-2">
                            <input type="text" name="metric_organizers" value="<?= htmlspecialchars($settings['metric_organizers'] ?? '48') ?>" placeholder="48" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <input type="text" name="metric_organizers_suffix" value="<?= htmlspecialchars($settings['metric_organizers_suffix'] ?? '+') ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                        <textarea name="metric_organizers_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_organizers_desc'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 4. Contact & Inquiries Coordinates -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-address-book"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Public Contact Coordinates</h2>
                        <p class="text-[11px] text-slate-500">Used across contact.php, footer.php, and inquiry confirmation templates.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Headquarters Physical Address</label>
                        <input type="text" name="contact_address" value="<?= htmlspecialchars($settings['contact_address'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Telephone / Hotline</label>
                        <input type="text" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">General Inquiries Email</label>
                        <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Startup Admissions Email</label>
                        <input type="email" name="admissions_email" value="<?= htmlspecialchars($settings['admissions_email'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Investor Relations Email</label>
                        <input type="email" name="investors_email" value="<?= htmlspecialchars($settings['investors_email'] ?? '') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- TAB 2: PROGRAMS & COHORTS PAGE (Banner, Curriculum, FAQs) -->
        <!-- ======================================================== -->
        <div id="tab-programs" class="cms-tab-pane <?= $activeTab === 'programs' ? '' : 'hidden' ?> space-y-6">
            
            <!-- Banner Header -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-rocket"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Programs Page Hero Header</h2>
                        <p class="text-[11px] text-slate-500">Banner displayed at the very top of programs.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Tag</label>
                        <input type="text" name="programs_badge" value="<?= htmlspecialchars($settings['programs_badge'] ?? 'Structured Incubation Tracks') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline</label>
                        <input type="text" name="programs_title" value="<?= htmlspecialchars($settings['programs_title'] ?? 'Programs & Founder Residencies') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Header Subtext Description</label>
                        <textarea name="programs_desc" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['programs_desc'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Curriculum Architecture (4 Phases) -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-timeline"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">16-Week Curriculum Phases</h2>
                        <p class="text-[11px] text-slate-500">The 4 progression steps shown in the curriculum section of programs.php.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded inline-block">Phase <?= $i ?></span>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Weeks Badge</label>
                                <input type="text" name="curriculum_phase<?= $i ?>_weeks" value="<?= htmlspecialchars($settings["curriculum_phase{$i}_weeks"] ?? '') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Phase Title</label>
                                <input type="text" name="curriculum_phase<?= $i ?>_title" value="<?= htmlspecialchars($settings["curriculum_phase{$i}_title"] ?? '') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Phase Summary</label>
                                <textarea name="curriculum_phase<?= $i ?>_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings["curriculum_phase{$i}_desc"] ?? '') ?></textarea>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Frequently Asked Questions -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Frequently Asked Questions (Accordion)</h2>
                        <p class="text-[11px] text-slate-500">Displayed in the interactive FAQ section on programs.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php for ($f = 1; $f <= 4; $f++): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-slate-800">FAQ Question #<?= $f ?></span>
                            <input type="text" name="faq<?= $f ?>_q" value="<?= htmlspecialchars($settings["faq{$f}_q"] ?? '') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <label class="block text-[11px] font-semibold text-slate-600">Answer Explanation</label>
                            <textarea name="faq<?= $f ?>_a" rows="3" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700 leading-relaxed"><?= htmlspecialchars($settings["faq{$f}_a"] ?? '') ?></textarea>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- TAB 3: CORE OFFERINGS PAGE (Hero Banner & Stats) -->
        <!-- ======================================================== -->
        <div id="tab-offerings" class="cms-tab-pane <?= $activeTab === 'offerings' ? '' : 'hidden' ?> space-y-6">
            
            <!-- Hero Header -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Offerings Page Hero Banner</h2>
                        <p class="text-[11px] text-slate-500">Displayed at the top of offerings.php. Note: Individual offering cards are managed via <a href="offerings.php" class="text-emerald-700 font-bold underline">Core Offerings Manager</a>.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Tag</label>
                        <input type="text" name="offerings_badge" value="<?= htmlspecialchars($settings['offerings_badge'] ?? 'Venture Studio & Technical Infrastructure') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline Part 1</label>
                            <input type="text" name="offerings_title_main" value="<?= htmlspecialchars($settings['offerings_title_main'] ?? 'Full-Stack Capabilities Built for') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Headline Part 2 (Gradient Highlight)</label>
                            <input type="text" name="offerings_title_highlight" value="<?= htmlspecialchars($settings['offerings_title_highlight'] ?? 'Agricultural Transformation') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtext Description</label>
                        <textarea name="offerings_desc" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['offerings_desc'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 4 Quick Stats Boxes -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Hero Infrastructure Stats (Dark Box)</h2>
                        <p class="text-[11px] text-slate-500">The 4 capability highlights on the right of the hero banner on offerings.php.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">1. Farmland Metric</label>
                        <input type="text" name="offering_stat1_num" value="<?= htmlspecialchars($settings['offering_stat1_num'] ?? '500+ Farmland Acres') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="offering_stat1_desc" value="<?= htmlspecialchars($settings['offering_stat1_desc'] ?? 'Active living labs in Bogura, Khulna & Rajshahi.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">2. Procurement / Off-Take Metric</label>
                        <input type="text" name="offering_stat2_num" value="<?= htmlspecialchars($settings['offering_stat2_num'] ?? '48+ Off-Take Channels') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="offering_stat2_desc" value="<?= htmlspecialchars($settings['offering_stat2_desc'] ?? 'Direct procurement bridges with leading agribusinesses.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">3. Syndicated Capital Metric</label>
                        <input type="text" name="offering_stat3_num" value="<?= htmlspecialchars($settings['offering_stat3_num'] ?? '$28.5M+ Syndicated Capital') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="offering_stat3_desc" value="<?= htmlspecialchars($settings['offering_stat3_desc'] ?? 'Pre-seed, angel syndicate & international VC co-investment.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-xs font-bold text-slate-800">4. Regulatory Sandbox Metric</label>
                        <input type="text" name="offering_stat4_num" value="<?= htmlspecialchars($settings['offering_stat4_num'] ?? '14-Day Regulatory Sandbox') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="offering_stat4_desc" value="<?= htmlspecialchars($settings['offering_stat4_desc'] ?? 'Streamlined DAE, seed certification, and bio-input clearance.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                    </div>
                </div>
            </div>

        </div>

        <!-- ======================================================== -->
        <!-- TAB 4: NETWORKING PAGE (Hero Banner & Quick Stats) -->
        <!-- ======================================================== -->
        <div id="tab-networking" class="cms-tab-pane <?= $activeTab === 'networking' ? '' : 'hidden' ?> space-y-6">
            
            <!-- Hero Header -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Networking Page Hero Banner</h2>
                        <p class="text-[11px] text-slate-500">Displayed at the top of networking.php. Note: Individual partner cards are managed via <a href="networking.php" class="text-emerald-700 font-bold underline">Network & Partners Manager</a>.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Tag</label>
                        <input type="text" name="networking_badge" value="<?= htmlspecialchars($settings['networking_badge'] ?? 'Connecting Entrepreneur Ecosystem Builders') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline Part 1</label>
                            <input type="text" name="networking_title_main" value="<?= htmlspecialchars($settings['networking_title_main'] ?? 'A Connected Web of') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Headline Part 2 (Gradient Highlight)</label>
                            <input type="text" name="networking_title_highlight" value="<?= htmlspecialchars($settings['networking_title_highlight'] ?? 'Founders, Investors & Scientists') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtext Description</label>
                        <textarea name="networking_desc" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['networking_desc'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 4 Quick Stats Bar -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Global Network 4-Card Quick Stats Bar</h2>
                        <p class="text-[11px] text-slate-500">The 4 metric cards displayed under the hero on networking.php.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-[11px] font-bold text-slate-800">Stat 1: Members</label>
                        <input type="text" name="network_stat1_num" value="<?= htmlspecialchars($settings['network_stat1_num'] ?? '5,200+') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="network_stat1_label" value="<?= htmlspecialchars($settings['network_stat1_label'] ?? 'Network Members') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700">
                        <input type="text" name="network_stat1_desc" value="<?= htmlspecialchars($settings['network_stat1_desc'] ?? 'Across all 64 agricultural districts') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-[11px] text-slate-500">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-[11px] font-bold text-slate-800">Stat 2: Ventures</label>
                        <input type="text" name="network_stat2_num" value="<?= htmlspecialchars($settings['network_stat2_num'] ?? '128+') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="network_stat2_label" value="<?= htmlspecialchars($settings['network_stat2_label'] ?? 'Ventures Accelerated') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700">
                        <input type="text" name="network_stat2_desc" value="<?= htmlspecialchars($settings['network_stat2_desc'] ?? 'High-impact AgriTech solutions') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-[11px] text-slate-500">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-[11px] font-bold text-slate-800">Stat 3: Partners</label>
                        <input type="text" name="network_stat3_num" value="<?= htmlspecialchars($settings['network_stat3_num'] ?? '48+') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="network_stat3_label" value="<?= htmlspecialchars($settings['network_stat3_label'] ?? 'Institutional Partners') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700">
                        <input type="text" name="network_stat3_desc" value="<?= htmlspecialchars($settings['network_stat3_desc'] ?? 'Universities, corporates & donors') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-[11px] text-slate-500">
                    </div>

                    <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                        <label class="block text-[11px] font-bold text-slate-800">Stat 4: Corridors</label>
                        <input type="text" name="network_stat4_num" value="<?= htmlspecialchars($settings['network_stat4_num'] ?? '18') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="network_stat4_label" value="<?= htmlspecialchars($settings['network_stat4_label'] ?? 'Global Corridors') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-700">
                        <input type="text" name="network_stat4_desc" value="<?= htmlspecialchars($settings['network_stat4_desc'] ?? 'South Asia, Netherlands & Africa') ?>" class="w-full px-2.5 py-1 bg-white border border-slate-300 rounded text-[11px] text-slate-500">
                    </div>
                </div>
            </div>

        </div>

        <!-- Sticky Submit Button Bar -->
        <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-md p-4 rounded-xl border border-slate-300 shadow-lg flex items-center justify-between">
            <span class="text-xs text-slate-500">
                <i class="fa-solid fa-cloud-arrow-up mr-1 text-emerald-600"></i> Saves directly to Database & Live Website
            </span>
            <div class="flex items-center space-x-3">
                <a href="index.php" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition border border-slate-300">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save & Publish Changes
                </button>
            </div>
        </div>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.cms-tab-btn');
    const tabPanes = document.querySelectorAll('.cms-tab-pane');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-tab-target');

            // Reset buttons
            tabButtons.forEach(b => {
                b.classList.remove('bg-emerald-700', 'text-white', 'border-emerald-700', 'shadow-sm');
                b.classList.add('bg-white', 'text-slate-700', 'border-slate-300');
            });

            // Activate clicked button
            this.classList.remove('bg-white', 'text-slate-700', 'border-slate-300');
            this.classList.add('bg-emerald-700', 'text-white', 'border-emerald-700', 'shadow-sm');

            // Toggle panes
            tabPanes.forEach(pane => {
                if (pane.id === target) {
                    pane.classList.remove('hidden');
                } else {
                    pane.classList.add('hidden');
                }
            });
        });
    });

    document.querySelectorAll('.alert-close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const box = this.closest('.alert-box');
            if (box) box.style.display = 'none';
        });
    });
});
</script>

<?php renderAdminFooter(); ?>
