<?php
/**
 * NextGen Farming Incubation Hub - Submissions & Leads Pipeline
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
    fputcsv($output, ['ID', 'Full Name', 'Email', 'Phone', 'Organization', 'Category', 'Track / Interest', 'Pitch URL', 'Status', 'Date', 'Message']);
    
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
            $r['message']
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
        header("Location: submissions.php?updated=1" . ($categoryFilter ? "&category=" . urlencode($categoryFilter) : ''));
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
$allSubmissions = getHubSubmissions(250, $categoryFilter);

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

renderAdminHeader('submissions', 'Submissions & Leads Pipeline');
?>

<div class="max-w-7xl">
    
    <!-- Top Filter & Search Bar -->
    <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm mb-8 space-y-4">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Applications & Inquiries Manager</h2>
                <p class="text-xs text-slate-500 mt-0.5">Filter incoming applicants, update screening status, and export reports.</p>
            </div>

            <!-- Export to CSV -->
            <div class="flex items-center space-x-2">
                <a href="submissions.php?action=export_csv<?= $categoryFilter ? '&category=' . urlencode($categoryFilter) : '' ?><?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow transition">
                    <i class="fa-solid fa-file-csv mr-2 text-emerald-400"></i> Export to CSV
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="pt-2 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
            
            <!-- Category Pills -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="submissions.php<?= $statusFilter ? '?status=' . urlencode($statusFilter) : '' ?>" class="px-3 py-1.5 rounded-lg border <?= empty($categoryFilter) ? 'bg-agro-800 text-white border-agro-800' : 'bg-gray-50 text-slate-600 border-gray-200 hover:bg-gray-100' ?>">
                    All Categories
                </a>
                <a href="submissions.php?category=Entrepreneur<?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Entrepreneur' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-gray-50 text-slate-600 border-gray-200 hover:bg-gray-100' ?>">
                    <i class="fa-solid fa-seedling mr-1"></i> Founders
                </a>
                <a href="submissions.php?category=Investor<?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Investor' ? 'bg-amber-600 text-white border-amber-600' : 'bg-gray-50 text-slate-600 border-gray-200 hover:bg-gray-100' ?>">
                    <i class="fa-solid fa-chart-line mr-1"></i> Investors
                </a>
                <a href="submissions.php?category=Support%20Organisation<?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Support Organisation' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 text-slate-600 border-gray-200 hover:bg-gray-100' ?>">
                    <i class="fa-solid fa-building-columns mr-1"></i> Orgs
                </a>
                <a href="submissions.php?category=Mentor<?= $statusFilter ? '&status=' . urlencode($statusFilter) : '' ?>" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'Mentor' ? 'bg-purple-600 text-white border-purple-600' : 'bg-gray-50 text-slate-600 border-gray-200 hover:bg-gray-100' ?>">
                    <i class="fa-solid fa-user-graduate mr-1"></i> Mentors
                </a>
            </div>

            <!-- Search Form -->
            <form method="GET" action="submissions.php" class="flex items-center space-x-2">
                <?php if ($categoryFilter): ?>
                    <input type="hidden" name="category" value="<?= htmlspecialchars($categoryFilter) ?>">
                <?php endif; ?>
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name, email, org..." class="w-56 px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-agro-700">
                </div>
                <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-slate-700 text-xs font-bold rounded-lg border border-gray-200 transition">
                    Search
                </button>
                <?php if ($search): ?>
                    <a href="submissions.php" class="text-xs text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark"></i></a>
                <?php endif; ?>
            </form>

        </div>

    </div>

    <!-- Submissions Table -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-gray-50 text-slate-700 uppercase font-extrabold tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Applicant & Contact</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Program / Track</th>
                        <th class="px-6 py-4">Pitch / Link</th>
                        <th class="px-6 py-4">Status & Notes</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-inbox text-4xl mb-3 text-slate-300 block"></i>
                                No submissions match the current filter or search criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $row): 
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
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        <a href="mailto:<?= htmlspecialchars($row['email']) ?>" class="hover:text-agro-800"><i class="fa-solid fa-envelope mr-1 text-slate-300"></i><?= htmlspecialchars($row['email']) ?></a>
                                        <?php if (!empty($row['phone'])): ?>
                                            • <span><i class="fa-solid fa-phone mr-1 text-slate-300"></i><?= htmlspecialchars($row['phone']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?= $badgeColor ?>">
                                        <?= htmlspecialchars($row['category']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800 max-w-xs">
                                    <?= htmlspecialchars($row['sub_interest'] ?: 'General Program') ?>
                                    <div class="text-[10px] text-slate-400 mt-0.5">
                                        <?= date('M d, Y h:i A', strtotime($row['created_at'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($row['pitch_url'])): ?>
                                        <a href="<?= htmlspecialchars($row['pitch_url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-agro-50 text-agro-800 border border-agro-200 text-xs font-bold hover:bg-agro-100 transition">
                                            <i class="fa-solid fa-arrow-up-right-from-square mr-1 text-[10px]"></i> Deck Link
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-300">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="submissions.php" class="flex items-center space-x-1.5">
                                        <input type="hidden" name="action" value="update_lead">
                                        <input type="hidden" name="submission_id" value="<?= $row['id'] ?>">
                                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg border text-xs font-semibold bg-gray-50 text-slate-700 focus:outline-none focus:border-agro-700">
                                            <option value="New" <?= $row['status'] === 'New' ? 'selected' : '' ?>>New</option>
                                            <option value="Under Review" <?= $row['status'] === 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                                            <option value="Shortlisted" <?= $row['status'] === 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                                            <option value="Contacted" <?= $row['status'] === 'Contacted' ? 'selected' : '' ?>>Contacted</option>
                                            <option value="Archived" <?= $row['status'] === 'Archived' ? 'selected' : '' ?>>Archived</option>
                                        </select>
                                    </form>
                                    <?php if (!empty($row['admin_notes'])): ?>
                                        <p class="text-[10px] text-slate-400 mt-1 italic truncate max-w-xs" title="<?= htmlspecialchars($row['admin_notes']) ?>">
                                            Note: <?= htmlspecialchars($row['admin_notes']) ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button type="button" onclick="openLeadModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)" class="p-1.5 text-slate-400 hover:text-agro-800 transition" title="View Full Message">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                        </button>
                                        <a href="submissions.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to permanently delete this submission?');" class="p-1.5 text-slate-400 hover:text-red-600 transition" title="Delete">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Lead Detail Modal -->
<div id="lead-modal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-gray-200 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-start justify-between pb-4 border-b border-gray-100 mb-6">
            <div>
                <span id="modal-category" class="inline-block px-2.5 py-1 rounded-full text-xs font-bold mb-2"></span>
                <h3 id="modal-name" class="text-xl font-extrabold text-slate-900"></h3>
                <span id="modal-org" class="text-xs text-slate-500 font-medium block"></span>
            </div>
            <button type="button" onclick="$('#lead-modal').addClass('hidden');" class="text-slate-400 hover:text-slate-700 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                <div>
                    <strong class="text-slate-400 uppercase tracking-wider text-[10px] block">Email:</strong>
                    <span id="modal-email" class="text-slate-800 font-semibold text-xs"></span>
                </div>
                <div>
                    <strong class="text-slate-400 uppercase tracking-wider text-[10px] block">Phone / WhatsApp:</strong>
                    <span id="modal-phone" class="text-slate-800 font-semibold text-xs"></span>
                </div>
                <div>
                    <strong class="text-slate-400 uppercase tracking-wider text-[10px] block">Program Track:</strong>
                    <span id="modal-track" class="text-slate-800 font-semibold text-xs"></span>
                </div>
                <div>
                    <strong class="text-slate-400 uppercase tracking-wider text-[10px] block">Deck / Website:</strong>
                    <a id="modal-deck" href="#" target="_blank" class="text-agro-700 underline font-semibold text-xs truncate block"></a>
                </div>
            </div>

            <div>
                <strong class="text-slate-700 uppercase tracking-wider text-[11px] block mb-2 font-bold">Applicant Message / Pitch:</strong>
                <div id="modal-message" class="p-4 rounded-2xl bg-gray-50 text-slate-700 leading-relaxed text-xs border border-gray-100 whitespace-pre-line"></div>
            </div>

            <form method="POST" action="submissions.php" class="pt-2 border-t border-gray-100 space-y-3">
                <input type="hidden" name="action" value="update_lead">
                <input type="hidden" name="submission_id" id="modal-id">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Update Status</label>
                        <select name="status" id="modal-status-select" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-slate-900">
                            <option value="New">New</option>
                            <option value="Under Review">Under Review</option>
                            <option value="Shortlisted">Shortlisted</option>
                            <option value="Contacted">Contacted</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Internal Evaluation Note</label>
                        <input type="text" name="admin_notes" id="modal-notes" placeholder="e.g. Scheduled pitch call for Thursday" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" onclick="$('#lead-modal').addClass('hidden');" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-slate-700 font-bold text-xs rounded-xl">Close</button>
                    <button type="submit" class="px-4 py-2 bg-agro-800 hover:bg-agro-900 text-white font-bold text-xs rounded-xl shadow">Save Status & Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openLeadModal(lead) {
    $('#modal-id').val(lead.id);
    $('#modal-name').text(lead.full_name);
    $('#modal-org').text(lead.organization || 'Independent Applicant');
    $('#modal-email').text(lead.email);
    $('#modal-phone').text(lead.phone || 'N/A');
    $('#modal-track').text(lead.sub_interest || 'General Program');
    $('#modal-message').text(lead.message);
    $('#modal-status-select').val(lead.status);
    $('#modal-notes').val(lead.admin_notes || '');
    
    if (lead.pitch_url) {
        $('#modal-deck').attr('href', lead.pitch_url).text(lead.pitch_url).show();
    } else {
        $('#modal-deck').text('No pitch link provided').removeAttr('href');
    }

    const badgeColor = lead.category === 'Entrepreneur' ? 'bg-emerald-100 text-emerald-800' :
                       lead.category === 'Investor' ? 'bg-amber-100 text-amber-800' :
                       lead.category === 'Support Organisation' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800';
    $('#modal-category').attr('class', 'inline-block px-2.5 py-1 rounded-full text-xs font-bold mb-2 ' + badgeColor).text(lead.category);

    $('#lead-modal').removeClass('hidden');
}
</script>

<?php renderAdminFooter(); ?>
