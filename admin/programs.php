<?php
/**
 * NextGen Farming Incubation Hub - Programs & Cohorts CMS (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';
$editingProgram = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    if (deleteProgram($delId)) {
        header("Location: programs.php?deleted=1");
        exit;
    } else {
        $errorMsg = 'Failed to delete program.';
    }
}

// Handle Form Submission (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_program') {
    $saveData = [
        'id'           => !empty($_POST['id']) ? (int)$_POST['id'] : null,
        'title'        => trim($_POST['title'] ?? ''),
        'stage_tag'    => trim($_POST['stage_tag'] ?? ''),
        'duration'     => trim($_POST['duration'] ?? ''),
        'funding_text' => trim($_POST['funding_text'] ?? ''),
        'field_access' => trim($_POST['field_access'] ?? ''),
        'description'  => trim($_POST['description'] ?? ''),
        'icon'         => trim($_POST['icon'] ?? 'fa-seedling'),
        'badge_status' => trim($_POST['badge_status'] ?? 'Applications Open'),
        'status'       => trim($_POST['status'] ?? 'Active'),
        'sort_order'   => (int)($_POST['sort_order'] ?? 1),
    ];

    if (empty($saveData['title']) || empty($saveData['description'])) {
        $errorMsg = 'Please provide both program title and program description.';
    } else {
        if (saveProgram($saveData)) {
            header("Location: programs.php?saved=1");
            exit;
        } else {
            $errorMsg = 'Could not save program to database. Please check connection.';
        }
    }
}

// Check if editing
if (isset($_GET['edit_id'])) {
    $editingProgram = getProgramById((int)$_GET['edit_id']);
}

$programs = getPrograms(false); // get all, including Drafts
renderAdminHeader('programs', 'Incubation Programs Manager', 'Programs CMS');
?>

<div class="max-w-6xl">
    
    <!-- Page Header Bar -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Incubation Programs & Cohorts</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage flagship accelerator tracks, founder residencies, SAFE terms, and living lab access.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" id="toggle-form-btn" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Add New Program
            </button>
            <a href="../programs.php" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View on Frontend
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['saved'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold">Program track successfully saved and synchronized with database!</span>
            </div>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-trash text-red-600 text-base"></i>
                <span class="font-bold">Program track has been removed from database.</span>
            </div>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-red-600 text-base"></i>
                <span class="font-bold"><?= htmlspecialchars($errorMsg) ?></span>
            </div>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- Program Form Card (Add/Edit) -->
    <div id="program-form-container" class="<?= ($editingProgram || isset($_GET['action'])) ? '' : 'hidden' ?> bg-white p-6 rounded-xl border border-slate-300 shadow-sm mb-6 transition">
        
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                <i class="fa-solid fa-pen-to-square text-emerald-600 mr-2"></i>
                <?= $editingProgram ? 'Edit Incubation Track #' . htmlspecialchars($editingProgram['id']) : 'Create New Incubation Program Track' ?>
            </h2>
            <button type="button" id="close-form-btn" class="text-slate-400 hover:text-slate-700 text-xs font-semibold">
                Cancel / Hide Form
            </button>
        </div>

        <form method="POST" action="programs.php" class="space-y-4 text-xs">
            <input type="hidden" name="action" value="save_program">
            <?php if ($editingProgram): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($editingProgram['id']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Program Track Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($editingProgram['title'] ?? '') ?>" placeholder="e.g. AgriTech Ignite Accelerator" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 font-medium">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Stage Tag & Subtitle</label>
                    <input type="text" name="stage_tag" value="<?= htmlspecialchars($editingProgram['stage_tag'] ?? '') ?>" placeholder="e.g. Pre-Seed Stage • 16-Week Structured Sprint" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Duration & Mode</label>
                    <input type="text" name="duration" value="<?= htmlspecialchars($editingProgram['duration'] ?? '') ?>" placeholder="e.g. 16 Weeks (Hybrid: Dhaka & Field Labs)" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Funding Terms / Grant Text</label>
                    <input type="text" name="funding_text" value="<?= htmlspecialchars($editingProgram['funding_text'] ?? '') ?>" placeholder="e.g. $35,000 – $50,000 Pre-Seed SAFE" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Living Farmland Pilot Access</label>
                    <input type="text" name="field_access" value="<?= htmlspecialchars($editingProgram['field_access'] ?? '') ?>" placeholder="e.g. Guaranteed Farmland Pilot in Bogura" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="<?= htmlspecialchars($editingProgram['icon'] ?? 'fa-seedling') ?>" placeholder="e.g. fa-seedling, fa-chart-line, fa-dna" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Badge Status Tag</label>
                    <input type="text" name="badge_status" value="<?= htmlspecialchars($editingProgram['badge_status'] ?? 'Applications Open') ?>" placeholder="e.g. Applications Open, Rolling Review" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Publishing Status</label>
                    <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                        <option value="Active" <?= ($editingProgram['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active (Visible on Website)</option>
                        <option value="Draft" <?= ($editingProgram['status'] ?? '') === 'Draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                        <option value="Closed" <?= ($editingProgram['status'] ?? '') === 'Closed' ? 'selected' : '' ?>>Closed (Completed Cohort)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($editingProgram['sort_order'] ?? '1') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Full Program Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($editingProgram['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
                <a href="programs.php" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition border border-slate-300">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    <?= $editingProgram ? 'Update Program' : 'Save & Publish Program' ?>
                </button>
            </div>
        </form>

    </div>

    <!-- Classic Programs Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Existing Programs & Accelerators</h2>
                <p class="text-[11px] text-slate-500">Live cohorts available for application on programs.php.</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400"><?= count($programs) ?> Tracks</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-center">Order</th>
                        <th class="px-4 py-3">Program Track</th>
                        <th class="px-4 py-3">Duration & Field Lab</th>
                        <th class="px-4 py-3">Funding Terms</th>
                        <th class="px-4 py-3">Badge & Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($programs)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                No programs found. Click "Add New Program" to create one.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($programs as $prog): ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-400">
                                    <?= htmlspecialchars($prog['sort_order']) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold shrink-0">
                                            <i class="fa-solid <?= htmlspecialchars($prog['icon']) ?>"></i>
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 font-bold block text-sm"><?= htmlspecialchars($prog['title']) ?></strong>
                                            <span class="text-[11px] text-slate-500"><?= htmlspecialchars($prog['stage_tag']) ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-700">
                                    <span class="block font-medium"><?= htmlspecialchars($prog['duration']) ?></span>
                                    <span class="text-[11px] text-slate-500"><?= htmlspecialchars($prog['field_access']) ?></span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-800">
                                    <?= htmlspecialchars($prog['funding_text']) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-200 block w-max mb-1">
                                        <?= htmlspecialchars($prog['badge_status']) ?>
                                    </span>
                                    <span class="inline-flex items-center text-[10px] font-semibold <?= $prog['status'] === 'Active' ? 'text-emerald-700' : 'text-slate-400' ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $prog['status'] === 'Active' ? 'bg-emerald-500' : 'bg-slate-400' ?> mr-1.5"></span>
                                        <?= htmlspecialchars($prog['status']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                                    <a href="programs.php?edit_id=<?= $prog['id'] ?>" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded border border-slate-300 transition">
                                        <i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit
                                    </a>
                                    <a href="programs.php?delete_id=<?= $prog['id'] ?>" onclick="return confirm('Are you sure you want to delete this program track?');" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-semibold text-xs rounded border border-red-200 transition">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-form-btn');
    const closeBtn = document.getElementById('close-form-btn');
    const container = document.getElementById('program-form-container');

    if (toggleBtn && container) {
        toggleBtn.addEventListener('click', function() {
            container.classList.remove('hidden');
            container.scrollIntoView({ behavior: 'smooth' });
        });
    }

    if (closeBtn && container) {
        closeBtn.addEventListener('click', function() {
            container.classList.add('hidden');
        });
    }
});
</script>

<?php renderAdminFooter(); ?>
