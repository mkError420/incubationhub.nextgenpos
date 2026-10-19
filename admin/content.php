<?php
/**
 * NextGen Farming Incubation Hub - Dynamic Site Content CMS
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';

// Handle form submission to update settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_content') {
    $allowedFields = [
        'hero_tagline', 'hero_title_main', 'hero_title_highlight', 'hero_subtitle', 'hero_description',
        'hero_banner_text', 'hero_banner_badge', 'hero_banner_link',
        'metric_members', 'metric_members_suffix', 'metric_members_desc',
        'metric_ventures', 'metric_ventures_suffix', 'metric_ventures_desc',
        'metric_capital', 'metric_organizers', 'metric_organizers_suffix', 'metric_organizers_desc',
        'contact_email', 'contact_phone', 'contact_address', 'admissions_email', 'investors_email'
    ];

    $updates = [];
    foreach ($allowedFields as $f) {
        if (isset($_POST[$f])) {
            $updates[$f] = trim($_POST[$f]);
        }
    }

    if (updateSiteSettings($updates)) {
        $successMsg = 'Website content settings successfully updated and published live!';
    } else {
        $errorMsg = 'Could not save some settings. Please check database connectivity.';
    }
}

$settings = getAllSiteSettings();
renderAdminHeader('content', 'Dynamic Website Content CMS');
?>

<div class="max-w-5xl">
    
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Website Content & Track Record CMS</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
            Update hero headlines, live cohort announcement banners, track record metrics, and contact info. All modifications take effect immediately across the public website.
        </p>
    </div>

    <?php if ($successMsg): ?>
        <div class="alert-box mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between">
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
        <div class="alert-box mb-6 p-4 rounded-2xl bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <span class="font-bold"><?= htmlspecialchars($errorMsg) ?></span>
            </div>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <form method="POST" action="content.php" class="space-y-8">
        <input type="hidden" name="action" value="save_content">

        <!-- 1. Top Announcement Strip -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-bullhorn"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Top Announcement Banner</h3>
                    <p class="text-xs text-slate-500">Displayed at the very top of every page on the website.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Badge Text</label>
                    <input type="text" name="hero_banner_badge" value="<?= htmlspecialchars($settings['hero_banner_badge']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Announcement Text</label>
                    <input type="text" name="hero_banner_text" value="<?= htmlspecialchars($settings['hero_banner_text']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Banner Click Link Target</label>
                    <input type="text" name="hero_banner_link" value="<?= htmlspecialchars($settings['hero_banner_link']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
            </div>
        </div>

        <!-- 2. Hero Section Content -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Hero Section Content (Homepage)</h3>
                    <p class="text-xs text-slate-500">Core title, subtitle, and impact description.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Title Line 1</label>
                    <input type="text" name="hero_title_main" value="<?= htmlspecialchars($settings['hero_title_main']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Title Highlight (Green Gradient)</label>
                    <input type="text" name="hero_title_highlight" value="<?= htmlspecialchars($settings['hero_title_highlight']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Subtitle</label>
                    <textarea name="hero_subtitle" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700 leading-relaxed"><?= htmlspecialchars($settings['hero_subtitle']) ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Hero Secondary Description</label>
                    <textarea name="hero_description" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700 leading-relaxed"><?= htmlspecialchars($settings['hero_description']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- 3. The Track Record Metrics -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-6">
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">The Track Record (3 Columns)</h3>
                    <p class="text-xs text-slate-500">Live animated numbers and descriptions displayed on the homepage.</p>
                </div>
            </div>

            <!-- Col 1: Community Members -->
            <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-200 space-y-4">
                <span class="text-xs font-extrabold text-emerald-800 uppercase tracking-wider block">Column 1: Community Members</span>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Target Number</label>
                        <input type="number" name="metric_members" value="<?= htmlspecialchars($settings['metric_members']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Suffix (e.g. +)</label>
                        <input type="text" name="metric_members_suffix" value="<?= htmlspecialchars($settings['metric_members_suffix']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description Text</label>
                    <textarea name="metric_members_desc" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs leading-relaxed"><?= htmlspecialchars($settings['metric_members_desc']) ?></textarea>
                </div>
            </div>

            <!-- Col 2: Business Ventures -->
            <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-200 space-y-4">
                <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider block">Column 2: Business Ventures</span>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Startups Count</label>
                        <input type="number" name="metric_ventures" value="<?= htmlspecialchars($settings['metric_ventures']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Suffix</label>
                        <input type="text" name="metric_ventures_suffix" value="<?= htmlspecialchars($settings['metric_ventures_suffix']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Total Capital Raised</label>
                        <input type="text" name="metric_capital" value="<?= htmlspecialchars($settings['metric_capital']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description Text</label>
                    <textarea name="metric_ventures_desc" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs leading-relaxed"><?= htmlspecialchars($settings['metric_ventures_desc']) ?></textarea>
                </div>
            </div>

            <!-- Col 3: Program Organizers -->
            <div class="p-5 rounded-2xl bg-gray-50/70 border border-gray-200 space-y-4">
                <span class="text-xs font-extrabold text-blue-800 uppercase tracking-wider block">Column 3: Program Organizers</span>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Partners Count</label>
                        <input type="number" name="metric_organizers" value="<?= htmlspecialchars($settings['metric_organizers']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Suffix</label>
                        <input type="text" name="metric_organizers_suffix" value="<?= htmlspecialchars($settings['metric_organizers_suffix']) ?>" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Description Text</label>
                    <textarea name="metric_organizers_desc" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs leading-relaxed"><?= htmlspecialchars($settings['metric_organizers_desc']) ?></textarea>
                </div>
            </div>
        </div>

        <!-- 4. Contact & Headquarters Details -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-5">
            <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Hub Contact & Official Coordinates</h3>
                    <p class="text-xs text-slate-500">Contact information rendered in headers, footers, and contact pages.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Public General Email</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone / WhatsApp Number</label>
                    <input type="text" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Startup Admissions Inbox</label>
                    <input type="email" name="admissions_email" value="<?= htmlspecialchars($settings['admissions_email']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Investor Relations Inbox</label>
                    <input type="email" name="investors_email" value="<?= htmlspecialchars($settings['investors_email']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Headquarters Physical Address</label>
                    <input type="text" name="contact_address" value="<?= htmlspecialchars($settings['contact_address']) ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
            </div>
        </div>

        <!-- Sticky Save Action Bar -->
        <div class="sticky bottom-6 z-20 bg-slate-950/95 backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-slate-800 flex items-center justify-between">
            <span class="text-xs text-slate-400 hidden sm:inline">
                <i class="fa-solid fa-clock-rotate-left mr-1.5 text-emerald-400"></i> All edits reflect instantly on the public website.
            </span>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-agro-600 to-agro-500 hover:from-agro-500 hover:to-agro-400 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-agro-600/30 transition flex items-center justify-center space-x-2">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Save & Publish Changes</span>
            </button>
        </div>

    </form>

</div>

<?php renderAdminFooter(); ?>
