<?php
/**
 * NextGen Farming Incubation Hub - Core Offerings Manager (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';
$editingOffering = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    if (deleteOffering($delId)) {
        header("Location: offerings.php?deleted=1");
        exit;
    } else {
        $errorMsg = 'Failed to delete core offering.';
    }
}

// Handle Form Submission (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_offering') {
    $saveData = [
        'id'          => !empty($_POST['id']) ? (int)$_POST['id'] : null,
        'title'       => trim($_POST['title'] ?? ''),
        'category'    => trim($_POST['category'] ?? 'field'),
        'badge'       => trim($_POST['badge'] ?? 'Testbeds'),
        'icon'        => trim($_POST['icon'] ?? 'fa-wheat-awn'),
        'description' => trim($_POST['description'] ?? ''),
        'features'    => trim($_POST['features'] ?? ''),
        'button_text' => trim($_POST['button_text'] ?? 'Request Farmland Access →'),
        'button_url'  => trim($_POST['button_url'] ?? 'contact.php?category=Entrepreneur'),
        'sort_order'  => (int)($_POST['sort_order'] ?? 1),
        'status'      => trim($_POST['status'] ?? 'Active'),
    ];

    if (empty($saveData['title']) || empty($saveData['description'])) {
        $errorMsg = 'Please provide both offering title and offering description.';
    } else {
        if (saveOffering($saveData)) {
            header("Location: offerings.php?saved=1");
            exit;
        } else {
            $errorMsg = 'Could not save core offering. Please check database connectivity.';
        }
    }
}

// Check if editing
if (isset($_GET['edit_id'])) {
    $editingOffering = getOfferingById((int)$_GET['edit_id']);
}

$categoryFilter = $_GET['category'] ?? '';
$offerings = getOfferings(false); // get all, including Drafts

if ($categoryFilter && in_array($categoryFilter, ['field', 'capital', 'commercial', 'tech', 'regulatory'])) {
    $offerings = array_filter($offerings, function($o) use ($categoryFilter) {
        return ($o['category'] ?? '') === $categoryFilter;
    });
}

renderAdminHeader('offerings', 'Core Offerings Manager', 'Offerings CMS');
?>

<div class="max-w-6xl">
    
    <!-- Page Header Bar -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Core Offerings & Infrastructure</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage farmland testbeds, venture studio capabilities, cold storage, and regulatory sandbox offerings.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="content.php?tab=offerings#matrix-cms" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-semibold border border-blue-200 transition">
                <i class="fa-solid fa-table-list mr-1.5 text-blue-600"></i> Edit Capability Matrix
            </a>
            <button type="button" id="toggle-form-btn" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Add New Offering
            </button>
            <a href="../offerings.php" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View on Frontend
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['saved'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold">Core offering successfully saved and updated on live website!</span>
            </div>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-trash text-red-600 text-base"></i>
                <span class="font-bold">Core offering has been removed from database.</span>
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

    <!-- Offering Form Card (Add/Edit) -->
    <div id="offering-form-container" class="<?= ($editingOffering || isset($_GET['action'])) ? '' : 'hidden' ?> bg-white p-6 rounded-xl border border-slate-300 shadow-sm mb-6 transition">
        
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                <i class="fa-solid fa-pen-to-square text-emerald-600 mr-2"></i>
                <?= $editingOffering ? 'Edit Core Offering #' . htmlspecialchars($editingOffering['id']) : 'Create New Core Offering' ?>
            </h2>
            <button type="button" id="close-form-btn" class="text-slate-400 hover:text-slate-700 text-xs font-semibold">
                Cancel / Hide Form
            </button>
        </div>

        <form method="POST" action="offerings.php" class="space-y-4 text-xs">
            <input type="hidden" name="action" value="save_offering">
            <?php if ($editingOffering): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($editingOffering['id']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Offering Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($editingOffering['title'] ?? '') ?>" placeholder="e.g. Empirical Living Farmland Testbeds" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 font-medium">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Interactive Filter Category</label>
                    <select name="category" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                        <option value="field" <?= ($editingOffering['category'] ?? '') === 'field' ? 'selected' : '' ?>>Farmland Living Labs (field)</option>
                        <option value="capital" <?= ($editingOffering['category'] ?? '') === 'capital' ? 'selected' : '' ?>>Capital & Syndicates (capital)</option>
                        <option value="commercial" <?= ($editingOffering['category'] ?? '') === 'commercial' ? 'selected' : '' ?>>Corporate Off-Take (commercial)</option>
                        <option value="tech" <?= ($editingOffering['category'] ?? '') === 'tech' ? 'selected' : '' ?>>Hardware & Bio Labs (tech)</option>
                        <option value="regulatory" <?= ($editingOffering['category'] ?? '') === 'regulatory' ? 'selected' : '' ?>>Policy & Standards (regulatory)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Badge Tag</label>
                    <input type="text" name="badge" value="<?= htmlspecialchars($editingOffering['badge'] ?? 'Testbeds') ?>" placeholder="e.g. Testbeds, Venture Capital, Enterprise" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="<?= htmlspecialchars($editingOffering['icon'] ?? 'fa-wheat-awn') ?>" placeholder="e.g. fa-wheat-awn, fa-sack-dollar, fa-microchip" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Button Call-to-Action Text</label>
                    <input type="text" name="button_text" value="<?= htmlspecialchars($editingOffering['button_text'] ?? 'Request Farmland Access →') ?>" placeholder="e.g. Request Farmland Access →" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Button Link Target</label>
                    <input type="text" name="button_url" value="<?= htmlspecialchars($editingOffering['button_url'] ?? 'contact.php?category=Entrepreneur') ?>" placeholder="e.g. contact.php?category=Entrepreneur" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($editingOffering['sort_order'] ?? '1') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Publishing Status</label>
                    <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                        <option value="Active" <?= ($editingOffering['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active (Visible on Website)</option>
                        <option value="Draft" <?= ($editingOffering['status'] ?? '') === 'Draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Offering Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($editingOffering['description'] ?? '') ?></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Key Features / Deliverables (Enter one bullet point per line)</label>
                    <textarea name="features" rows="3" placeholder="On-site IoT telemetry stations&#10;Multi-spectral drone corridor approvals&#10;Cohorts of 200+ participatory farmers" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($editingOffering['features'] ?? '') ?></textarea>
                    <span class="text-[10px] text-slate-400 mt-1 block">Each line will automatically display with a green checkmark on the frontend card.</span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
                <a href="offerings.php" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition border border-slate-300">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    <?= $editingOffering ? 'Update Offering' : 'Save & Publish Offering' ?>
                </button>
            </div>
        </form>

    </div>

    <!-- Filter Bar -->
    <div class="mb-4 flex flex-wrap items-center gap-2 text-xs">
        <span class="text-slate-500 font-semibold mr-1">Filter Category:</span>
        <a href="offerings.php" class="px-3 py-1.5 rounded-lg border <?= empty($categoryFilter) ? 'bg-slate-900 text-white border-slate-900 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            All (<?= count(getOfferings(false)) ?>)
        </a>
        <a href="offerings.php?category=field" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'field' ? 'bg-emerald-700 text-white border-emerald-700 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Farmland Labs
        </a>
        <a href="offerings.php?category=capital" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'capital' ? 'bg-amber-600 text-white border-amber-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Capital & Syndicates
        </a>
        <a href="offerings.php?category=commercial" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'commercial' ? 'bg-blue-600 text-white border-blue-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Corporate Off-Take
        </a>
        <a href="offerings.php?category=tech" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'tech' ? 'bg-purple-600 text-white border-purple-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Hardware & Bio
        </a>
        <a href="offerings.php?category=regulatory" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'regulatory' ? 'bg-emerald-800 text-white border-emerald-800 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Policy & Standards
        </a>
    </div>

    <!-- Offerings Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Configured Core Offerings</h2>
                <p class="text-[11px] text-slate-500">Live cards displayed in the interactive filter grid on offerings.php.</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400"><?= count($offerings) ?> Offerings</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-center">Order</th>
                        <th class="px-4 py-3">Offering & Category</th>
                        <th class="px-4 py-3">Features & Bullet Points</th>
                        <th class="px-4 py-3">Action Button Target</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($offerings)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-layer-group text-3xl mb-2 text-slate-300 block"></i>
                                No core offerings found in this category. Click "Add New Offering" above to add one.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($offerings as $off): 
                            $featureList = array_filter(array_map('trim', explode("\n", $off['features'] ?? '')));
                            $catBadge = match ($off['category'] ?? '') {
                                'field' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'capital' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'commercial' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'tech' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'regulatory' => 'bg-teal-50 text-teal-700 border-teal-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200'
                            };
                        ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-500">
                                    <?= htmlspecialchars($off['sort_order']) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-base shrink-0 border border-slate-200">
                                            <i class="fa-solid <?= htmlspecialchars($off['icon'] ?? 'fa-wheat-awn') ?>"></i>
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 block font-bold text-xs"><?= htmlspecialchars($off['title']) ?></strong>
                                            <div class="flex items-center space-x-2 mt-0.5">
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold border <?= $catBadge ?>">
                                                    <?= htmlspecialchars(ucfirst($off['category'] ?? 'field')) ?>
                                                </span>
                                                <span class="text-[10px] text-slate-400"><?= htmlspecialchars($off['badge'] ?? '') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <p class="text-[11px] text-slate-500 line-clamp-1 mb-1"><?= htmlspecialchars($off['description']) ?></p>
                                    <span class="text-[10px] font-semibold text-slate-400 flex items-center">
                                        <i class="fa-solid fa-list-check mr-1 text-emerald-600"></i> <?= count($featureList) ?> Deliverables
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 font-mono text-[11px]">
                                    <span class="block font-semibold text-slate-800"><?= htmlspecialchars($off['button_text']) ?></span>
                                    <span class="block text-slate-400 text-[10px] truncate max-w-[150px]"><?= htmlspecialchars($off['button_url']) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (($off['status'] ?? '') === 'Active'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 mr-1.5"></span> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                            Draft
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-right space-x-1 whitespace-nowrap">
                                    <a href="offerings.php?edit_id=<?= $off['id'] ?>" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded border border-slate-300 font-semibold transition" title="Edit Offering">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    <a href="offerings.php?delete_id=<?= $off['id'] ?>" onclick="return confirm('Are you sure you want to delete this core offering?');" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded border border-red-200 font-semibold transition" title="Delete Offering">
                                        <i class="fa-solid fa-trash"></i>
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
    const formContainer = document.getElementById('offering-form-container');

    if (toggleBtn && formContainer) {
        toggleBtn.addEventListener('click', function() {
            formContainer.classList.toggle('hidden');
            if (!formContainer.classList.contains('hidden')) {
                formContainer.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    if (closeBtn && formContainer) {
        closeBtn.addEventListener('click', function() {
            formContainer.classList.add('hidden');
        });
    }

    document.querySelectorAll('.alert-close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const box = this.closest('.alert-box');
            if (box) box.style.display = 'none';
        });
    });
});
</script>

<?php renderAdminFooter(); ?>
