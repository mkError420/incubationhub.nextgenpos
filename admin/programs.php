<?php
/**
 * NextGen Farming Incubation Hub - Programs & Cohorts CMS
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
        $errorMsg = 'Please provide at least a program title and description.';
    } else {
        if (saveProgram($saveData)) {
            header("Location: programs.php?saved=1");
            exit;
        } else {
            $errorMsg = 'Could not save program to database.';
        }
    }
}

// Check if editing
if (isset($_GET['edit_id'])) {
    $editingProgram = getProgramById((int)$_GET['edit_id']);
}

$programs = getPrograms(false); // get all, including Drafts
renderAdminHeader('programs', 'Programs & Cohorts CMS');
?>

<div class="max-w-6xl">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Incubation Programs & Tracks</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Add, modify, and publish accelerator cohorts, founder residencies, and funding tracks.
            </p>
        </div>
        <div>
            <a href="#program-form-card" onclick="$('#program-form-card').removeClass('hidden');" class="inline-flex items-center px-4 py-2.5 bg-agro-800 hover:bg-agro-900 text-white font-bold text-xs rounded-xl shadow transition">
                <i class="fa-solid fa-plus mr-2"></i> Add New Program
            </a>
        </div>
    </div>

    <?php if (isset($_GET['saved'])): ?>
        <div class="alert-box mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> Program successfully saved and updated live!</span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box mb-6 p-4 rounded-2xl bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between">
            <span class="font-bold flex items-center"><i class="fa-solid fa-trash text-red-600 mr-2 text-base"></i> Program removed.</span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box mb-6 p-4 rounded-2xl bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between">
            <span class="font-bold"><?= htmlspecialchars($errorMsg) ?></span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- Program Form Card (Add/Edit) -->
    <div id="program-form-card" class="<?= ($editingProgram || isset($_GET['action'])) ? '' : 'hidden' ?> bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-xl mb-8 transition">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-agro-50 text-agro-700 flex items-center justify-center text-lg">
                    <i class="fa-solid <?= !empty($editingProgram['icon']) ? htmlspecialchars($editingProgram['icon']) : 'fa-seedling' ?>"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">
                        <?= $editingProgram ? 'Edit Program: ' . htmlspecialchars($editingProgram['title']) : 'Create New Accelerator Track' ?>
                    </h3>
                    <span class="text-xs text-slate-500">Provide cohort eligibility, duration, and funding structure</span>
                </div>
            </div>
            <?php if ($editingProgram): ?>
                <a href="programs.php" class="text-xs text-slate-400 hover:text-slate-600 font-bold">
                    <i class="fa-solid fa-xmark mr-1"></i> Cancel Edit
                </a>
            <?php else: ?>
                <button type="button" onclick="$('#program-form-card').addClass('hidden');" class="text-xs text-slate-400 hover:text-slate-600 font-bold">
                    <i class="fa-solid fa-xmark mr-1"></i> Close
                </button>
            <?php endif; ?>
        </div>

        <form method="POST" action="programs.php" class="space-y-5">
            <input type="hidden" name="action" value="save_program">
            <?php if ($editingProgram): ?>
                <input type="hidden" name="id" value="<?= $editingProgram['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Program Title *</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($editingProgram['title'] ?? '') ?>" placeholder="e.g. AgriTech Ignite Accelerator" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Stage / Tagline</label>
                    <input type="text" name="stage_tag" value="<?= htmlspecialchars($editingProgram['stage_tag'] ?? '') ?>" placeholder="e.g. Pre-Seed Stage • 16-Week Sprint" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Duration & Format</label>
                    <input type="text" name="duration" value="<?= htmlspecialchars($editingProgram['duration'] ?? '') ?>" placeholder="e.g. 16 Weeks (Hybrid: Dhaka & Field)" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Funding / Grant Facility</label>
                    <input type="text" name="funding_text" value="<?= htmlspecialchars($editingProgram['funding_text'] ?? '') ?>" placeholder="e.g. $35,000 – $50,000 SAFE" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Field Lab / Corporate Access</label>
                    <input type="text" name="field_access" value="<?= htmlspecialchars($editingProgram['field_access'] ?? '') ?>" placeholder="e.g. Living Farmlands in Bogura" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Badge Status (Ribbon)</label>
                    <input type="text" name="badge_status" value="<?= htmlspecialchars($editingProgram['badge_status'] ?? 'Applications Open') ?>" placeholder="e.g. Applications Open / Rolling" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="<?= htmlspecialchars($editingProgram['icon'] ?? 'fa-seedling') ?>" placeholder="e.g. fa-seedling, fa-chart-line, fa-dna" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Publication Status</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold text-slate-900 focus:outline-none focus:border-agro-700">
                        <option value="Active" <?= ($editingProgram['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active (Published on website)</option>
                        <option value="Draft" <?= ($editingProgram['status'] ?? '') === 'Draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                        <option value="Closed" <?= ($editingProgram['status'] ?? '') === 'Closed' ? 'selected' : '' ?>>Closed (Completed cohort)</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Program Description *</label>
                    <textarea name="description" rows="3" required placeholder="Describe the cohort objectives, eligible technologies, and outcomes..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700 leading-relaxed"><?= htmlspecialchars($editingProgram['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3">
                <button type="submit" class="px-6 py-2.5 bg-agro-800 hover:bg-agro-900 text-white font-bold text-xs rounded-xl shadow transition">
                    <i class="fa-solid fa-save mr-1.5"></i> Save Program
                </button>
            </div>
        </form>
    </div>

    <!-- Programs Grid List -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($programs as $prog): 
            $statusBadge = match ($prog['status']) {
                'Active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'Draft' => 'bg-gray-100 text-slate-700 border-gray-200',
                'Closed' => 'bg-red-100 text-red-800 border-red-200',
                default => 'bg-gray-100 text-slate-700',
            };
        ?>
            <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border <?= $statusBadge ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?= $prog['status'] === 'Active' ? 'bg-emerald-500' : 'bg-slate-400' ?> mr-1.5"></span>
                            <?= htmlspecialchars($prog['status']) ?>
                        </span>
                        <span class="text-[11px] font-bold text-agro-700 bg-agro-50 px-2.5 py-0.5 rounded-md">
                            <?= htmlspecialchars($prog['badge_status']) ?>
                        </span>
                    </div>

                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-agro-50 text-agro-700 flex items-center justify-center text-lg">
                            <i class="fa-solid <?= htmlspecialchars($prog['icon']) ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-base text-slate-900"><?= htmlspecialchars($prog['title']) ?></h3>
                            <span class="text-xs text-slate-400 font-medium"><?= htmlspecialchars($prog['stage_tag']) ?></span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        <?= htmlspecialchars($prog['description']) ?>
                    </p>

                    <div class="space-y-1.5 text-[11px] text-slate-500 pb-4 border-b border-gray-100">
                        <div><strong>Funding:</strong> <?= htmlspecialchars($prog['funding_text']) ?></div>
                        <div><strong>Duration:</strong> <?= htmlspecialchars($prog['duration']) ?></div>
                        <?php if (!empty($prog['field_access'])): ?>
                            <div><strong>Access:</strong> <?= htmlspecialchars($prog['field_access']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <span class="text-[11px] text-slate-400 font-mono">ID: #<?= $prog['id'] ?></span>
                    <div class="flex items-center space-x-2">
                        <a href="programs.php?edit_id=<?= $prog['id'] ?>" class="px-3 py-1.5 text-xs font-bold text-slate-700 hover:text-agro-800 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition">
                            <i class="fa-solid fa-pen mr-1 text-[10px]"></i> Edit
                        </a>
                        <a href="programs.php?delete_id=<?= $prog['id'] ?>" onclick="return confirm('Are you sure you want to delete this program?');" class="px-3 py-1.5 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition">
                            <i class="fa-solid fa-trash mr-1 text-[10px]"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<?php renderAdminFooter(); ?>
