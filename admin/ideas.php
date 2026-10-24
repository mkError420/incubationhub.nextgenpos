<?php
/**
 * NextGen Farming Incubation Hub - Visitor Idea Pitches & Video Intake Console
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$statusFilter = $_GET['status'] ?? null;
$categoryFilter = $_GET['category'] ?? null;
$hasVideoFilter = isset($_GET['has_video']) ? (bool)$_GET['has_video'] : null;
$search = trim($_GET['search'] ?? '');

// Handle CSV Export
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    $rows = getVisitorIdeas(1000, null, $search ?: null, $categoryFilter ?: null);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=nextgen_visitor_ideas_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Category Track', 'Full Name', 'WhatsApp Phone', 'Email', 'Education', 'Local Address', 'Idea Description', 'Video File', 'Video Duration (s)', 'Status', 'Date', 'Admin Notes', 'IP Address']);
    
    foreach ($rows as $r) {
        if ($statusFilter && ($r['status'] ?? '') !== $statusFilter) continue;
        if ($categoryFilter && ($r['category'] ?? 'Entrepreneurs') !== $categoryFilter) continue;
        if ($hasVideoFilter !== null) {
            $hasVid = !empty($r['video_path']) || !empty($r['video_duration']);
            if ($hasVideoFilter && !$hasVid) continue;
            if (!$hasVideoFilter && $hasVid) continue;
        }

        fputcsv($output, [
            $r['id'] ?? '',
            $r['category'] ?? 'Entrepreneurs',
            $r['full_name'] ?? '',
            $r['phone'] ?? '',
            $r['email'] ?? '',
            $r['education'] ?? '',
            $r['local_address'] ?? '',
            $r['idea_description'] ?? '',
            $r['video_path'] ?? '',
            $r['video_duration'] ?? '',
            $r['status'] ?? 'New',
            $r['created_at'] ?? '',
            $r['admin_notes'] ?? '',
            $r['ip_address'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Status & Admin Notes Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_idea') {
    $ideaId = (int)($_POST['idea_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? '');
    $notes = trim($_POST['admin_notes'] ?? '');
    if ($ideaId > 0 && !empty($newStatus)) {
        updateVisitorIdeaStatus($ideaId, $newStatus, $notes ?: null);
        $redirectUrl = "ideas.php?updated=1";
        if ($statusFilter) $redirectUrl .= "&status=" . urlencode($statusFilter);
        if ($categoryFilter) $redirectUrl .= "&category=" . urlencode($categoryFilter);
        if ($hasVideoFilter !== null) $redirectUrl .= "&has_video=" . ($hasVideoFilter ? '1' : '0');
        if (!empty($search)) $redirectUrl .= "&search=" . urlencode($search);
        header("Location: " . $redirectUrl);
        exit;
    }
}

// Handle Delete Idea
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    deleteVisitorIdea($delId);
    header("Location: ideas.php?deleted=1");
    exit;
}

// Fetch all ideas
$allIdeas = getVisitorIdeas(500, null, $search ?: null);

// Calculate metrics
$totalIdeasCount = count($allIdeas);
$newCount = 0;
$reviewedCount = 0;
$shortlistedCount = 0;
$contactedCount = 0;
$withVideoCount = 0;

$entrepreneursCount = 0;
$investorsCount = 0;
$mentorsCount = 0;
$organizersCount = 0;

foreach ($allIdeas as $item) {
    $st = $item['status'] ?? 'New';
    if ($st === 'New') $newCount++;
    elseif ($st === 'Reviewed') $reviewedCount++;
    elseif ($st === 'Shortlisted') $shortlistedCount++;
    elseif ($st === 'Contacted') $contactedCount++;

    if (!empty($item['video_path']) || !empty($item['video_duration'])) {
        $withVideoCount++;
    }

    $cat = trim($item['category'] ?? '');
    if (strcasecmp($cat, 'Investor') === 0 || strcasecmp($cat, 'Investors') === 0) {
        $investorsCount++;
    } elseif (strcasecmp($cat, 'Mentor') === 0 || strcasecmp($cat, 'Mentors') === 0) {
        $mentorsCount++;
    } elseif (strcasecmp($cat, 'Organizer') === 0 || strcasecmp($cat, 'Organizers') === 0 || stripos($cat, 'organis') !== false || stripos($cat, 'organiz') !== false) {
        $organizersCount++;
    } else {
        $entrepreneursCount++;
    }
}

// Apply in-memory filtering for active view
$ideas = array_filter($allIdeas, function ($item) use ($statusFilter, $categoryFilter, $hasVideoFilter) {
    if ($statusFilter && ($item['status'] ?? 'New') !== $statusFilter) {
        return false;
    }
    if ($categoryFilter && ($item['category'] ?? 'Entrepreneurs') !== $categoryFilter) {
        return false;
    }
    if ($hasVideoFilter !== null) {
        $hasVid = !empty($item['video_path']) || !empty($item['video_duration']);
        if ($hasVideoFilter && !$hasVid) return false;
        if (!$hasVideoFilter && $hasVid) return false;
    }
    return true;
});

renderAdminHeader('ideas', 'Visitor Idea Pitches & Video Intake', 'Visitor Ideas');
?>

<div class="max-w-7xl">

    <!-- Flash Alerts -->
    <?php if (isset($_GET['updated'])): ?>
        <div class="mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span class="font-bold">Visitor idea status & evaluation notes updated successfully!</span>
            </div>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="mb-5 p-3.5 rounded-lg bg-rose-50 border border-rose-300 text-rose-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-trash text-rose-600 text-sm"></i>
                <span class="font-bold">Idea submission and associated pitch video deleted successfully.</span>
            </div>
            <button type="button" class="alert-close-btn text-rose-700 hover:text-rose-950">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Top Action & Info Bar -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    <i class="fa-solid fa-lock-open mr-1 text-amber-600"></i> Gate Submissions
                </span>
                <span class="text-xs text-slate-400">•</span>
                <span class="text-xs text-slate-500 font-semibold"><?= count($ideas) ?> displaying</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight mt-1">Visitor Idea Pitches & Video Intake</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Every visitor must submit their profile, idea description, and optional 5-minute video before entering the website.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="ideas.php?action=export_csv<?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?><?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?><?= $hasVideoFilter !== null ? '&has_video=' . ($hasVideoFilter ? '1' : '0') : '' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" class="inline-flex items-center px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-file-csv mr-1.5 text-emerald-400"></i> Export to CSV
            </a>
            <a href="../landing.php" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-300 transition">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View Landing Page
            </a>
        </div>
    </div>

    <?php
    function buildIdeaUrl($params = []) {
        global $statusFilter, $categoryFilter, $hasVideoFilter, $search;
        $base = [
            'status' => $statusFilter,
            'category' => $categoryFilter,
            'has_video' => $hasVideoFilter !== null ? ($hasVideoFilter ? '1' : '0') : null,
            'search' => !empty($search) ? $search : null
        ];
        $merged = array_merge($base, $params);
        $filtered = array_filter($merged, function($v) { return $v !== null && $v !== ''; });
        return 'ideas.php' . (!empty($filtered) ? '?' . http_build_query($filtered) : '');
    }
    ?>

    <!-- Metric KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
        
        <a href="<?= buildIdeaUrl(['status' => null, 'has_video' => null]) ?>" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 transition">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Submissions</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $totalIdeasCount ?></span>
            <span class="text-[11px] text-slate-500 font-semibold mt-0.5 block">All Gate Intakes</span>
        </a>

        <a href="<?= buildIdeaUrl(['status' => 'New', 'has_video' => null]) ?>" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-amber-400 transition">
            <span class="text-[11px] font-bold text-amber-600 uppercase tracking-wider block">New / Pending</span>
            <span class="text-2xl font-extrabold text-amber-600 mt-1 block"><?= $newCount ?></span>
            <span class="text-[11px] text-amber-700 font-semibold mt-0.5 block">Awaiting evaluation</span>
        </a>

        <a href="<?= buildIdeaUrl(['status' => 'Shortlisted', 'has_video' => null]) ?>" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-emerald-400 transition">
            <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider block">Shortlisted</span>
            <span class="text-2xl font-extrabold text-emerald-600 mt-1 block"><?= $shortlistedCount ?></span>
            <span class="text-[11px] text-emerald-700 font-semibold mt-0.5 block">For Founder Interview</span>
        </a>

        <a href="<?= buildIdeaUrl(['status' => 'Contacted', 'has_video' => null]) ?>" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-400 transition">
            <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider block">Contacted</span>
            <span class="text-2xl font-extrabold text-blue-600 mt-1 block"><?= $contactedCount ?></span>
            <span class="text-[11px] text-blue-700 font-semibold mt-0.5 block">Via WhatsApp / Call</span>
        </a>

        <a href="<?= buildIdeaUrl(['has_video' => '1', 'status' => null]) ?>" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-purple-400 transition col-span-2 sm:col-span-1">
            <span class="text-[11px] font-bold text-purple-600 uppercase tracking-wider block">With Video Pitch</span>
            <span class="text-2xl font-extrabold text-purple-600 mt-1 block"><?= $withVideoCount ?></span>
            <span class="text-[11px] text-purple-700 font-semibold mt-0.5 block">5-Min Video Available</span>
        </a>

    </div>

    <!-- Filters & Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 space-y-3">
        
        <!-- Category Track Selector Row -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 text-xs font-bold border-b border-slate-100">
            <span class="text-slate-400 uppercase text-[10px] tracking-wider shrink-0 mr-1"><i class="fa-solid fa-layer-group mr-1"></i> Track:</span>
            <a href="<?= buildIdeaUrl(['category' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= empty($categoryFilter) ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' ?> shrink-0 transition">
                All Tracks (<?= $totalIdeasCount ?>)
            </a>
            <a href="<?= buildIdeaUrl(['category' => 'Entrepreneurs']) ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Entrepreneurs' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-emerald-50/70 text-emerald-800 border-emerald-200 hover:bg-emerald-100' ?> shrink-0 transition inline-flex items-center">
                <i class="fa-solid fa-seedling mr-1.5 text-xs"></i> Entrepreneurs (<?= $entrepreneursCount ?>)
            </a>
            <a href="<?= buildIdeaUrl(['category' => 'Investors']) ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Investors' ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-amber-50/70 text-amber-800 border-amber-200 hover:bg-amber-100' ?> shrink-0 transition inline-flex items-center">
                <i class="fa-solid fa-coins mr-1.5 text-xs"></i> Investors (<?= $investorsCount ?>)
            </a>
            <a href="<?= buildIdeaUrl(['category' => 'Mentors']) ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Mentors' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-blue-50/70 text-blue-800 border-blue-200 hover:bg-blue-100' ?> shrink-0 transition inline-flex items-center">
                <i class="fa-solid fa-chalkboard-user mr-1.5 text-xs"></i> Mentors (<?= $mentorsCount ?>)
            </a>
            <a href="<?= buildIdeaUrl(['category' => 'Organizers']) ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Organizers' ? 'bg-teal-700 text-white border-teal-700 shadow-sm' : 'bg-teal-50/70 text-teal-800 border-teal-200 hover:bg-teal-100' ?> shrink-0 transition inline-flex items-center">
                <i class="fa-solid fa-users mr-1.5 text-xs"></i> Organizers (<?= $organizersCount ?>)
            </a>
        </div>

        <!-- Status Filter Tabs & Search Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
            <div class="flex flex-wrap items-center gap-1.5 text-xs font-bold">
                <a href="<?= buildIdeaUrl(['status' => null, 'has_video' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= empty($statusFilter) && $hasVideoFilter === null ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' ?> transition">
                    All Status
                </a>
                <a href="<?= buildIdeaUrl(['status' => 'New', 'has_video' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'New' ? 'bg-amber-600 text-white border-amber-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' ?> transition">
                    New (<?= $newCount ?>)
                </a>
                <a href="<?= buildIdeaUrl(['status' => 'Reviewed', 'has_video' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Reviewed' ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' ?> transition">
                    Reviewed (<?= $reviewedCount ?>)
                </a>
                <a href="<?= buildIdeaUrl(['status' => 'Shortlisted', 'has_video' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Shortlisted' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' ?> transition">
                    Shortlisted (<?= $shortlistedCount ?>)
                </a>
                <a href="<?= buildIdeaUrl(['status' => 'Contacted', 'has_video' => null]) ?>" class="px-3 py-1.5 rounded-lg border <?= $statusFilter === 'Contacted' ? 'bg-purple-600 text-white border-purple-600' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' ?> transition">
                    Contacted (<?= $contactedCount ?>)
                </a>
                <a href="<?= buildIdeaUrl(['has_video' => $hasVideoFilter === true ? null : '1']) ?>" class="px-3 py-1.5 rounded-lg border <?= $hasVideoFilter === true ? 'bg-purple-700 text-white border-purple-700' : 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100' ?> transition flex items-center">
                    <i class="fa-solid fa-video mr-1 text-xs"></i> Videos Only (<?= $withVideoCount ?>)
                </a>
            </div>

            <!-- Search Input -->
            <form action="ideas.php" method="GET" class="flex items-center space-x-2 shrink-0">
                <?php if ($statusFilter): ?>
                    <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
                <?php endif; ?>
                <?php if ($categoryFilter): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($categoryFilter) ?>">
                <?php endif; ?>
                <?php if ($hasVideoFilter !== null): ?>
                    <input type="hidden" name="has_video" value="<?= $hasVideoFilter ? '1' : '0' ?>">
                <?php endif; ?>
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, phone, idea..." class="pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-300 rounded-lg text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:bg-white w-48 sm:w-60">
                    <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs pointer-events-none"></i>
                </div>
                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg transition">
                    Search
                </button>
                <?php if (!empty($search) || !empty($statusFilter) || !empty($categoryFilter) || $hasVideoFilter !== null): ?>
                    <a href="ideas.php" class="text-xs text-slate-500 hover:text-slate-800 px-2 py-1.5 rounded hover:bg-slate-100" title="Reset All Filters">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-extrabold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3.5">ID / Date</th>
                        <th class="px-5 py-3.5">Track / Category</th>
                        <th class="px-5 py-3.5">Applicant & Background</th>
                        <th class="px-5 py-3.5">WhatsApp / Contact</th>
                        <th class="px-5 py-3.5">Idea Description</th>
                        <th class="px-5 py-3.5">Pitch Video (5m)</th>
                        <th class="px-5 py-3.5">Status & Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($ideas)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-lightbulb text-4xl mb-3 text-slate-300 block"></i>
                                No visitor idea submissions found under current criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ideas as $row): 
                            $rawPhone = $row['phone'] ?? '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            $waLink = !empty($cleanPhone) ? 'https://wa.me/' . $cleanPhone : null;
                            $hasVideo = !empty($row['video_path']);
                            $durationSec = (int)($row['video_duration'] ?? 0);
                            $durationText = '';
                            if ($durationSec > 0) {
                                $dMins = floor($durationSec / 60);
                                $dSecs = $durationSec % 60;
                                $durationText = $dMins . 'm ' . ($dSecs < 10 ? '0' : '') . $dSecs . 's';
                            }
                            $status = $row['status'] ?? 'New';
                            $statusColors = [
                                'New'         => 'bg-amber-100 text-amber-800 border-amber-300',
                                'Reviewed'    => 'bg-blue-100 text-blue-800 border-blue-300',
                                'Shortlisted' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'Contacted'   => 'bg-purple-100 text-purple-800 border-purple-300',
                                'Archived'    => 'bg-slate-100 text-slate-600 border-slate-300'
                            ];
                            $statusBadgeClass = $statusColors[$status] ?? 'bg-slate-100 text-slate-700 border-slate-200';

                            $category = !empty($row['category']) ? $row['category'] : 'Entrepreneurs';
                            $catBadgeConfig = [
                                'Entrepreneurs' => [
                                    'class' => 'bg-emerald-50 text-emerald-800 border-emerald-300',
                                    'icon'  => 'fa-seedling',
                                    'label' => 'Entrepreneurs'
                                ],
                                'Investors' => [
                                    'class' => 'bg-amber-50 text-amber-800 border-amber-300',
                                    'icon'  => 'fa-coins',
                                    'label' => 'Investors'
                                ],
                                'Mentors' => [
                                    'class' => 'bg-blue-50 text-blue-800 border-blue-300',
                                    'icon'  => 'fa-chalkboard-user',
                                    'label' => 'Mentors'
                                ],
                                'Organizers' => [
                                    'class' => 'bg-teal-50 text-teal-800 border-teal-300',
                                    'icon'  => 'fa-users',
                                    'label' => 'Organizers'
                                ]
                            ];
                            $catConfig = $catBadgeConfig[$category] ?? $catBadgeConfig['Entrepreneurs'];
                        ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                
                                <!-- ID & Date -->
                                <td class="px-5 py-4 align-top">
                                    <span class="font-mono font-bold text-slate-900 block">#<?= htmlspecialchars($row['id']) ?></span>
                                    <span class="text-[11px] text-slate-500 block mt-0.5">
                                        <?= date('M d, Y', strtotime($row['created_at'])) ?>
                                    </span>
                                    <span class="text-[10px] text-slate-400 block font-mono">
                                        <?= date('h:i A', strtotime($row['created_at'])) ?>
                                    </span>
                                </td>

                                <!-- Track / Category -->
                                <td class="px-5 py-4 align-top">
                                    <a href="<?= buildIdeaUrl(['category' => $category]) ?>" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border <?= $catConfig['class'] ?> shadow-2xs hover:opacity-80 transition" title="Filter by <?= htmlspecialchars($category) ?>">
                                        <i class="fa-solid <?= $catConfig['icon'] ?> mr-1.5 text-xs"></i>
                                        <?= htmlspecialchars($category) ?>
                                    </a>
                                </td>

                                <!-- Applicant & Background -->
                                <td class="px-5 py-4 align-top max-w-xs">
                                    <span class="font-bold text-slate-900 text-sm block">
                                        <?= htmlspecialchars($row['full_name']) ?>
                                    </span>
                                    <span class="inline-flex items-center text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 font-medium mt-1">
                                        <i class="fa-solid fa-graduation-cap mr-1 text-emerald-600"></i>
                                        <?= htmlspecialchars($row['education'] ?? 'Not specified') ?>
                                    </span>
                                    <span class="text-[11px] text-slate-500 block mt-1">
                                        <i class="fa-solid fa-location-dot mr-1 text-slate-400"></i>
                                        <?= htmlspecialchars($row['local_address'] ?? 'N/A') ?>
                                    </span>
                                </td>

                                <!-- WhatsApp / Contact -->
                                <td class="px-5 py-4 align-top">
                                    <?php if ($waLink): ?>
                                        <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" class="inline-flex items-center px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg text-xs font-bold transition shadow-2xs mb-1">
                                            <i class="fa-brands fa-whatsapp text-emerald-600 mr-1.5 text-sm"></i>
                                            <?= htmlspecialchars($rawPhone) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="font-bold text-slate-700 block"><?= htmlspecialchars($rawPhone) ?></span>
                                    <?php endif; ?>

                                    <?php if (!empty($row['email'])): ?>
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="text-[11px] text-slate-500 hover:text-slate-800 block flex items-center mt-1 truncate max-w-[180px]">
                                            <i class="fa-solid fa-envelope mr-1 text-slate-400"></i>
                                            <?= htmlspecialchars($row['email']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-400 italic block mt-1">No email provided</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Idea Description -->
                                <td class="px-5 py-4 align-top max-w-md">
                                    <p class="text-xs text-slate-700 line-clamp-3 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($row['idea_description'])) ?>
                                    </p>
                                    <button type="button" onclick="openIdeaModal(<?= htmlspecialchars(json_encode($row)) ?>)" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold mt-1.5 inline-flex items-center">
                                        <i class="fa-solid fa-up-right-and-down-left-from-center mr-1"></i> Read Full Idea Details
                                    </button>
                                </td>

                                <!-- Pitch Video -->
                                <td class="px-5 py-4 align-top">
                                    <?php if ($hasVideo): ?>
                                        <div class="space-y-1.5">
                                            <button type="button" onclick="openVideoPlayer('<?= htmlspecialchars('../' . ltrim($row['video_path'], '/')) ?>', '<?= htmlspecialchars(addslashes($row['full_name'])) ?>', '<?= $durationText ?>')" class="inline-flex items-center px-3 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                <i class="fa-solid fa-play mr-1.5 text-[10px]"></i> Watch Pitch
                                            </button>
                                            
                                            <div class="flex items-center space-x-2 text-[10px] text-slate-500 font-medium">
                                                <?php if (!empty($durationText)): ?>
                                                    <span class="text-purple-700 font-bold bg-purple-50 px-1.5 py-0.5 rounded border border-purple-200">
                                                        <i class="fa-solid fa-stopwatch mr-0.5"></i> <?= $durationText ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($row['video_size'])): ?>
                                                    <span><?= round($row['video_size'] / (1024 * 1024), 1) ?> MB</span>
                                                <?php endif; ?>
                                            </div>

                                            <a href="<?= htmlspecialchars('../' . ltrim($row['video_path'], '/')) ?>" download class="text-[10px] text-slate-500 hover:text-purple-700 block underline">
                                                <i class="fa-solid fa-download mr-1"></i> Download File
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                            <i class="fa-solid fa-align-left mr-1 text-slate-400"></i> Text Only (No Video)
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status & Quick Action Form -->
                                <td class="px-5 py-4 align-top">
                                    <form action="ideas.php" method="POST" class="space-y-2">
                                        <input type="hidden" name="action" value="update_idea">
                                        <input type="hidden" name="idea_id" value="<?= $row['id'] ?>">
                                        
                                        <!-- Status Badge & Selector -->
                                        <div>
                                            <select name="status" onchange="this.form.submit()" class="text-xs font-bold px-2.5 py-1 rounded-lg border <?= $statusBadgeClass ?> cursor-pointer focus:outline-none shadow-2xs">
                                                <option value="New" <?= $status === 'New' ? 'selected' : '' ?>>● New</option>
                                                <option value="Reviewed" <?= $status === 'Reviewed' ? 'selected' : '' ?>>● Reviewed</option>
                                                <option value="Shortlisted" <?= $status === 'Shortlisted' ? 'selected' : '' ?>>● Shortlisted</option>
                                                <option value="Contacted" <?= $status === 'Contacted' ? 'selected' : '' ?>>● Contacted</option>
                                                <option value="Archived" <?= $status === 'Archived' ? 'selected' : '' ?>>● Archived</option>
                                            </select>
                                        </div>

                                        <!-- Admin Notes Quick Input -->
                                        <div class="flex items-center space-x-1">
                                            <input type="text" name="admin_notes" value="<?= htmlspecialchars($row['admin_notes'] ?? '') ?>" placeholder="Evaluation notes..." class="text-[11px] px-2 py-1 bg-slate-50 border border-slate-300 rounded focus:bg-white focus:outline-none w-32">
                                            <button type="submit" title="Save notes" class="p-1 text-slate-500 hover:text-emerald-700 text-xs">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </div>

                                        <!-- Delete Link -->
                                        <div class="pt-1">
                                            <a href="ideas.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to permanently delete this submission from <?= htmlspecialchars(addslashes($row['full_name'])) ?>?');" class="text-[10px] text-rose-500 hover:text-rose-700 hover:underline">
                                                <i class="fa-solid fa-trash mr-1"></i> Delete
                                            </a>
                                        </div>

                                    </form>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Video Pitch Modal Player -->
<div id="video-pitch-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
            <div>
                <h3 class="text-base font-extrabold text-white" id="modal-video-title">Pitch Video</h3>
                <span class="text-xs text-emerald-400 font-semibold" id="modal-video-duration"></span>
            </div>
            <button type="button" onclick="closeVideoPlayer()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="bg-black rounded-xl overflow-hidden max-h-[450px] flex items-center justify-center">
            <video id="modal-video-player" controls autoplay class="w-full max-h-[450px] object-contain"></video>
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a id="modal-video-download" href="#" download class="text-xs text-slate-400 hover:text-white inline-flex items-center">
                <i class="fa-solid fa-download mr-1.5 text-purple-400"></i> Download Pitch Video
            </a>
            <button type="button" onclick="closeVideoPlayer()" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Detailed Idea View Modal -->
<div id="idea-detail-modal" class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-200 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 uppercase tracking-wide">
                        Visitor Idea Intake Details
                    </span>
                    <span id="detail-modal-category-badge" class="text-xs font-bold px-2.5 py-0.5 rounded-full border uppercase tracking-wide"></span>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 mt-1" id="detail-modal-name">Applicant Name</h3>
            </div>
            <button type="button" onclick="closeIdeaModal()" class="text-slate-400 hover:text-slate-800 p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-4 text-xs text-slate-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                <div class="sm:col-span-2 flex items-center justify-between p-2.5 bg-white rounded-lg border border-slate-200">
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Track / Role Selection:</span>
                    <span id="detail-modal-category" class="font-bold text-xs inline-flex items-center px-2.5 py-0.5 rounded-lg border"></span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">WhatsApp Phone:</span>
                    <span id="detail-modal-phone" class="font-bold text-slate-900 text-sm mt-0.5 block"></span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Email Address:</span>
                    <span id="detail-modal-email" class="font-semibold text-slate-800 text-sm mt-0.5 block"></span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Education / Background:</span>
                    <span id="detail-modal-education" class="font-semibold text-slate-800 text-sm mt-0.5 block"></span>
                </div>
                <div>
                    <span class="font-bold text-slate-500 uppercase tracking-wider block text-[10px]">Local Address:</span>
                    <span id="detail-modal-address" class="font-semibold text-slate-800 text-sm mt-0.5 block"></span>
                </div>
            </div>

            <div>
                <span class="font-bold text-slate-900 uppercase tracking-wider block text-xs mb-2">Full Idea Description:</span>
                <div id="detail-modal-description" class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-sm leading-relaxed whitespace-pre-line text-slate-800"></div>
            </div>

            <div id="detail-modal-video-box" class="hidden p-4 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-between">
                <div>
                    <span class="font-bold text-purple-900 block text-xs">5-Minute Pitch Video Attached</span>
                    <span class="text-[11px] text-purple-700" id="detail-modal-video-meta"></span>
                </div>
                <button type="button" id="detail-modal-video-btn" class="px-3.5 py-1.5 bg-purple-700 hover:bg-purple-800 text-white rounded-lg text-xs font-bold transition">
                    <i class="fa-solid fa-play mr-1.5"></i> Play Video
                </button>
            </div>
        </div>

        <div class="mt-6 flex justify-end border-t border-slate-100 pt-4">
            <button type="button" onclick="closeIdeaModal()" class="px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openVideoPlayer(src, name, duration) {
        const modal = document.getElementById('video-pitch-modal');
        const player = document.getElementById('modal-video-player');
        const title = document.getElementById('modal-video-title');
        const dur = document.getElementById('modal-video-duration');
        const download = document.getElementById('modal-video-download');

        title.textContent = 'Pitch Video by ' + name;
        dur.textContent = duration ? 'Duration: ' + duration : '';
        player.src = src;
        download.href = src;

        modal.classList.remove('hidden');
    }

    function closeVideoPlayer() {
        const modal = document.getElementById('video-pitch-modal');
        const player = document.getElementById('modal-video-player');
        player.pause();
        player.removeAttribute('src');
        modal.classList.add('hidden');
    }

    function openIdeaModal(data) {
        document.getElementById('detail-modal-name').textContent = data.full_name || 'N/A';
        document.getElementById('detail-modal-phone').textContent = data.phone || 'N/A';
        document.getElementById('detail-modal-email').textContent = data.email || 'None provided';
        document.getElementById('detail-modal-education').textContent = data.education || 'N/A';
        document.getElementById('detail-modal-address').textContent = data.local_address || 'N/A';
        document.getElementById('detail-modal-description').textContent = data.idea_description || '';

        const category = data.category || 'Entrepreneurs';
        const catEl = document.getElementById('detail-modal-category');
        const badgeEl = document.getElementById('detail-modal-category-badge');
        
        const catConfigs = {
            'Entrepreneurs': { class: 'bg-emerald-50 text-emerald-800 border-emerald-300', icon: 'fa-seedling' },
            'Investors': { class: 'bg-amber-50 text-amber-800 border-amber-300', icon: 'fa-coins' },
            'Mentors': { class: 'bg-blue-50 text-blue-800 border-blue-300', icon: 'fa-chalkboard-user' },
            'Organizers': { class: 'bg-teal-50 text-teal-800 border-teal-300', icon: 'fa-users' }
        };
        const cfg = catConfigs[category] || catConfigs['Entrepreneurs'];

        if (catEl) {
            catEl.className = 'font-bold text-xs inline-flex items-center px-2.5 py-0.5 rounded-lg border ' + cfg.class;
            catEl.innerHTML = '<i class="fa-solid ' + cfg.icon + ' mr-1.5"></i> ' + category;
        }
        if (badgeEl) {
            badgeEl.className = 'text-xs font-bold px-2.5 py-0.5 rounded-full border uppercase tracking-wide ' + cfg.class;
            badgeEl.innerHTML = '<i class="fa-solid ' + cfg.icon + ' mr-1.5"></i> ' + category + ' Track';
        }

        const vidBox = document.getElementById('detail-modal-video-box');
        const vidBtn = document.getElementById('detail-modal-video-btn');
        const vidMeta = document.getElementById('detail-modal-video-meta');

        if (data.video_path) {
            vidBox.classList.remove('hidden');
            let dur = data.video_duration ? Math.floor(data.video_duration / 60) + 'm ' + (data.video_duration % 60) + 's' : '';
            vidMeta.textContent = dur ? 'Duration: ' + dur : 'Video file ready for playback';
            vidBtn.onclick = function() {
                closeIdeaModal();
                openVideoPlayer('../' + data.video_path.replace(/^\/+/, ''), data.full_name, dur);
            };
        } else {
            vidBox.classList.add('hidden');
        }

        document.getElementById('idea-detail-modal').classList.remove('hidden');
    }

    function closeIdeaModal() {
        document.getElementById('idea-detail-modal').classList.add('hidden');
    }

    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideoPlayer();
            closeIdeaModal();
        }
    });
</script>

<?php
renderAdminFooter();
?>
