<?php
/**
 * NextGen Farming Incubation Hub - Admin Dashboard Overview (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$stats = getAdminDashboardStats();
$recentSubmissions = getHubSubmissions(15);
$recentIdeas = getVisitorIdeas(6);
$dbConnected = isDbConnected();

// Handle quick inline status update from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $subId = (int)($_POST['submission_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? '');
    $notes = trim($_POST['admin_notes'] ?? '');
    
    if ($subId && $newStatus) {
        updateSubmissionStatus($subId, $newStatus, $notes ?: null);
        header("Location: index.php?status_updated=1");
        exit;
    }
}

renderAdminHeader('dashboard', 'Dashboard Overview', 'Dashboard Overview');
?>

<!-- Alert Feedback -->
<?php if (isset($_GET['status_updated'])): ?>
    <div class="alert-box mb-5 p-3.5 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
            <span class="font-bold">Submission status updated successfully in database!</span>
        </div>
        <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
<?php endif; ?>

<!-- Classic Page Header Bar -->
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
    <div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Incubation Hub Management Console</h1>
        <p class="text-xs text-slate-500 mt-0.5">Welcome back, <?= htmlspecialchars(getCurrentAdmin()['full_name']) ?> • System operational</p>
    </div>
    <div class="flex flex-wrap gap-2 text-xs">
        <a href="landing.php" class="inline-flex items-center px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-pager mr-1.5"></i> Landing Page
        </a>
        <a href="ideas.php" class="inline-flex items-center px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-lightbulb mr-1.5"></i> Visitor Ideas (<?= $stats['new_ideas'] ?? 0 ?> New)
        </a>
        <a href="content.php" class="inline-flex items-center px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Site Content
        </a>
        <a href="programs.php?action=new" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-rocket mr-1.5"></i> Programs
        </a>
        <a href="offerings.php" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-layer-group mr-1.5"></i> Offerings
        </a>
        <a href="networking.php" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-network-wired mr-1.5"></i> Network
        </a>
        <a href="submissions.php?action=export_csv" class="inline-flex items-center px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-download mr-1.5 text-slate-500"></i> Export CSV
        </a>
    </div>
</div>

<!-- Classic Metric KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    
    <!-- KPI 1: Total Leads -->
    <a href="submissions.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Submissions</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $stats['total'] ?></span>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-database mr-1"></i> Logged in MySQL
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-inbox"></i>
        </div>
    </a>

    <!-- KPI 2: Pending Action -->
    <a href="submissions.php?status=New" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-amber-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pending</span>
            <span class="text-2xl font-extrabold text-amber-600 mt-1 block"><?= $stats['new'] ?> New</span>
            <span class="text-[11px] text-amber-700 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-clock mr-1"></i> Awaiting review
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </a>

    <!-- KPI 3: Cohort Programs -->
    <a href="programs.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Programs</span>
            <span class="text-2xl font-extrabold text-blue-900 mt-1 block"><?= $stats['total_programs'] ?></span>
            <span class="text-[11px] text-blue-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-rocket mr-1"></i> Flagship tracks
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-seedling"></i>
        </div>
    </a>

    <!-- KPI 4: Core Offerings -->
    <a href="offerings.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-emerald-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Offerings</span>
            <span class="text-2xl font-extrabold text-emerald-800 mt-1 block"><?= $stats['total_offerings'] ?? 6 ?></span>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-layer-group mr-1"></i> Capabilities
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-tractor"></i>
        </div>
    </a>

    <!-- KPI 5: Network Partners -->
    <a href="networking.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-purple-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Partners</span>
            <span class="text-2xl font-extrabold text-purple-900 mt-1 block"><?= $stats['total_partners'] ?? 6 ?></span>
            <span class="text-[11px] text-purple-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-network-wired mr-1"></i> Ecosystem
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-handshake"></i>
        </div>
    </a>

    <!-- KPI 6: Visitor Ideas (Gate Intakes) -->
    <a href="ideas.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-amber-400 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider block">Visitor Ideas</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $stats['total_ideas'] ?? 0 ?></span>
            <span class="text-[11px] text-amber-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-lightbulb mr-1"></i> <?= $stats['new_ideas'] ?? 0 ?> New Pitches
            </span>
        </div>
        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-base shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
    </a>

</div>

<!-- 4 Stakeholder Breakdown Cards -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <a href="submissions.php?category=Entrepreneur" class="p-3.5 rounded-lg bg-white border border-slate-200 hover:border-emerald-300 transition flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold shrink-0">
            <i class="fa-solid fa-seedling"></i>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 block uppercase tracking-wider">Founders</span>
            <span class="text-sm font-bold text-slate-900"><?= $stats['entrepreneurs'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Investor" class="p-3.5 rounded-lg bg-white border border-slate-200 hover:border-amber-300 transition flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-xs font-bold shrink-0">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 block uppercase tracking-wider">Investors</span>
            <span class="text-sm font-bold text-slate-900"><?= $stats['investors'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Support%20Organisation" class="p-3.5 rounded-lg bg-white border border-slate-200 hover:border-blue-300 transition flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center text-xs font-bold shrink-0">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 block uppercase tracking-wider">Organizations</span>
            <span class="text-sm font-bold text-slate-900"><?= $stats['organisations'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Mentor" class="p-3.5 rounded-lg bg-white border border-slate-200 hover:border-purple-300 transition flex items-center space-x-3 shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center text-xs font-bold shrink-0">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div>
            <span class="text-[10px] font-bold text-slate-500 block uppercase tracking-wider">Mentors</span>
            <span class="text-sm font-bold text-slate-900"><?= $stats['mentors'] ?> Submissions</span>
        </div>
    </a>
</div>

<!-- Recent Visitor Idea Pitches (Gate Submissions) Section -->
<div class="bg-white rounded-xl border border-amber-200/80 shadow-sm overflow-hidden mb-8">
    <div class="px-5 py-4 bg-gradient-to-r from-amber-50/70 via-white to-emerald-50/50 border-b border-amber-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-base shadow-sm">
                <i class="fa-solid fa-lightbulb"></i>
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Visitor Idea Pitches (Landing Gate Intakes)</h2>
                    <span class="text-[10px] bg-amber-500 text-slate-950 font-black px-2 py-0.5 rounded-full uppercase tracking-wide">Live Intake</span>
                </div>
                <p class="text-[11px] text-slate-500">Applications and 5-minute video pitches captured before visitors enter the website.</p>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <a href="ideas.php" class="text-xs font-bold text-amber-700 hover:text-amber-900 flex items-center px-3 py-1.5 rounded-lg bg-amber-100/60 hover:bg-amber-100 transition">
                <span>Open Ideas Console (<?= $stats['total_ideas'] ?? 0 ?>)</span>
                <i class="fa-solid fa-arrow-right ml-1.5 text-xs"></i>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3">Applicant & Background</th>
                    <th class="px-4 py-3">WhatsApp Phone</th>
                    <th class="px-4 py-3">Local Address</th>
                    <th class="px-4 py-3">Idea Concept</th>
                    <th class="px-4 py-3">Pitch Video</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php if (empty($recentIdeas)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            No visitor ideas submitted yet. Visit <a href="../landing.php" target="_blank" class="text-emerald-600 underline font-semibold">landing.php</a> to test the intake form.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentIdeas as $idea): 
                        $rawPhone = $idea['phone'] ?? '';
                        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                        $waLink = !empty($cleanPhone) ? 'https://wa.me/' . $cleanPhone : null;
                        $hasVid = !empty($idea['video_path']);
                        $status = $idea['status'] ?? 'New';
                        $statusBadge = match ($status) {
                            'New' => 'bg-amber-100 text-amber-800 border-amber-300',
                            'Reviewed' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'Shortlisted' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'Contacted' => 'bg-purple-100 text-purple-800 border-purple-300',
                            default => 'bg-slate-100 text-slate-600 border-slate-300',
                        };
                    ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                <span class="block text-slate-900 font-bold"><?= htmlspecialchars($idea['full_name']) ?></span>
                                <span class="text-[10px] text-emerald-700 block"><?= htmlspecialchars($idea['education'] ?? '') ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($waLink): ?>
                                    <a href="<?= htmlspecialchars($waLink) ?>" target="_blank" class="inline-flex items-center text-emerald-700 hover:text-emerald-900 font-bold text-xs">
                                        <i class="fa-brands fa-whatsapp mr-1 text-sm text-emerald-600"></i>
                                        <?= htmlspecialchars($rawPhone) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-600"><?= htmlspecialchars($rawPhone) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-slate-600 truncate max-w-[150px]">
                                <?= htmlspecialchars($idea['local_address'] ?? '') ?>
                            </td>
                            <td class="px-4 py-3 text-slate-700 truncate max-w-xs">
                                <?= htmlspecialchars(mb_strimwidth($idea['idea_description'] ?? '', 0, 70, '...')) ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($hasVid): ?>
                                    <a href="ideas.php" class="inline-flex items-center text-[11px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-800 border border-purple-200 hover:bg-purple-200 transition">
                                        <i class="fa-solid fa-play mr-1 text-[9px]"></i> Watch Video
                                    </a>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-400">Text Only</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold border <?= $statusBadge ?>">
                                    <?= htmlspecialchars($status) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="ideas.php" class="text-xs font-bold text-slate-700 hover:text-amber-700">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Classic Recent Inquiries Data Table -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    
    <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Recent Inquiries & Applications</h2>
            <p class="text-[11px] text-slate-500">Live feed of applicants captured from the frontend website form.</p>
        </div>
        <a href="submissions.php" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center">
            View All in Submissions Manager &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3">ID</th>
                    <th class="px-4 py-3">Applicant & Entity</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Track / Focus</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php if (empty($recentSubmissions)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                            No submissions recorded in database.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentSubmissions as $row): 
                        $badgeColor = match ($row['category']) {
                            'Entrepreneur' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Investor' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Support Organisation' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Mentor' => 'bg-purple-50 text-purple-700 border-purple-200',
                            default => 'bg-slate-50 text-slate-700 border-slate-200',
                        };

                        $statusBadge = match ($row['status']) {
                            'New' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Under Review' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Shortlisted' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'Contacted' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                    ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-mono font-semibold text-slate-500">
                                #<?= htmlspecialchars($row['id']) ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-bold text-slate-900 block"><?= htmlspecialchars($row['full_name']) ?></span>
                                <span class="text-[11px] text-slate-500"><?= htmlspecialchars($row['organization'] ?: 'Independent') ?></span>
                                <span class="text-[11px] text-slate-400 block"><?= htmlspecialchars($row['email']) ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border <?= $badgeColor ?>">
                                    <?= htmlspecialchars($row['category']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-700 font-medium max-w-xs truncate" title="<?= htmlspecialchars($row['sub_interest']) ?>">
                                <?= htmlspecialchars($row['sub_interest'] ?: 'General Program') ?>
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="index.php" class="inline">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded border text-xs font-medium bg-white text-slate-800 border-slate-300 focus:outline-none focus:border-emerald-600">
                                        <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                        <option value="Under Review" <?= $row['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                                        <option value="Shortlisted" <?= $row['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                        <option value="Contacted" <?= $row['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                        <option value="Archived" <?= $row['status'] === 'Archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-[11px] text-slate-500 whitespace-nowrap">
                                <?= date('M d, Y', strtotime($row['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <button type="button" 
                                    class="view-lead-btn px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs border border-slate-300 transition"
                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                    data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-phone="<?= htmlspecialchars($row['phone'] ?? '') ?>"
                                    data-org="<?= htmlspecialchars($row['organization'] ?? '') ?>"
                                    data-category="<?= htmlspecialchars($row['category']) ?>"
                                    data-track="<?= htmlspecialchars($row['sub_interest'] ?? '') ?>"
                                    data-pitch="<?= htmlspecialchars($row['pitch_url'] ?? '') ?>"
                                    data-status="<?= htmlspecialchars($row['status']) ?>"
                                    data-notes="<?= htmlspecialchars($row['admin_notes'] ?? '') ?>"
                                    data-date="<?= date('M d, Y H:i', strtotime($row['created_at'])) ?>"
                                    data-message="<?= htmlspecialchars($row['message']) ?>">
                                    <i class="fa-solid fa-eye mr-1"></i> View
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Classic Details Modal Dialog -->
<div id="lead-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 hidden">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-300 w-full max-w-2xl overflow-hidden animate-fadeIn">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="font-bold text-slate-900 text-sm" id="modal-title">Submission Details</h3>
            </div>
            <button type="button" id="modal-close-btn" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form method="POST" action="index.php" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="action" value="update_status">
            <input type="hidden" name="submission_id" id="modal-sub-id" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Applicant Name</span>
                    <strong class="text-slate-900 text-sm" id="modal-name"></strong>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Organization / Venture</span>
                    <span class="text-slate-800 font-medium text-xs" id="modal-org"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Email Address</span>
                    <a href="#" id="modal-email-link" class="text-emerald-700 hover:underline font-medium"></a>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Phone / WhatsApp</span>
                    <span class="text-slate-800 font-medium" id="modal-phone"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Category & Interest</span>
                    <span class="text-slate-800 font-medium" id="modal-category-track"></span>
                </div>
                <div>
                    <span class="text-slate-400 block font-semibold text-[10px] uppercase">Pitch Deck / Website</span>
                    <a href="#" target="_blank" id="modal-pitch-link" class="text-blue-600 hover:underline font-semibold truncate block">None Provided</a>
                </div>
            </div>

            <div>
                <span class="text-slate-400 block font-semibold text-[10px] uppercase mb-1">Applicant Message / Proposal</span>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 text-xs leading-relaxed max-h-40 overflow-y-auto" id="modal-message"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block font-semibold text-[10px] text-slate-500 uppercase mb-1">Update Status</label>
                    <select name="status" id="modal-status-select" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs font-medium text-slate-800 focus:outline-none focus:border-emerald-600">
                        <option value="New">New</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Shortlisted">Shortlisted</option>
                        <option value="Contacted">Contacted</option>
                        <option value="Archived">Archived</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-[10px] text-slate-500 uppercase mb-1">Admin Internal Notes</label>
                    <input type="text" name="admin_notes" id="modal-notes-input" placeholder="e.g. Sent email for interview" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-emerald-600">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-2">
                <button type="button" id="modal-cancel-btn" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition">
                    Close
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg text-xs transition shadow-sm">
                    Save Changes
                </button>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('lead-modal');
    const closeBtns = [document.getElementById('modal-close-btn'), document.getElementById('modal-cancel-btn')];

    document.querySelectorAll('.view-lead-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('modal-sub-id').value = this.dataset.id;
            document.getElementById('modal-title').textContent = 'Submission #' + this.dataset.id + ' — ' + this.dataset.name;
            document.getElementById('modal-name').textContent = this.dataset.name;
            document.getElementById('modal-org').textContent = this.dataset.org || 'Not Specified';
            
            const emailLink = document.getElementById('modal-email-link');
            emailLink.textContent = this.dataset.email;
            emailLink.href = 'mailto:' + this.dataset.email;

            document.getElementById('modal-phone').textContent = this.dataset.phone || 'Not Provided';
            document.getElementById('modal-category-track').textContent = this.dataset.category + ' • ' + (this.dataset.track || 'General');
            
            const pitchLink = document.getElementById('modal-pitch-link');
            if (this.dataset.pitch && this.dataset.pitch !== '') {
                pitchLink.textContent = this.dataset.pitch;
                pitchLink.href = this.dataset.pitch;
                pitchLink.classList.remove('text-slate-400');
                pitchLink.classList.add('text-blue-600');
            } else {
                pitchLink.textContent = 'None Provided';
                pitchLink.removeAttribute('href');
                pitchLink.classList.add('text-slate-400');
                pitchLink.classList.remove('text-blue-600');
            }

            document.getElementById('modal-message').textContent = this.dataset.message;
            document.getElementById('modal-status-select').value = this.dataset.status;
            document.getElementById('modal-notes-input').value = this.dataset.notes || '';

            modal.classList.remove('hidden');
        });
    });

    closeBtns.forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => modal.classList.add('hidden'));
        }
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>

<?php renderAdminFooter(); ?>
