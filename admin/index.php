<?php
/**
 * NextGen Farming Incubation Hub - Admin Dashboard
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$stats = getAdminDashboardStats();
$recentSubmissions = getHubSubmissions(10);
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

renderAdminHeader('dashboard', 'Incubation Dashboard Overview');
?>

<!-- Welcome Banner & Quick CMS Navigation -->
<div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-gradient-to-r from-agro-900 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl border border-agro-800 relative overflow-hidden">
    <div class="relative z-10">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-2">
            <i class="fa-solid fa-seedling mr-1.5 text-emerald-400"></i> Active Cohort 04 Intake
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
            Welcome back, <?= htmlspecialchars(getCurrentAdmin()['full_name']) ?>
        </h2>
        <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-xl leading-relaxed">
            Manage your incoming AgriTech founders, investor deal flows, accelerator tracks, and dynamic website content in real time.
        </p>
    </div>
    
    <!-- Quick Action Buttons -->
    <div class="relative z-10 flex flex-wrap gap-2.5">
        <a href="content.php" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow transition flex items-center">
            <i class="fa-solid fa-pen-to-square mr-2"></i> Edit Site Content
        </a>
        <a href="programs.php?action=new" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold text-xs rounded-xl border border-white/20 backdrop-blur transition flex items-center">
            <i class="fa-solid fa-plus mr-2 text-emerald-300"></i> New Program
        </a>
    </div>

    <!-- Decorative Gradient Bubble -->
    <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>
</div>

<?php if (isset($_GET['status_updated'])): ?>
    <div class="alert-box mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            <span class="font-bold">Submission status has been successfully updated!</span>
        </div>
        <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
<?php endif; ?>

<!-- 4 Key Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <!-- Stat 1: Total Leads -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Inquiries</span>
            <span class="text-3xl font-black text-slate-900 mt-1 block"><?= $stats['total'] ?></span>
            <span class="text-[11px] text-emerald-600 font-semibold mt-1 flex items-center">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> Across All 4 Stakeholders
            </span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl">
            <i class="fa-solid fa-inbox"></i>
        </div>
    </div>

    <!-- Stat 2: New Review Pipeline -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Action Needed</span>
            <span class="text-3xl font-black text-amber-600 mt-1 block"><?= $stats['new'] ?> New</span>
            <span class="text-[11px] text-amber-700 font-semibold mt-1 flex items-center">
                <i class="fa-solid fa-clock mr-1"></i> Pending Initial Review
            </span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center text-xl">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </div>

    <!-- Stat 3: Active Programs -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Cohort Programs</span>
            <span class="text-3xl font-black text-blue-900 mt-1 block"><?= $stats['total_programs'] ?> Tracks</span>
            <span class="text-[11px] text-blue-600 font-semibold mt-1 flex items-center">
                <a href="programs.php" class="underline hover:text-blue-800">Manage Tracks &rarr;</a>
            </span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl">
            <i class="fa-solid fa-rocket"></i>
        </div>
    </div>

    <!-- Stat 4: Subscribers -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Newsletter Audience</span>
            <span class="text-3xl font-black text-purple-900 mt-1 block"><?= $stats['subscribers'] ?></span>
            <span class="text-[11px] text-purple-600 font-semibold mt-1 flex items-center">
                <i class="fa-solid fa-paper-plane mr-1"></i> Agri-Dispatch Briefing
            </span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

</div>

<!-- Breakdown by Category Pills -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <a href="submissions.php?category=Entrepreneur" class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-200 hover:bg-emerald-100 transition flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-seedling"></i>
        </div>
        <div>
            <span class="text-xs font-bold text-emerald-950 block">Founders</span>
            <span class="text-sm font-extrabold text-emerald-800"><?= $stats['entrepreneurs'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Investor" class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 hover:bg-amber-100 transition flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div>
            <span class="text-xs font-bold text-amber-950 block">Investors</span>
            <span class="text-sm font-extrabold text-amber-800"><?= $stats['investors'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Support%20Organisation" class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 hover:bg-blue-100 transition flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div>
            <span class="text-xs font-bold text-blue-950 block">Organizations</span>
            <span class="text-sm font-extrabold text-blue-800"><?= $stats['organisations'] ?> Submissions</span>
        </div>
    </a>

    <a href="submissions.php?category=Mentor" class="p-4 rounded-2xl bg-purple-50/70 border border-purple-200 hover:bg-purple-100 transition flex items-center space-x-3">
        <div class="w-9 h-9 rounded-xl bg-purple-600 text-white flex items-center justify-center text-sm font-bold">
            <i class="fa-solid fa-user-graduate"></i>
        </div>
        <div>
            <span class="text-xs font-bold text-purple-950 block">Mentors</span>
            <span class="text-sm font-extrabold text-purple-800"><?= $stats['mentors'] ?> Submissions</span>
        </div>
    </a>
</div>

<!-- Recent Submissions Review Table -->
<div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Recent Application Leads</h3>
            <p class="text-xs text-slate-500 mt-0.5">Most recent incoming submissions from website forms.</p>
        </div>
        <a href="submissions.php" class="text-xs font-bold text-agro-800 hover:text-agro-900 flex items-center">
            View All in Lead Manager <i class="fa-solid fa-arrow-right ml-1.5 text-[10px]"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
            <thead class="bg-gray-50 text-slate-700 uppercase font-extrabold tracking-wider border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">ID</th>
                    <th class="px-6 py-4">Applicant & Entity</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Track / Focus</th>
                    <th class="px-6 py-4">Status & Action</th>
                    <th class="px-6 py-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                <?php if (empty($recentSubmissions)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                            No submissions recorded yet. Submissions from the public form will appear here.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentSubmissions as $row): 
                        $badgeColor = match ($row['category']) {
                            'Entrepreneur' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'Investor' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'Support Organisation' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'Mentor' => 'bg-purple-100 text-purple-800 border-purple-200',
                            default => 'bg-gray-100 text-gray-800 border-gray-200',
                        };
                    ?>
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-400">
                                #<?= htmlspecialchars($row['id']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($row['full_name']) ?></div>
                                <div class="text-[11px] text-slate-500 font-medium"><?= htmlspecialchars($row['organization'] ?: 'Independent') ?></div>
                                <div class="text-[11px] text-slate-400">
                                    <i class="fa-solid fa-envelope mr-1 text-slate-300"></i><?= htmlspecialchars($row['email']) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?= $badgeColor ?>">
                                    <?= htmlspecialchars($row['category']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800 max-w-xs truncate" title="<?= htmlspecialchars($row['sub_interest']) ?>">
                                <?= htmlspecialchars($row['sub_interest'] ?: 'General Program') ?>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="index.php" class="flex items-center space-x-2">
                                    <input type="hidden" name="action" value="update_status">
                                    <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                    <select name="status" onchange="this.form.submit()" class="px-2.5 py-1.5 rounded-lg border text-xs font-semibold bg-gray-50 text-slate-700 focus:outline-none focus:border-agro-700">
                                        <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                        <option value="Under Review" <?= $row['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                                        <option value="Shortlisted" <?= $row['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                        <option value="Contacted" <?= $row['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                        <option value="Archived" <?= $row['status'] === 'Archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-[11px] text-slate-400 whitespace-nowrap">
                                <?= date('M d, Y', strtotime($row['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php renderAdminFooter(); ?>
