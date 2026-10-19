<?php
/**
 * NextGen Farming Incubation Hub - Network & Ecosystem Directory CMS (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$successMsg = '';
$errorMsg = '';
$editingPartner = null;

// Handle Delete
if (isset($_GET['delete_id'])) {
    $delId = (int)$_GET['delete_id'];
    if (deleteNetworkPartner($delId)) {
        header("Location: networking.php?deleted=1");
        exit;
    } else {
        $errorMsg = 'Failed to delete partner record.';
    }
}

// Handle Form Submission (Add or Edit)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_partner') {
    $saveData = [
        'id'          => !empty($_POST['id']) ? (int)$_POST['id'] : null,
        'name'        => trim($_POST['name'] ?? ''),
        'category'    => trim($_POST['category'] ?? 'founder'),
        'badge'       => trim($_POST['badge'] ?? 'Portfolio Startup'),
        'location'    => trim($_POST['location'] ?? 'Dhaka'),
        'tagline'     => trim($_POST['tagline'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'icon'        => trim($_POST['icon'] ?? 'fa-seedling'),
        'meta_label'  => trim($_POST['meta_label'] ?? 'Cohort Alum'),
        'link_text'   => trim($_POST['link_text'] ?? 'Connect →'),
        'link_url'    => trim($_POST['link_url'] ?? 'contact.php?category=Entrepreneur'),
        'sort_order'  => (int)($_POST['sort_order'] ?? 1),
        'status'      => trim($_POST['status'] ?? 'Active'),
    ];

    if (empty($saveData['name']) || empty($saveData['description'])) {
        $errorMsg = 'Please provide both partner name and description.';
    } else {
        if (saveNetworkPartner($saveData)) {
            header("Location: networking.php?saved=1");
            exit;
        } else {
            $errorMsg = 'Could not save partner to database. Please check connection.';
        }
    }
}

// Check if editing
if (isset($_GET['edit_id'])) {
    $editingPartner = getNetworkPartnerById((int)$_GET['edit_id']);
}

$categoryFilter = $_GET['category'] ?? '';
$partners = getNetworkPartners(false); // get all, including Drafts

if ($categoryFilter && in_array($categoryFilter, ['founder', 'investor', 'academic', 'corporate', 'impact'])) {
    $partners = array_filter($partners, function($p) use ($categoryFilter) {
        return ($p['category'] ?? '') === $categoryFilter;
    });
}

renderAdminHeader('networking', 'Network & Partners Manager', 'Network CMS');
?>

<div class="max-w-6xl">
    
    <!-- Page Header Bar -->
    <div class="mb-6 bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Global Network & Ecosystem Partners</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage portfolio founders, venture capital syndicates, academic institutions, and corporate off-takers.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" id="toggle-form-btn" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition">
                <i class="fa-solid fa-plus mr-1.5"></i> Add New Partner
            </button>
            <a href="../networking.php" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-300 transition">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5 text-slate-500"></i> View on Frontend
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if (isset($_GET['saved'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-bold">Ecosystem partner successfully saved and updated on live directory!</span>
            </div>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-box mb-6 p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-trash text-red-600 text-base"></i>
                <span class="font-bold">Partner record has been removed from database.</span>
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

    <!-- Partner Form Card (Add/Edit) -->
    <div id="partner-form-container" class="<?= ($editingPartner || isset($_GET['action'])) ? '' : 'hidden' ?> bg-white p-6 rounded-xl border border-slate-300 shadow-sm mb-6 transition">
        
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center">
                <i class="fa-solid fa-pen-to-square text-emerald-600 mr-2"></i>
                <?= $editingPartner ? 'Edit Partner #' . htmlspecialchars($editingPartner['id']) : 'Create New Partner / Alliance Record' ?>
            </h2>
            <button type="button" id="close-form-btn" class="text-slate-400 hover:text-slate-700 text-xs font-semibold">
                Cancel / Hide Form
            </button>
        </div>

        <form method="POST" action="networking.php" class="space-y-4 text-xs">
            <input type="hidden" name="action" value="save_partner">
            <?php if ($editingPartner): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($editingPartner['id']) ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Partner / Organization Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($editingPartner['name'] ?? '') ?>" placeholder="e.g. KrishiSense IoT, PRAN-RFL Agro" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 font-medium">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Ecosystem Category</label>
                    <select name="category" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                        <option value="founder" <?= ($editingPartner['category'] ?? '') === 'founder' ? 'selected' : '' ?>>Agri-Founders (founder)</option>
                        <option value="investor" <?= ($editingPartner['category'] ?? '') === 'investor' ? 'selected' : '' ?>>Venture Capital (investor)</option>
                        <option value="academic" <?= ($editingPartner['category'] ?? '') === 'academic' ? 'selected' : '' ?>>Academic & R&D (academic)</option>
                        <option value="corporate" <?= ($editingPartner['category'] ?? '') === 'corporate' ? 'selected' : '' ?>>Corporate Agribusiness (corporate)</option>
                        <option value="impact" <?= ($editingPartner['category'] ?? '') === 'impact' ? 'selected' : '' ?>>Multilateral & Donors (impact)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Badge Tag</label>
                    <input type="text" name="badge" value="<?= htmlspecialchars($editingPartner['badge'] ?? 'Portfolio Startup') ?>" placeholder="e.g. Portfolio Startup, Syndicate Partner, Research Anchor" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Location / Geography</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($editingPartner['location'] ?? 'Dhaka') ?>" placeholder="e.g. Bogura & Dhaka, Netherlands, Mymensingh" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Tagline / Specialization</label>
                    <input type="text" name="tagline" value="<?= htmlspecialchars($editingPartner['tagline'] ?? '') ?>" placeholder="e.g. Precision Soil & Irrigation Tech" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">FontAwesome Icon Class</label>
                    <input type="text" name="icon" value="<?= htmlspecialchars($editingPartner['icon'] ?? 'fa-seedling') ?>" placeholder="e.g. fa-seedling, fa-landmark, fa-microscope, fa-truck-fast" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Meta / Role Label (Bottom Left)</label>
                    <input type="text" name="meta_label" value="<?= htmlspecialchars($editingPartner['meta_label'] ?? 'Cohort 02 Alum') ?>" placeholder="e.g. Cohort 02 Alum, Co-Investment Lead, Off-Take Offerer" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Action Link Text</label>
                    <input type="text" name="link_text" value="<?= htmlspecialchars($editingPartner['link_text'] ?? 'Connect →') ?>" placeholder="e.g. Connect →, Syndicate Deals →, Partner Terms →" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Action Link Target</label>
                    <input type="text" name="link_url" value="<?= htmlspecialchars($editingPartner['link_url'] ?? 'contact.php?category=Entrepreneur') ?>" placeholder="e.g. contact.php?category=Entrepreneur" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="<?= htmlspecialchars($editingPartner['sort_order'] ?? '1') ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Publishing Status</label>
                    <select name="status" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                        <option value="Active" <?= ($editingPartner['status'] ?? '') === 'Active' ? 'selected' : '' ?>>Active (Visible on Directory)</option>
                        <option value="Draft" <?= ($editingPartner['status'] ?? '') === 'Draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Full Partnership Summary / Bio <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 leading-relaxed"><?= htmlspecialchars($editingPartner['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
                <a href="networking.php" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition border border-slate-300">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    <?= $editingPartner ? 'Update Partner' : 'Save & Publish Partner' ?>
                </button>
            </div>
        </form>

    </div>

    <!-- Filter Bar -->
    <div class="mb-4 flex flex-wrap items-center gap-2 text-xs">
        <span class="text-slate-500 font-semibold mr-1">Filter Category:</span>
        <a href="networking.php" class="px-3 py-1.5 rounded-lg border <?= empty($categoryFilter) ? 'bg-slate-900 text-white border-slate-900 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            All (<?= count(getNetworkPartners(false)) ?>)
        </a>
        <a href="networking.php?category=founder" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'founder' ? 'bg-emerald-700 text-white border-emerald-700 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Agri-Founders
        </a>
        <a href="networking.php?category=investor" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'investor' ? 'bg-amber-600 text-white border-amber-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Venture Capital
        </a>
        <a href="networking.php?category=academic" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'academic' ? 'bg-blue-600 text-white border-blue-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Academic & R&D
        </a>
        <a href="networking.php?category=corporate" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'corporate' ? 'bg-purple-600 text-white border-purple-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Corporate Agribusiness
        </a>
        <a href="networking.php?category=impact" class="px-3 py-1.5 rounded-lg border <?= $categoryFilter === 'impact' ? 'bg-red-600 text-white border-red-600 font-bold' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50' ?> transition">
            Multilateral & Donors
        </a>
    </div>

    <!-- Directory Partners Data Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-8">
        
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Configured Ecosystem Directory</h2>
                <p class="text-[11px] text-slate-500">Live cards displayed in the interactive filter directory on networking.php.</p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400"><?= count($partners) ?> Partners</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-center">Order</th>
                        <th class="px-4 py-3">Partner & Category</th>
                        <th class="px-4 py-3">Location & Specialization</th>
                        <th class="px-4 py-3">Role & CTA Link</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php if (empty($partners)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-network-wired text-3xl mb-2 text-slate-300 block"></i>
                                No directory partners found in this category. Click "Add New Partner" above to add one.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($partners as $p): 
                            $catBadge = match ($p['category'] ?? '') {
                                'founder' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'investor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'academic' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'corporate' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'impact' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200'
                            };
                        ?>
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3 text-center font-mono font-bold text-slate-500">
                                    <?= htmlspecialchars($p['sort_order']) ?>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-base shrink-0 border border-slate-200">
                                            <i class="fa-solid <?= htmlspecialchars($p['icon'] ?? 'fa-seedling') ?>"></i>
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 block font-bold text-xs"><?= htmlspecialchars($p['name']) ?></strong>
                                            <div class="flex items-center space-x-2 mt-0.5">
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold border <?= $catBadge ?>">
                                                    <?= htmlspecialchars(ucfirst($p['category'] ?? 'founder')) ?>
                                                </span>
                                                <span class="text-[10px] text-slate-400"><?= htmlspecialchars($p['badge'] ?? '') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <div class="flex items-center text-slate-700 text-[11px] font-semibold mb-0.5">
                                        <i class="fa-solid fa-location-dot mr-1 text-slate-400"></i>
                                        <?= htmlspecialchars($p['location'] ?? 'Dhaka') ?>
                                    </div>
                                    <span class="text-[11px] text-slate-500 line-clamp-1"><?= htmlspecialchars($p['tagline'] ?? '') ?></span>
                                </td>
                                <td class="px-4 py-3 text-slate-600 text-[11px]">
                                    <span class="block font-semibold text-slate-800"><?= htmlspecialchars($p['meta_label'] ?? '') ?></span>
                                    <span class="block text-emerald-700 font-bold text-[10px]"><?= htmlspecialchars($p['link_text'] ?? 'Connect →') ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (($p['status'] ?? '') === 'Active'): ?>
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
                                    <a href="networking.php?edit_id=<?= $p['id'] ?>" class="inline-flex items-center px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded border border-slate-300 font-semibold transition" title="Edit Partner">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </a>
                                    <a href="networking.php?delete_id=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this partner record?');" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded border border-red-200 font-semibold transition" title="Delete Partner">
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
    const formContainer = document.getElementById('partner-form-container');

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
