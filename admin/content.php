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
        'offerings_matrix_badge', 'offerings_matrix_title', 'offerings_matrix_subtitle',
        'offerings_matrix_col0', 'offerings_matrix_col1', 'offerings_matrix_col2', 'offerings_matrix_col3', 'offerings_matrix_col4',
        'offering_faq1_q', 'offering_faq1_a',
        'offering_faq2_q', 'offering_faq2_a',
        'offering_faq3_q', 'offering_faq3_a',

        // Networking Page Content
        'networking_badge', 'networking_title_main', 'networking_title_highlight', 'networking_desc',
        'network_stat1_num', 'network_stat1_label', 'network_stat1_desc',
        'network_stat2_num', 'network_stat2_label', 'network_stat2_desc',
        'network_stat3_num', 'network_stat3_label', 'network_stat3_desc',
        'network_stat4_num', 'network_stat4_label', 'network_stat4_desc',

        // About Us Page Content
        'about_hero_badge', 'about_hero_title_main', 'about_hero_title_highlight', 'about_hero_desc',
        'about_context_badge', 'about_context_title', 'about_context_p1', 'about_context_p2',
        'about_bottle1_title', 'about_bottle1_desc', 'about_bottle1_icon',
        'about_bottle2_title', 'about_bottle2_desc', 'about_bottle2_icon',
        'about_bottle3_title', 'about_bottle3_desc', 'about_bottle3_icon',
        'about_bottle4_title', 'about_bottle4_desc', 'about_bottle4_icon',
        'about_flywheel_badge', 'about_flywheel_title',
        'about_flywheel1_title', 'about_flywheel1_desc',
        'about_flywheel2_title', 'about_flywheel2_desc',
        'about_flywheel3_title', 'about_flywheel3_desc',
        'about_flywheel4_title', 'about_flywheel4_desc',
        'about_pillars_badge', 'about_pillars_title', 'about_pillars_subtitle',
        'about_pillar1_title', 'about_pillar1_desc', 'about_pillar1_icon',
        'about_pillar2_title', 'about_pillar2_desc', 'about_pillar2_icon',
        'about_pillar3_title', 'about_pillar3_desc', 'about_pillar3_icon',
        'about_pillar4_title', 'about_pillar4_desc', 'about_pillar4_icon',
        'about_team_badge', 'about_team_title', 'about_team_subtitle',
        'about_leader1_name', 'about_leader1_role', 'about_leader1_initials', 'about_leader1_bio',
        'about_leader2_name', 'about_leader2_role', 'about_leader2_initials', 'about_leader2_bio',
        'about_leader3_name', 'about_leader3_role', 'about_leader3_initials', 'about_leader3_bio',
        'about_leader4_name', 'about_leader4_role', 'about_leader4_initials', 'about_leader4_bio',
    ];

    $updates = [];
    foreach ($allowedFields as $f) {
        if (isset($_POST[$f])) {
            $updates[$f] = trim($_POST[$f]);
        }
    }

    // Process dynamic Stakeholder Capability Matrix rows
    if (isset($_POST['matrix_rows']) && is_array($_POST['matrix_rows'])) {
        $cleanMatrixRows = [];
        foreach ($_POST['matrix_rows'] as $r) {
            $title = trim($r['title'] ?? '');
            if ($title === '') continue;
            $cleanMatrixRows[] = [
                'title'      => $title,
                'icon'       => trim($r['icon'] ?? 'fa-solid fa-cube text-agro-600'),
                'col1'       => trim($r['col1'] ?? ''),
                'col1_check' => !empty($r['col1_check']) ? 1 : 0,
                'col2'       => trim($r['col2'] ?? ''),
                'col2_check' => !empty($r['col2_check']) ? 1 : 0,
                'col3'       => trim($r['col3'] ?? ''),
                'col3_check' => !empty($r['col3_check']) ? 1 : 0,
                'col4'       => trim($r['col4'] ?? ''),
                'col4_check' => !empty($r['col4_check']) ? 1 : 0,
            ];
        }
        $updates['offerings_matrix_json'] = json_encode($cleanMatrixRows, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
            <a href="../about.php" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                About Us
            </a>
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
        <button type="button" data-tab-target="tab-about" class="cms-tab-btn px-4 py-2 rounded-lg font-bold border transition <?= $activeTab === 'about' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?>">
            <i class="fa-solid fa-compass mr-1.5"></i> About Us Page
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

            <!-- 3. Stakeholder Capability Matrix Section -->
            <div id="matrix-cms" class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-3">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-table-list"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Stakeholder Capability Matrix</h2>
                            <p class="text-[11px] text-slate-500">Maintain the cross-stage resource matrix table displayed on offerings.php.</p>
                        </div>
                    </div>
                    <a href="../offerings.php#matrix" target="_blank" class="inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-900">
                        <i class="fa-solid fa-arrow-up-right-from-square mr-1 text-[11px]"></i> Preview Matrix on Live Page
                    </a>
                </div>

                <!-- Section Header Settings -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Section Badge Tag</label>
                        <input type="text" name="offerings_matrix_badge" value="<?= htmlspecialchars($settings['offerings_matrix_badge'] ?? 'Tailored For Every Stage') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Section Main Headline</label>
                        <input type="text" name="offerings_matrix_title" value="<?= htmlspecialchars($settings['offerings_matrix_title'] ?? 'Stakeholder Capability Matrix') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Subtitle Description</label>
                        <input type="text" name="offerings_matrix_subtitle" value="<?= htmlspecialchars($settings['offerings_matrix_subtitle'] ?? 'How founders, research spin-offs, corporate partners, and institutional investors engage with NextGen Farming Hub’s core infrastructure.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                    </div>
                </div>

                <!-- Column Headers Configuration -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Matrix Column Headers</label>
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-500 mb-1">Column 1 (Capabilities)</span>
                            <input type="text" name="offerings_matrix_col0" value="<?= htmlspecialchars($settings['offerings_matrix_col0'] ?? 'Capabilities & Resources') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-900">
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-emerald-600 mb-1">Column 2 (Pre-Seed)</span>
                            <input type="text" name="offerings_matrix_col1" value="<?= htmlspecialchars($settings['offerings_matrix_col1'] ?? 'Pre-Seed Startups') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-900">
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-amber-600 mb-1">Column 3 (Growth)</span>
                            <input type="text" name="offerings_matrix_col2" value="<?= htmlspecialchars($settings['offerings_matrix_col2'] ?? 'Scaling Growth Ventures') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-900">
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-sky-600 mb-1">Column 4 (Academic)</span>
                            <input type="text" name="offerings_matrix_col3" value="<?= htmlspecialchars($settings['offerings_matrix_col3'] ?? 'Academic / Science Spin-offs') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-900">
                        </div>
                        <div>
                            <span class="block text-[11px] font-semibold text-slate-600 mb-1">Column 5 (Corporate)</span>
                            <input type="text" name="offerings_matrix_col4" value="<?= htmlspecialchars($settings['offerings_matrix_col4'] ?? 'Corporate Agribusiness') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-slate-900">
                        </div>
                    </div>
                </div>

                <!-- Matrix Rows Container -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Matrix Rows & Capability Allocations</label>
                        <button type="button" id="add-matrix-row-btn" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300 transition">
                            <i class="fa-solid fa-plus mr-1"></i> Add Matrix Row
                        </button>
                    </div>

                    <div id="matrix-rows-list" class="space-y-4">
                        <?php 
                        $curMatrixRows = getOfferingsMatrixRows();
                        foreach ($curMatrixRows as $idx => $mRow): 
                        ?>
                        <div class="matrix-row-item p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                                <span class="text-xs font-bold text-slate-800 flex items-center">
                                    <i class="fa-solid fa-grip-lines text-slate-400 mr-2 cursor-grab"></i>
                                    Row #<span class="matrix-row-num"><?= $idx + 1 ?></span>
                                </span>
                                <button type="button" class="remove-matrix-row-btn text-xs text-red-600 hover:text-red-800 font-semibold flex items-center">
                                    <i class="fa-solid fa-trash-can mr-1"></i> Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">Capability / Resource Title</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][title]" value="<?= htmlspecialchars($mRow['title'] ?? '') ?>" placeholder="e.g. Farmland Living Labs Access" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-700 mb-1">FontAwesome Icon Class</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][icon]" value="<?= htmlspecialchars($mRow['icon'] ?? 'fa-solid fa-cube text-agro-600') ?>" placeholder="fa-solid fa-tractor text-agro-600" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-mono text-slate-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                                <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                                    <label class="block text-[11px] font-semibold text-emerald-700">Col 1 Value (Pre-Seed)</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][col1]" value="<?= htmlspecialchars($mRow['col1'] ?? '') ?>" placeholder="Up to 5 Acres" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                                    <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                                        <input type="checkbox" name="matrix_rows[<?= $idx ?>][col1_check]" value="1" <?= !empty($mRow['col1_check']) ? 'checked' : '' ?> class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                                        Green Check & Bold
                                    </label>
                                </div>

                                <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                                    <label class="block text-[11px] font-semibold text-amber-700">Col 2 Value (Scaling)</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][col2]" value="<?= htmlspecialchars($mRow['col2'] ?? '') ?>" placeholder="25+ Commercial Acres" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                                    <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                                        <input type="checkbox" name="matrix_rows[<?= $idx ?>][col2_check]" value="1" <?= !empty($mRow['col2_check']) ? 'checked' : '' ?> class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                                        Green Check & Bold
                                    </label>
                                </div>

                                <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                                    <label class="block text-[11px] font-semibold text-sky-700">Col 3 Value (Academic)</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][col3]" value="<?= htmlspecialchars($mRow['col3'] ?? '') ?>" placeholder="Dedicated Trial Plots" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                                    <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                                        <input type="checkbox" name="matrix_rows[<?= $idx ?>][col3_check]" value="1" <?= !empty($mRow['col3_check']) ? 'checked' : '' ?> class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                                        Green Check & Bold
                                    </label>
                                </div>

                                <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                                    <label class="block text-[11px] font-semibold text-slate-700">Col 4 Value (Corporate)</label>
                                    <input type="text" name="matrix_rows[<?= $idx ?>][col4]" value="<?= htmlspecialchars($mRow['col4'] ?? '') ?>" placeholder="Bespoke Contract Plots" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                                    <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                                        <input type="checkbox" name="matrix_rows[<?= $idx ?>][col4_check]" value="1" <?= !empty($mRow['col4_check']) ? 'checked' : '' ?> class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                                        Green Check & Bold
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- 4. Offerings & Infrastructure FAQs -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-circle-question"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Offerings & Infrastructure FAQs</h2>
                        <p class="text-[11px] text-slate-500">Displayed in the FAQ section at the bottom of offerings.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php for ($f = 1; $f <= 3; $f++): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-slate-800">Offerings FAQ #<?= $f ?></span>
                            <input type="text" name="offering_faq<?= $f ?>_q" value="<?= htmlspecialchars($settings["offering_faq{$f}_q"] ?? '') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <label class="block text-[11px] font-semibold text-slate-600">Answer Explanation</label>
                            <textarea name="offering_faq<?= $f ?>_a" rows="3" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700 leading-relaxed"><?= htmlspecialchars($settings["offering_faq{$f}_a"] ?? '') ?></textarea>
                        </div>
                    <?php endfor; ?>
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

        <!-- ======================================================== -->
        <!-- TAB 5: ABOUT US PAGE -->
        <!-- ======================================================== -->
        <div id="tab-about" class="cms-tab-pane <?= $activeTab === 'about' ? '' : 'hidden' ?> space-y-6">
            
            <!-- 1. Hero Banner -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">About Us Page Hero Banner</h2>
                        <p class="text-[11px] text-slate-500">Banner displayed at the top of about.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Badge Tag</label>
                        <input type="text" name="about_hero_badge" value="<?= htmlspecialchars($settings['about_hero_badge'] ?? 'Our Mission & Purpose') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline Part 1</label>
                            <input type="text" name="about_hero_title_main" value="<?= htmlspecialchars($settings['about_hero_title_main'] ?? 'Catalyzing Frontier AgriTech Across') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Headline Part 2 (Gradient Highlight)</label>
                            <input type="text" name="about_hero_title_highlight" value="<?= htmlspecialchars($settings['about_hero_title_highlight'] ?? 'Bangladesh & Emerging Markets') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtext Description</label>
                        <textarea name="about_hero_desc" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['about_hero_desc'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- 2. The Emerging Market Context Section -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-agro-50 text-agro-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-earth-asia"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Market Context & Agricultural Sandbox</h2>
                        <p class="text-[11px] text-slate-500">The deltaic promise description and core agricultural bottlenecks we solve.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Context Badge</label>
                            <input type="text" name="about_context_badge" value="<?= htmlspecialchars($settings['about_context_badge'] ?? 'The Problem & The Deltaic Promise') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Main Heading</label>
                            <input type="text" name="about_context_title" value="<?= htmlspecialchars($settings['about_context_title'] ?? 'Why Bangladesh is the World’s Most Vital Agricultural Sandbox') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Narrative Paragraph 1</label>
                        <textarea name="about_context_p1" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings['about_context_p1'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Narrative Paragraph 2</label>
                        <textarea name="about_context_p2" rows="2" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings['about_context_p2'] ?? '') ?></textarea>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">4 Key Bottlenecks We Solve</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php for ($b = 1; $b <= 4; $b++): ?>
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                                <span class="text-[11px] font-bold text-slate-800">Bottleneck #<?= $b ?></span>
                                <div class="grid grid-cols-3 gap-2">
                                    <div class="col-span-2">
                                        <input type="text" name="about_bottle<?= $b ?>_title" value="<?= htmlspecialchars($settings["about_bottle{$b}_title"] ?? '') ?>" placeholder="Title" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                                    </div>
                                    <div>
                                        <input type="text" name="about_bottle<?= $b ?>_icon" value="<?= htmlspecialchars($settings["about_bottle{$b}_icon"] ?? 'fa-circle') ?>" placeholder="FontAwesome" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs font-mono text-slate-700">
                                    </div>
                                </div>
                                <textarea name="about_bottle<?= $b ?>_desc" rows="2" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-[11px] text-slate-700"><?= htmlspecialchars($settings["about_bottle{$b}_desc"] ?? '') ?></textarea>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. The NextGen Incubation Flywheel -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-arrows-spin"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">The NextGen Incubation Flywheel</h2>
                        <p class="text-[11px] text-slate-500">The 4-stage operating architecture card on about.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Flywheel Badge</label>
                            <input type="text" name="about_flywheel_badge" value="<?= htmlspecialchars($settings['about_flywheel_badge'] ?? 'Our Operating Architecture') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Flywheel Title</label>
                            <input type="text" name="about_flywheel_title" value="<?= htmlspecialchars($settings['about_flywheel_title'] ?? 'The NextGen Incubation Flywheel') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <?php for ($fw = 1; $fw <= 4; $fw++): ?>
                        <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded inline-block">Flywheel Stage <?= $fw ?></span>
                            <input type="text" name="about_flywheel<?= $fw ?>_title" value="<?= htmlspecialchars($settings["about_flywheel{$fw}_title"] ?? '') ?>" placeholder="Stage Title" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                            <textarea name="about_flywheel<?= $fw ?>_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings["about_flywheel{$fw}_desc"] ?? '') ?></textarea>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- 4. Four Strategic Pillars -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-shapes"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Four Strategic Pillars</h2>
                        <p class="text-[11px] text-slate-500">Core foundations that engineer agricultural transformation.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pillars Badge</label>
                            <input type="text" name="about_pillars_badge" value="<?= htmlspecialchars($settings['about_pillars_badge'] ?? 'Core Foundations') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Headline</label>
                            <input type="text" name="about_pillars_title" value="<?= htmlspecialchars($settings['about_pillars_title'] ?? 'Our Four Strategic Pillars') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Subtitle</label>
                            <input type="text" name="about_pillars_subtitle" value="<?= htmlspecialchars($settings['about_pillars_subtitle'] ?? 'How we engineer lasting agricultural transformation through discipline and science.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <?php for ($p = 1; $p <= 4; $p++): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-slate-800">Pillar #<?= $p ?></span>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-2">
                                    <input type="text" name="about_pillar<?= $p ?>_title" value="<?= htmlspecialchars($settings["about_pillar{$p}_title"] ?? '') ?>" placeholder="Title" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                                </div>
                                <div>
                                    <input type="text" name="about_pillar<?= $p ?>_icon" value="<?= htmlspecialchars($settings["about_pillar{$p}_icon"] ?? 'fa-cube') ?>" placeholder="FontAwesome Icon" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-mono text-slate-700">
                                </div>
                            </div>
                            <textarea name="about_pillar<?= $p ?>_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings["about_pillar{$p}_desc"] ?? '') ?></textarea>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- 5. Leadership & Advisory Board -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Leadership & Advisory Board</h2>
                        <p class="text-[11px] text-slate-500">Governance, executive directors, scientists, and advisors on about.php.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Section Badge</label>
                            <input type="text" name="about_team_badge" value="<?= htmlspecialchars($settings['about_team_badge'] ?? 'Governance & Operators') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Section Headline</label>
                            <input type="text" name="about_team_title" value="<?= htmlspecialchars($settings['about_team_title'] ?? 'Leadership & Advisory Board') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Subtitle</label>
                            <input type="text" name="about_team_subtitle" value="<?= htmlspecialchars($settings['about_team_subtitle'] ?? 'Combining decades of venture capital, ag-science, and rural market scaling.') ?>" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <?php for ($l = 1; $l <= 4; $l++): ?>
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                            <span class="text-xs font-bold text-slate-800">Leader / Advisor #<?= $l ?></span>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="col-span-2">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Full Name</label>
                                    <input type="text" name="about_leader<?= $l ?>_name" value="<?= htmlspecialchars($settings["about_leader{$l}_name"] ?? '') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Initials</label>
                                    <input type="text" name="about_leader<?= $l ?>_initials" value="<?= htmlspecialchars($settings["about_leader{$l}_initials"] ?? '') ?>" placeholder="RA" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-center text-slate-900">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Role / Designation</label>
                                <input type="text" name="about_leader<?= $l ?>_role" value="<?= htmlspecialchars($settings["about_leader{$l}_role"] ?? '') ?>" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs font-semibold text-agro-700">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Bio / Track Record</label>
                                <textarea name="about_leader<?= $l ?>_bio" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-xs text-slate-700"><?= htmlspecialchars($settings["about_leader{$l}_bio"] ?? '') ?></textarea>
                            </div>
                        </div>
                        <?php endfor; ?>
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

    // Dynamic Matrix Rows Add & Remove
    const matrixList = document.getElementById('matrix-rows-list');
    const addMatrixRowBtn = document.getElementById('add-matrix-row-btn');

    if (addMatrixRowBtn && matrixList) {
        addMatrixRowBtn.addEventListener('click', function() {
            const rowCount = matrixList.querySelectorAll('.matrix-row-item').length;
            const newIndex = Date.now();
            const newRowNum = rowCount + 1;

            const div = document.createElement('div');
            div.className = 'matrix-row-item p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-3';
            div.innerHTML = `
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <span class="text-xs font-bold text-slate-800 flex items-center">
                        <i class="fa-solid fa-grip-lines text-slate-400 mr-2 cursor-grab"></i>
                        Row #<span class="matrix-row-num">${newRowNum}</span>
                    </span>
                    <button type="button" class="remove-matrix-row-btn text-xs text-red-600 hover:text-red-800 font-semibold flex items-center">
                        <i class="fa-solid fa-trash-can mr-1"></i> Remove
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Capability / Resource Title</label>
                        <input type="text" name="matrix_rows[${newIndex}][title]" value="" placeholder="e.g. Cold Chain Storage" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">FontAwesome Icon Class</label>
                        <input type="text" name="matrix_rows[${newIndex}][icon]" value="fa-solid fa-cube text-agro-600" placeholder="fa-solid fa-tractor text-agro-600" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-mono text-slate-700">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-2">
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                        <label class="block text-[11px] font-semibold text-emerald-700">Col 1 Value (Pre-Seed)</label>
                        <input type="text" name="matrix_rows[${newIndex}][col1]" value="" placeholder="Feature details" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                            <input type="checkbox" name="matrix_rows[${newIndex}][col1_check]" value="1" class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                            Green Check & Bold
                        </label>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                        <label class="block text-[11px] font-semibold text-amber-700">Col 2 Value (Scaling)</label>
                        <input type="text" name="matrix_rows[${newIndex}][col2]" value="" placeholder="Feature details" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                            <input type="checkbox" name="matrix_rows[${newIndex}][col2_check]" value="1" class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                            Green Check & Bold
                        </label>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                        <label class="block text-[11px] font-semibold text-sky-700">Col 3 Value (Academic)</label>
                        <input type="text" name="matrix_rows[${newIndex}][col3]" value="" placeholder="Feature details" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                            <input type="checkbox" name="matrix_rows[${newIndex}][col3_check]" value="1" class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                            Green Check & Bold
                        </label>
                    </div>
                    <div class="p-2.5 bg-white rounded-lg border border-slate-200 space-y-1.5">
                        <label class="block text-[11px] font-semibold text-slate-700">Col 4 Value (Corporate)</label>
                        <input type="text" name="matrix_rows[${newIndex}][col4]" value="" placeholder="Feature details" class="w-full px-2 py-1 bg-white border border-slate-300 rounded text-xs text-slate-900">
                        <label class="inline-flex items-center text-[10px] text-slate-600 font-medium cursor-pointer">
                            <input type="checkbox" name="matrix_rows[${newIndex}][col4_check]" value="1" class="mr-1.5 rounded text-emerald-600 focus:ring-0">
                            Green Check & Bold
                        </label>
                    </div>
                </div>
            `;
            matrixList.appendChild(div);
        });

        matrixList.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-matrix-row-btn');
            if (btn) {
                const item = btn.closest('.matrix-row-item');
                if (item && confirm('Are you sure you want to remove this capability row from the matrix?')) {
                    item.remove();
                    matrixList.querySelectorAll('.matrix-row-item').forEach((row, i) => {
                        const numSpan = row.querySelector('.matrix-row-num');
                        if (numSpan) numSpan.textContent = i + 1;
                    });
                }
            }
        });
    }
});
</script>

<?php renderAdminFooter(); ?>
