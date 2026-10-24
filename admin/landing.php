<?php
/**
 * NextGen Farming Incubation Hub - Landing Page & Dynamic Banner Carousel CMS
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';
$editingBanner = null;

// Handle Delete Banner
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delId = (int)$_GET['id'];
    if (deleteLandingBanner($delId)) {
        header("Location: landing.php?deleted=1");
        exit;
    } else {
        $errorMsg = 'Could not delete banner slide.';
    }
}

// Handle Toggle Banner Status
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $toggleId = (int)$_GET['id'];
    if (toggleLandingBannerStatus($toggleId)) {
        header("Location: landing.php?toggled=1");
        exit;
    } else {
        $errorMsg = 'Could not update banner status.';
    }
}

// Handle Reset Default Banners
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reset_defaults') {
    if (resetDefaultLandingBanners()) {
        header("Location: landing.php?reset_done=1");
        exit;
    } else {
        $errorMsg = 'Could not restore default banners.';
    }
}

// Handle Update Landing Settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_landing_settings') {
    $siteName = trim($_POST['landing_site_name'] ?? 'NextGen IncubationHub');
    $subtitle = trim($_POST['landing_subtitle'] ?? '');
    $carouselEnabled = isset($_POST['landing_carousel_enabled']) && $_POST['landing_carousel_enabled'] === '1' ? '1' : '0';
    $carouselAutoplay = (int)($_POST['landing_carousel_autoplay'] ?? 5000);
    $gateEnabled = isset($_POST['landing_gate_enabled']) && $_POST['landing_gate_enabled'] === '1' ? '1' : '0';

    if (empty($siteName)) {
        $siteName = 'NextGen IncubationHub';
    }

    $ok1 = updateSiteSetting('landing_site_name', $siteName, 'landing');
    $ok2 = updateSiteSetting('landing_subtitle', $subtitle, 'landing');
    $ok3 = updateSiteSetting('landing_carousel_enabled', $carouselEnabled, 'landing');
    $ok4 = updateSiteSetting('landing_carousel_autoplay', (string)$carouselAutoplay, 'landing');
    $ok5 = updateSiteSetting('landing_gate_enabled', $gateEnabled, 'general');

    if ($ok1 && $ok2 && $ok3 && $ok4 && $ok5) {
        $successMsg = 'Landing Page settings and banner carousel configuration saved successfully!';
    } else {
        $errorMsg = 'Some settings could not be saved. Please verify file / database permissions.';
    }
}

// Handle Save Banner (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_banner') {
    $bannerId = !empty($_POST['banner_id']) ? (int)$_POST['banner_id'] : null;
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $badge = trim($_POST['badge'] ?? '');
    $linkText = trim($_POST['link_text'] ?? 'Explore More');
    $linkUrl = trim($_POST['link_url'] ?? '#intake-form-section');
    $sortOrder = (int)($_POST['sort_order'] ?? 1);
    $status = in_array($_POST['status'] ?? '', ['Active', 'Draft']) ? $_POST['status'] : 'Active';

    $existingBanner = $bannerId ? getLandingBannerById($bannerId) : null;
    $imagePath = $existingBanner['image_path'] ?? '';

    // Handle File Upload or Image URL
    if (isset($_FILES['banner_file']) && $_FILES['banner_file']['error'] === UPLOAD_ERR_OK) {
        $uploadRes = processBannerUpload('banner_file', 'banner');
        if ($uploadRes['success']) {
            if (!empty($existingBanner['image_path'])) {
                deleteOldBannerFile($existingBanner['image_path']);
            }
            $imagePath = $uploadRes['path'];
        } else {
            $errorMsg = 'Image upload failed: ' . $uploadRes['error'];
        }
    } elseif (isset($_POST['image_url']) && trim($_POST['image_url']) !== '') {
        $imagePath = trim($_POST['image_url']);
    }

    if (empty($title)) {
        $errorMsg = 'Banner slide title is required.';
    }

    if (empty($errorMsg)) {
        $saveData = [
            'id'         => $bannerId,
            'title'      => $title,
            'subtitle'   => $subtitle,
            'badge'      => $badge,
            'image_path' => $imagePath,
            'link_text'  => $linkText,
            'link_url'   => $linkUrl,
            'sort_order' => $sortOrder,
            'status'     => $status,
        ];

        if (saveLandingBanner($saveData)) {
            header("Location: landing.php?saved=1");
            exit;
        } else {
            $errorMsg = 'Could not save banner slide to system.';
        }
    }
}

// Check if Editing a banner
if (isset($_GET['edit_id'])) {
    $editingBanner = getLandingBannerById((int)$_GET['edit_id']);
}

// Fetch all banners (including drafts)
$banners = getLandingBanners(false);
$landingSiteName = getSiteSetting('landing_site_name', 'NextGen IncubationHub');
$landingSubtitle = getSiteSetting('landing_subtitle', 'Empowering visionary innovators, farmers, and entrepreneurs with living farmland testbeds, venture capital, and commercial scale.');
$carouselEnabled = getSiteSetting('landing_carousel_enabled', '1');
$carouselAutoplay = (int)getSiteSetting('landing_carousel_autoplay', '5000');
$landingGateEnabled = getSiteSetting('landing_gate_enabled', '1');

$activeCount = 0;
foreach ($banners as $b) {
    if (($b['status'] ?? 'Active') === 'Active') $activeCount++;
}

renderAdminHeader('landing', 'Landing Page & Carousel Manager', 'Landing Page');
?>

<div class="max-w-6xl space-y-6">

    <!-- Page Header Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">Front Door & Gate</span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-semibold"><?= count($banners) ?> Banner Slides</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-1">Landing Page & Dynamic Carousel Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Control the top site brand title, manage dynamic banner slides, configure autoplay speeds, and toggle idea submission intake requirements.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="#banner-modal-section" id="btn-add-banner" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Add New Slide
            </a>
            <a href="../landing.php" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition" title="Preview live landing page in new tab">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View Live Landing Page
            </a>
            <a href="../landing.php?reset=1" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold border border-amber-300 transition" title="Clear unlock session and test gate flow">
                <i class="fa-solid fa-rotate-left mr-1.5 text-amber-600"></i> Test Gate
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['saved'])): ?>
        <div class="alert-box p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> Banner slide saved and synchronized successfully!</span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-trash text-red-600 mr-2 text-base"></i> Banner slide has been permanently deleted.</span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['toggled'])): ?>
        <div class="alert-box p-4 rounded-lg bg-blue-50 border border-blue-300 text-blue-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-toggle-on text-blue-600 mr-2 text-base"></i> Banner status toggled successfully!</span>
            <button type="button" class="alert-close-btn text-blue-700 hover:text-blue-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reset_done'])): ?>
        <div class="alert-box p-4 rounded-lg bg-amber-50 border border-amber-300 text-amber-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-rotate text-amber-600 mr-2 text-base"></i> Default high-resolution AgriTech banners restored successfully!</span>
            <button type="button" class="alert-close-btn text-amber-700 hover:text-amber-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($successMsg): ?>
        <div class="alert-box p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> <?= htmlspecialchars($successMsg) ?></span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-exclamation text-red-600 mr-2 text-base"></i> <?= htmlspecialchars($errorMsg) ?></span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- KPI Metric Quick Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Landing Site Name</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block truncate max-w-[180px]"><?= htmlspecialchars($landingSiteName) ?></span>
                <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block">Displayed on TOP</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-heading"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Banner Carousel</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block"><?= $carouselEnabled === '1' ? 'Enabled (Live)' : 'Disabled' ?></span>
                <span class="text-[11px] <?= $carouselEnabled === '1' ? 'text-emerald-600' : 'text-slate-400' ?> font-semibold mt-0.5 block"><?= $activeCount ?> Active Slides</span>
            </div>
            <div class="w-10 h-10 rounded-lg <?= $carouselEnabled === '1' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' ?> flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-images"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Slide Rotation</span>
                <span class="text-sm font-extrabold text-slate-900 mt-1 block"><?= $carouselAutoplay > 0 ? ($carouselAutoplay / 1000) . ' Seconds' : 'Manual Navigation' ?></span>
                <span class="text-[11px] text-blue-600 font-semibold mt-0.5 block">Auto-loop on hover</span>
            </div>
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Intake Gate Access</span>
                <span class="text-sm font-extrabold <?= $landingGateEnabled === '1' ? 'text-amber-700' : 'text-slate-600' ?> mt-1 block">
                    <?= $landingGateEnabled === '1' ? 'Gate Enabled' : 'Open Access' ?>
                </span>
                <span class="text-[11px] text-slate-500 font-semibold mt-0.5 block">Requires Idea Submission</span>
            </div>
            <div class="w-10 h-10 rounded-lg <?= $landingGateEnabled === '1' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600' ?> flex items-center justify-center text-lg shrink-0">
                <i class="fa-solid <?= $landingGateEnabled === '1' ? 'fa-lock' : 'fa-lock-open' ?>"></i>
            </div>
        </div>
    </div>

    <!-- 1. Top Section: Landing Page Configuration Form -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
        <div class="border-b border-slate-200 pb-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-sliders text-emerald-600 mr-2"></i> Landing Page Top Settings
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Control the top site brand title, subtitle description, and carousel behavior shown to visitors.</p>
            </div>
            <span class="text-[11px] font-mono bg-slate-100 text-slate-600 px-2.5 py-1 rounded border border-slate-200">
                Landing Page Controller
            </span>
        </div>

        <form method="POST" action="landing.php" class="space-y-4">
            <input type="hidden" name="action" value="save_landing_settings">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Site Name on Top -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Landing Page Top Site Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="landing_site_name" value="<?= htmlspecialchars($landingSiteName) ?>" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none font-semibold text-slate-900" placeholder="NextGen IncubationHub">
                    <p class="text-[11px] text-slate-500 mt-1">This brand title is prominently rendered on TOP of the landing page above the banner carousel.</p>
                </div>

                <!-- Autoplay Speed -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Carousel Auto-Slide Speed
                    </label>
                    <select name="landing_carousel_autoplay" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-900 font-semibold">
                        <option value="3000" <?= $carouselAutoplay === 3000 ? 'selected' : '' ?>>Fast (3 Seconds per slide)</option>
                        <option value="5000" <?= $carouselAutoplay === 5000 ? 'selected' : '' ?>>Standard (5 Seconds per slide - Recommended)</option>
                        <option value="7000" <?= $carouselAutoplay === 7000 ? 'selected' : '' ?>>Relaxed (7 Seconds per slide)</option>
                        <option value="10000" <?= $carouselAutoplay === 10000 ? 'selected' : '' ?>>Slow (10 Seconds per slide)</option>
                        <option value="0" <?= $carouselAutoplay === 0 ? 'selected' : '' ?>>Manual Only (Do not auto-advance)</option>
                    </select>
                    <p class="text-[11px] text-slate-500 mt-1">Carousel automatically pauses whenever a visitor hovers their cursor over a slide.</p>
                </div>
            </div>

            <!-- Subtitle Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Landing Page Subtitle Description
                </label>
                <textarea name="landing_subtitle" rows="2" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="Empowering visionary innovators, farmers, and entrepreneurs with living farmland testbeds, venture capital, and commercial scale."><?= htmlspecialchars($landingSubtitle) ?></textarea>
            </div>

            <!-- Toggles: Enable Carousel & Enable Gate -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <label class="flex items-start space-x-3 p-3.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition">
                    <input type="checkbox" name="landing_carousel_enabled" value="1" <?= $carouselEnabled === '1' ? 'checked' : '' ?> class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Show Banner Carousel Under Name</span>
                        <span class="text-[11px] text-slate-500 block mt-0.5">When checked, active banner slides will animate under the "NextGen IncubationHub" title.</span>
                    </div>
                </label>

                <label class="flex items-start space-x-3 p-3.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 cursor-pointer transition">
                    <input type="checkbox" name="landing_gate_enabled" value="1" <?= $landingGateEnabled === '1' ? 'checked' : '' ?> class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Enable Idea Submission Gate</span>
                        <span class="text-[11px] text-slate-500 block mt-0.5">Requires new visitors to submit their idea pitch before viewing the rest of the website.</span>
                    </div>
                </label>
            </div>

            <div class="pt-2 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-sm transition flex items-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Landing Page Settings
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Dynamic Banner Carousel Slides List -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6">
        <div class="border-b border-slate-200 pb-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid fa-images text-emerald-600 mr-2"></i> Dynamic Banner Carousel Slides
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage slides with custom background photography, highlighted badges, titles, descriptions, and CTA buttons.</p>
            </div>
            <div class="flex items-center space-x-2">
                <form method="POST" action="landing.php" onsubmit="return confirm('Restore the 3 default high-resolution AgriTech slides? This will reset all current banner slides.');">
                    <input type="hidden" name="action" value="reset_defaults">
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition" title="Restore default AgriTech curated slides">
                        <i class="fa-solid fa-rotate-left mr-1 text-slate-500"></i> Restore Defaults
                    </button>
                </form>
                <a href="#banner-modal-section" class="px-3.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                    <i class="fa-solid fa-plus mr-1"></i> Add New Slide
                </a>
            </div>
        </div>

        <?php if (empty($banners)): ?>
            <div class="text-center py-12 px-4 border-2 border-dashed border-slate-200 rounded-xl">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-lg">
                    <i class="fa-solid fa-image"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-700">No Banner Slides Found</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">Click "Add New Slide" to create your first dynamic banner, or click "Restore Defaults" to populate curated slides.</p>
                <div class="mt-4 flex items-center justify-center space-x-3">
                    <form method="POST" action="landing.php">
                        <input type="hidden" name="action" value="reset_defaults">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-sm transition">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1.5"></i> Restore Default Slides
                        </button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($banners as $idx => $b): 
                    $imgUrl = getBannerImageUrl($b['image_path'] ?? '', true);
                    $isActive = ($b['status'] ?? 'Active') === 'Active';
                ?>
                    <div class="group relative rounded-xl border border-slate-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <!-- Top Banner Image Preview -->
                        <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($b['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.src='https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=1600&q=80'">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                            
                            <!-- Badges on Image -->
                            <div class="absolute top-2.5 left-2.5 flex items-center space-x-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $isActive ? 'bg-emerald-500 text-slate-950' : 'bg-slate-700 text-slate-200' ?>">
                                    <?= $isActive ? 'Active' : 'Draft' ?>
                                </span>
                                <?php if (!empty($b['badge'])): ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-900/80 text-emerald-300 border border-emerald-500/30 backdrop-blur">
                                        <?= htmlspecialchars($b['badge']) ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="absolute top-2.5 right-2.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-900/80 text-slate-300 border border-slate-700 backdrop-blur">
                                    Order #<?= htmlspecialchars($b['sort_order'] ?? ($idx + 1)) ?>
                                </span>
                            </div>

                            <!-- Title on Image -->
                            <div class="absolute bottom-2.5 left-3 right-3 text-white">
                                <h3 class="text-sm font-bold line-clamp-2 leading-snug drop-shadow-sm">
                                    <?= htmlspecialchars($b['title']) ?>
                                </h3>
                            </div>
                        </div>

                        <!-- Card Body Details -->
                        <div class="p-4 flex-1 flex flex-col justify-between text-xs space-y-3">
                            <p class="text-slate-600 line-clamp-3 leading-relaxed">
                                <?= htmlspecialchars($b['subtitle'] ?: 'No subtitle provided.') ?>
                            </p>

                            <!-- CTA Link Info -->
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                <span class="font-semibold text-emerald-700 flex items-center truncate max-w-[150px]">
                                    <i class="fa-solid fa-arrow-up-right-from-square mr-1 text-[10px]"></i>
                                    <?= htmlspecialchars($b['link_text'] ?: 'Explore') ?>
                                </span>
                                <span class="font-mono text-slate-400 truncate max-w-[120px]" title="<?= htmlspecialchars($b['link_url'] ?? '') ?>">
                                    <?= htmlspecialchars($b['link_url'] ?: '#') ?>
                                </span>
                            </div>

                            <!-- Card Action Buttons -->
                            <div class="pt-2 flex items-center justify-between gap-1 border-t border-slate-100">
                                <a href="landing.php?edit_id=<?= (int)$b['id'] ?>#banner-modal-section" class="px-2.5 py-1.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition flex items-center">
                                    <i class="fa-solid fa-pen-to-square mr-1 text-slate-500"></i> Edit
                                </a>

                                <a href="landing.php?action=toggle&id=<?= (int)$b['id'] ?>" class="px-2.5 py-1.5 rounded <?= $isActive ? 'bg-amber-50 hover:bg-amber-100 text-amber-800' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800' ?> font-semibold text-xs transition flex items-center" title="<?= $isActive ? 'Disable slide to draft' : 'Activate slide' ?>">
                                    <i class="fa-solid <?= $isActive ? 'fa-pause' : 'fa-play' ?> mr-1"></i>
                                    <?= $isActive ? 'Set Draft' : 'Activate' ?>
                                </a>

                                <a href="landing.php?action=delete&id=<?= (int)$b['id'] ?>" onclick="return confirm('Are you sure you want to delete this banner slide?');" class="px-2.5 py-1.5 rounded bg-red-50 hover:bg-red-100 text-red-700 font-semibold text-xs transition flex items-center">
                                    <i class="fa-solid fa-trash mr-1"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- 3. Add / Edit Slide Form Section -->
    <div id="banner-modal-section" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 sm:p-6 scroll-mt-20">
        <div class="border-b border-slate-200 pb-4 mb-5 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center">
                    <i class="fa-solid <?= $editingBanner ? 'fa-pen-to-square text-amber-600' : 'fa-circle-plus text-emerald-600' ?> mr-2"></i>
                    <?= $editingBanner ? 'Edit Banner Slide #' . (int)$editingBanner['id'] : 'Create New Dynamic Banner Slide' ?>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    <?= $editingBanner ? 'Update slide photography, headline, and call-to-action button details.' : 'Add an impactful slide to the banner carousel under the site title.' ?>
                </p>
            </div>
            <?php if ($editingBanner): ?>
                <a href="landing.php" class="text-xs text-slate-500 hover:text-slate-800 font-semibold underline">
                    Cancel Edit / Add New
                </a>
            <?php endif; ?>
        </div>

        <form method="POST" action="landing.php" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="action" value="save_banner">
            <?php if ($editingBanner): ?>
                <input type="hidden" name="banner_id" value="<?= (int)$editingBanner['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Slide Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Banner Slide Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="<?= htmlspecialchars($editingBanner['title'] ?? '') ?>" required class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900 font-semibold" placeholder="e.g., Scale Your AgriTech Innovation to National Impact">
                </div>

                <!-- Badge / Tag -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Badge Tag Text (Optional)
                    </label>
                    <input type="text" name="badge" value="<?= htmlspecialchars($editingBanner['badge'] ?? '') ?>" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="e.g., Flagship Accelerator 2026">
                </div>
            </div>

            <!-- Subtitle Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">
                    Banner Subtitle / Description
                </label>
                <textarea name="subtitle" rows="2" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="e.g., Access 500+ acres of living farmland testbeds, IoT sensor laboratories, and direct commercial off-take partnerships across Bangladesh."><?= htmlspecialchars($editingBanner['subtitle'] ?? '') ?></textarea>
            </div>

            <!-- Image File Upload or URL -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 rounded-xl border border-slate-200 bg-slate-50">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Upload Image File (PNG, JPG, WEBP, GIF - Max 10MB)
                    </label>
                    <input type="file" name="banner_file" accept="image/*" class="w-full text-xs text-slate-700 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    <p class="text-[11px] text-slate-500 mt-1">Recommended resolution: 1600 × 700 pixels (widescreen high-definition).</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        OR Image Web URL (Unsplash, CDN, or Cloud Storage)
                    </label>
                    <input type="text" name="image_url" value="<?= htmlspecialchars($editingBanner['image_path'] ?? '') ?>" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="https://images.unsplash.com/photo-...">
                    <p class="text-[11px] text-slate-500 mt-1">Leave blank to use default high-res curated imagery if not uploading a file.</p>
                </div>
            </div>

            <!-- CTA Button & Sorting Controls -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        CTA Button Label
                    </label>
                    <input type="text" name="link_text" value="<?= htmlspecialchars($editingBanner['link_text'] ?? 'Submit Your Idea & Unlock Access') ?>" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="Submit Your Idea">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        CTA Button Link Target
                    </label>
                    <input type="text" name="link_url" value="<?= htmlspecialchars($editingBanner['link_url'] ?? '#intake-form-section') ?>" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" placeholder="#intake-form-section">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Display Order (Sort)
                    </label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars((string)($editingBanner['sort_order'] ?? 1)) ?>" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none text-slate-900" min="1" max="100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Slide Status
                    </label>
                    <select name="status" class="w-full text-xs px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-900 font-semibold">
                        <option value="Active" <?= (($editingBanner['status'] ?? 'Active') === 'Active') ? 'selected' : '' ?>>Active (Visible on Carousel)</option>
                        <option value="Draft" <?= (($editingBanner['status'] ?? '') === 'Draft') ? 'selected' : '' ?>>Draft (Hidden)</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-3 flex items-center justify-end space-x-3">
                <?php if ($editingBanner): ?>
                    <a href="landing.php" class="px-4 py-2.5 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition">
                        Cancel
                    </a>
                <?php endif; ?>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold shadow-sm transition flex items-center">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i>
                    <?= $editingBanner ? 'Update Banner Slide' : 'Publish Banner Slide' ?>
                </button>
            </div>
        </form>
    </div>

</div>

<?php
renderAdminFooter();
