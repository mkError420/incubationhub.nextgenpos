<?php
/**
 * NextGen Farming Incubation Hub - Admin Dashboard Overview (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$stats = getAdminDashboardStats();
$recentSubmissions = getHubSubmissions(15);
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
        <a href="content.php" class="inline-flex items-center px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Site Content
        </a>
        <a href="programs.php?action=new" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-plus mr-1.5"></i> New Program
        </a>
        <a href="submissions.php?action=export_csv" class="inline-flex items-center px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-semibold rounded-lg shadow-sm transition">
            <i class="fa-solid fa-download mr-1.5 text-slate-500"></i> Export CSV
        </a>
    </div>
</div>

<!-- 4 Classic Metric KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <!-- KPI 1: Total Leads -->
    <a href="submissions.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-slate-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Total Submissions</span>
            <span class="text-2xl font-extrabold text-slate-900 mt-1 block"><?= $stats['total'] ?></span>
            <span class="text-[11px] text-emerald-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-database mr-1"></i> Logged in MySQL
            </span>
        </div>
        <div class="w-11 h-11 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-inbox"></i>
        </div>
    </a>

    <!-- KPI 2: Pending Action -->
    <a href="submissions.php?status=New" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-amber-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Pending Review</span>
            <span class="text-2xl font-extrabold text-amber-600 mt-1 block"><?= $stats['new'] ?> New</span>
            <span class="text-[11px] text-amber-700 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-clock mr-1"></i> Awaiting screening
            </span>
        </div>
        <div class="w-11 h-11 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
    </a>

    <!-- KPI 3: Cohort Programs -->
    <a href="programs.php" class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm hover:border-blue-300 transition flex items-center justify-between group">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Incubation Tracks</span>
            <span class="text-2xl font-extrabold text-blue-900 mt-1 block"><?= $stats['total_programs'] ?></span>
            <span class="text-[11px] text-blue-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-rocket mr-1"></i> Active in Database
            </span>
        </div>
        <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-lg shrink-0 group-hover:scale-105 transition">
            <i class="fa-solid fa-seedling"></i>
        </div>
    </a>

    <!-- KPI 4: Subscribers -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block">Subscribers</span>
            <span class="text-2xl font-extrabold text-purple-900 mt-1 block"><?= $stats['subscribers'] ?></span>
            <span class="text-[11px] text-purple-600 font-semibold mt-0.5 block flex items-center">
                <i class="fa-solid fa-paper-plane mr-1"></i> Monthly Agri-Briefing
            </span>
        </div>
        <div class="w-11 h-11 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-lg shrink-0">
            <i class="fa-solid fa-envelope-open-text"></i>
        </div>
    </div>

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
