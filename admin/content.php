<?php
/**
 * NextGen Farming Incubation Hub - Site Content CMS (Normal & Classic Style)
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
        $successMsg = 'Website content and metrics successfully updated and published live!';
    } else {
        $errorMsg = 'Could not save some settings. Please verify database connectivity.';
    }
}

$settings = getAllSiteSettings();
renderAdminHeader('content', 'Website Content CMS', 'Content CMS');
?>

<div class="max-w-5xl">
    
    <!-- Page Title & Explainer -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Website Content & Track Record CMS</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage live website announcements, hero section headlines, impact metrics, and contact info.</p>
        </div>
        <a href="../index.php" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition shrink-0">
            <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View Live Site
        </a>
    </div>

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

    <form method="POST" action="content.php" class="space-y-6">
        <input type="hidden" name="action" value="save_content">

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
                    <input type="text" name="hero_banner_badge" value="<?= htmlspecialchars($settings['hero_banner_badge']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    <span class="text-[10px] text-slate-400 mt-1 block">e.g. "New Call", "Cohort 04"</span>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Announcement Message</label>
                    <input type="text" name="hero_banner_text" value="<?= htmlspecialchars($settings['hero_banner_text']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    <span class="text-[10px] text-slate-400 mt-1 block">Main notification text</span>
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Banner Link Target</label>
                    <input type="text" name="hero_banner_link" value="<?= htmlspecialchars($settings['hero_banner_link']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-mono text-slate-900 focus:outline-none focus:border-emerald-600">
                    <span class="text-[10px] text-slate-400 mt-1 block">Relative URL or target (e.g. contact.php?category=Entrepreneur)</span>
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
                    <input type="text" name="hero_tagline" value="<?= htmlspecialchars($settings['hero_tagline']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Main Headline Part 1</label>
                        <input type="text" name="hero_title_main" value="<?= htmlspecialchars($settings['hero_title_main']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        <span class="text-[10px] text-slate-400 mt-1 block">Primary text: e.g. "NextGen Farming"</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Headline Part 2 (Green Highlight)</label>
                        <input type="text" name="hero_title_highlight" value="<?= htmlspecialchars($settings['hero_title_highlight']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                        <span class="text-[10px] text-slate-400 mt-1 block">Highlighted text: e.g. "Incubation Hub"</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" value="<?= htmlspecialchars($settings['hero_subtitle']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                    <span class="text-[10px] text-slate-400 mt-1 block">"Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets."</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Full Value Proposition Description</label>
                    <textarea name="hero_description" rows="3" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($settings['hero_description']) ?></textarea>
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
                
                <!-- Metric 1: Members -->
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                    <strong class="text-xs text-slate-800 block">1. Ecosystem Members</strong>
                    <div class="flex space-x-2">
                        <input type="text" name="metric_members" value="<?= htmlspecialchars($settings['metric_members']) ?>" placeholder="5200" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="metric_members_suffix" value="<?= htmlspecialchars($settings['metric_members_suffix']) ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    </div>
                    <textarea name="metric_members_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_members_desc']) ?></textarea>
                </div>

                <!-- Metric 2: Ventures -->
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                    <strong class="text-xs text-slate-800 block">2. Ventures Accelerated</strong>
                    <div class="flex space-x-2">
                        <input type="text" name="metric_ventures" value="<?= htmlspecialchars($settings['metric_ventures']) ?>" placeholder="128" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="metric_ventures_suffix" value="<?= htmlspecialchars($settings['metric_ventures_suffix']) ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    </div>
                    <textarea name="metric_ventures_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_ventures_desc']) ?></textarea>
                </div>

                <!-- Metric 3: Capital Raised -->
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                    <strong class="text-xs text-slate-800 block">3. Capital Mobilized</strong>
                    <input type="text" name="metric_capital" value="<?= htmlspecialchars($settings['metric_capital']) ?>" placeholder="$28.5M+" class="w-full px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    <span class="text-[10px] text-slate-500">Cumulative funding raised by cohort startups</span>
                </div>

                <!-- Metric 4: Organizers -->
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                    <strong class="text-xs text-slate-800 block">4. Partner Institutions</strong>
                    <div class="flex space-x-2">
                        <input type="text" name="metric_organizers" value="<?= htmlspecialchars($settings['metric_organizers']) ?>" placeholder="48" class="w-2/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                        <input type="text" name="metric_organizers_suffix" value="<?= htmlspecialchars($settings['metric_organizers_suffix']) ?>" placeholder="+" class="w-1/3 px-3 py-1.5 bg-white border border-slate-300 rounded text-xs font-bold text-slate-900">
                    </div>
                    <textarea name="metric_organizers_desc" rows="2" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded text-[11px] text-slate-700 leading-relaxed"><?= htmlspecialchars($settings['metric_organizers_desc']) ?></textarea>
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
                    <input type="text" name="contact_address" value="<?= htmlspecialchars($settings['contact_address']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Telephone / Hotline</label>
                    <input type="text" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">General Inquiries Email</label>
                    <input type="email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Startup Admissions Email</label>
                    <input type="email" name="admissions_email" value="<?= htmlspecialchars($settings['admissions_email']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Investor Relations Email</label>
                    <input type="email" name="investors_email" value="<?= htmlspecialchars($settings['investors_email']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>
            </div>
        </div>

        <!-- Submit Button Bar -->
        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="index.php" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-lg transition border border-slate-300">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow-sm transition flex items-center">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Save & Publish Changes
            </button>
        </div>

    </form>

</div>

<?php renderAdminFooter(); ?>
