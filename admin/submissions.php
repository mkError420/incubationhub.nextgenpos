<?php
/**
 * NextGen Farming Incubation Hub - Submissions & Leads Pipeline (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$categoryFilter = $_GET['category'] ?? null;
$statusFilter = $_GET['status'] ?? null;
$search = trim($_GET['search'] ?? '');

// Handle CSV Export
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    $rows = getHubSubmissions(1000, $categoryFilter);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=nextgen_hub_submissions_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Full Name', 'Email', 'Phone', 'Organization', 'Category', 'Track / Interest', 'Pitch URL', 'Status', 'Date', 'Message', 'Admin Notes']);
    
    foreach ($rows as $r) {
        if ($statusFilter && $r['status'] !== $statusFilter) continue;
        fputcsv($output, [
            $r['id'],
            $r['full_name'],
            $r['email'],
            $r['phone'],
            $r['organization'],
            $r['category'],
            $r['sub_interest'],
            $r['pitch_url'],
            $r['status'],
            $r['created_at'],
            $r['message'],
            $r['admin_notes'] ?? ''
        ]);
    }
    fclose($output);
    exit;
}

// Handle Status & Notes Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_lead') {
    $subId = (int)($_POST['submission_id'] ?? 0);
    $newStatus = trim($_POST['status'] ?? '');
    $adminNotes = trim($_POST['admin_notes'] ?? '');
    if ($subId && $newStatus) {
        updateSubmissionStatus($subId, $newStatus, $adminNotes ?: null);
        $redirectUrl = "submissions.php?updated=1";
        if ($categoryFilter) $redirectUrl .= "&category=" . urlencode($categoryFilter);
        if ($statusFilter) $redirectUrl .= "&status=" . urlencode($statusFilter);
        header("Location: " . $redirectUrl);
        exit;
    }
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    deleteSubmission($delId);
    header("Location: submissions.php?deleted=1");
    exit;
}

// Fetch submissions
$allSubmissions = getHubSubmissions(300, $categoryFilter);

// Filter by status & search if applicable
$submissions = array_filter($allSubmissions, function ($item) use ($statusFilter, $search) {
    if ($statusFilter && $item['status'] !== $statusFilter) {
        return false;
    }
    if ($search !== '') {
        $haystack = strtolower($item['full_name'] . ' ' . $item['email'] . ' ' . $item['organization'] . ' ' . $item['message']);
        if (strpos($haystack, strtolower($search)) === false) {
            return false;
        }
    }
    return true;
});

renderAdminHeader('submissions', 'Submissions & Pipeline Manager', 'Leads Manager');
?>

<div class="max-w-7xl">
    
    <!-- Top Action & Filter Header -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Applications & Leads Pipeline</h1>
            <p class="text-xs text-slate-500 mt-0.5">Filter, evaluate, and manage incoming applications across all stakeholder tracks.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="submissions.php?action=export_csv<?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?><?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="inline-flex items-center px-3.5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-sm transition">
                <i class="fa-solid fa-file-csv mr-1.5 text-emerald-400"></i> Export to CSV
            </a>
            <a href="../submissions.php" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-300 transition">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> Public View
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['updated'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> Lead status and notes updated successfully!</span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-trash text-red-600 mr-2 text-base"></i> Submission record deleted from database.</span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- Filter Bar Card -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form method="GET" action="submissions.php" class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-12 gap-3 text-xs">
            
            <!-- Category Filter -->
            <div class="lg:col-span-3">
                <label class="block font-semibold text-slate-600 mb-1">Category</label>
                <select name="category" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-emerald-600">
                    <option value="">All Categories (Any)</option>
                    <option value="Entrepreneur" <?= $categoryFilter === 'Entrepreneur' ? 'selected' : '' ?>>Entrepreneurs / Founders</option>
                    <option value="Investor" <?= $categoryFilter === 'Investor' ? 'selected' : '' ?>>Investors / VCs</option>
                    <option value="Support Organisation" <?= $categoryFilter === 'Support Organisation' ? 'selected' : '' ?>>Support Organizations</option>
                    <option value="Mentor" <?= $categoryFilter === 'Mentor' ? 'selected' : '' ?>>Domain Mentors</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="lg:col-span-3">
                <label class="block font-semibold text-slate-600 mb-1">Pipeline Status</label>
                <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-emerald-600">
                    <option value="">All Statuses (Any)</option>
                    <option value="New" <?= $statusFilter === 'New' ? 'selected' : '' ?>>New</option>
                    <option value="Under Review" <?= $statusFilter === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                    <option value="Shortlisted" <?= $statusFilter === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                    <option value="Contacted" <?= $statusFilter === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                    <option value="Archived" <?= $statusFilter === 'Archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>

            <!-- Keyword Search -->
            <div class="lg:col-span-4">
                <label class="block font-semibold text-slate-600 mb-1">Search Keywords</label>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Name, email, org, message..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-800 focus:outline-none focus:border-emerald-600">
            </div>

            <!-- Action Buttons -->
            <div class="lg:col-span-2 flex items-end space-x-2">
                <button type="submit" class="w-full px-3 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    Filter
                </button>
                <a href="submissions.php" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg border border-slate-300 transition text-center" title="Reset Filters">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>

        </form>
    </div>

    <!-- Classic Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Submissions Registry</h2>
                <p class="text-[11px] text-slate-500">Showing <?= count($submissions) ?> application records</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400">Total in Database: <?= count($allSubmissions) ?></span>
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
                        <th class="px-4 py-3">Received</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-slate-400">
                                No records match the selected filter criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $row): 
                            $badgeColor = match ($row['category']) {
                                'Entrepreneur' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'Investor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Support Organisation' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Mentor' => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 font-mono font-semibold text-slate-500">
                                    #<?= htmlspecialchars($row['id']) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <strong class="font-bold text-slate-900 block"><?= htmlspecialchars($row['full_name']) ?></strong>
                                    <span class="text-[11px] text-slate-500 block"><?= htmlspecialchars($row['organization'] ?: 'Independent') ?></span>
                                    <span class="text-[11px] text-slate-400 block"><?= htmlspecialchars($row['email']) ?></span>
                                    <?php if (!empty($row['phone'])): ?>
                                        <span class="text-[10px] text-slate-400 block"><i class="fa-solid fa-phone text-[9px] mr-1"></i><?= htmlspecialchars($row['phone']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border <?= $badgeColor ?>">
                                        <?= htmlspecialchars($row['category']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-medium text-slate-800 max-w-xs truncate" title="<?= htmlspecialchars($row['sub_interest']) ?>">
                                    <?= htmlspecialchars($row['sub_interest'] ?: 'General Program') ?>
                                    <?php if (!empty($row['pitch_url'])): ?>
                                        <a href="<?= htmlspecialchars($row['pitch_url']) ?>" target="_blank" class="block text-[10px] text-blue-600 hover:underline">
                                            <i class="fa-solid fa-link mr-1"></i> Deck / Link
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="submissions.php" class="inline">
                                        <input type="hidden" name="action" value="update_lead">
                                        <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded border text-xs font-medium bg-white text-slate-800 border-slate-300 focus:outline-none focus:border-emerald-600">
                                            <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                            <option value="Under Review" <?= $row['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                                            <option value="Shortlisted" <?= $row['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                            <option value="Contacted" <?= $row['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                            <option value="Archived" <?= $row['status'] === 'Archived' ? 'selected' : '' ?>>Archived</option>
                                        </select>
                                    </form>
                                    <?php if (!empty($row['admin_notes'])): ?>
                                        <span class="block text-[10px] text-slate-500 italic mt-0.5" title="<?= htmlspecialchars($row['admin_notes']) ?>">
                                            <i class="fa-solid fa-note-sticky text-amber-500 mr-1"></i> Notes
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-[11px] text-slate-500 whitespace-nowrap">
                                    <?= date('M d, Y', strtotime($row['created_at'])) ?>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
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
                                        <i class="fa-solid fa-eye text-[10px] mr-1"></i> View
                                    </button>
                                    <a href="submissions.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this submission record?');" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-semibold text-xs rounded border border-red-200 transition">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Dialog -->
<div id="lead-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 hidden">
    <div class="bg-white rounded-xl shadow-2xl border border-slate-300 w-full max-w-2xl overflow-hidden">
        
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <h3 class="font-bold text-slate-900 text-sm" id="modal-title">Submission Details</h3>
            <button type="button" id="modal-close-btn" class="text-slate-400 hover:text-slate-700 p-1">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        <form method="POST" action="submissions.php" class="p-6 space-y-4 text-xs">
            <input type="hidden" name="action" value="update_lead">
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
                    <label class="block font-semibold text-[10px] text-slate-500 uppercase mb-1">Pipeline Status</label>
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
